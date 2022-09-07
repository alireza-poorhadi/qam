<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Level;
use App\Models\Teacher;
use App\Utils\Sanitize;
use App\Utils\Validator;
use Symfony\Component\Finder\Glob;

class AdminController
{
    private $teacherModel;
    private $categoryModel;
    private $levelModel;
    private $settings;
    private $logo;

    public function __construct()
    {
        global $settings;
        global $logo;
        $this->teacherModel =  new Teacher();
        $this->categoryModel = new Category();
        $this->levelModel = new Level();
        $this->settings = $settings;
        $this->logo = $logo;
    }

    public function index()
    {
        global $request;
        if (isset($_GET['logout'])) {
            unset($_SESSION['admin']);
            redirect('admin');
            exit;
        }

        $allAdmins = $this->teacherModel->get(['id', 'fullname', 'username'], ['AND' => ['role' => 'admin', 'username[!]' => 'defaultuser']]);

        $allTeachers = $this->teacherModel->get(['id', 'fullname' , 'username'], ['role' => 'teacher']);

        $allCategories = $this->categoryModel->getAll();

        $allLevels = $this->levelModel->getAll();

        $data = [
            'admins' => $allAdmins,
            'teachers' => $allTeachers,
            'categories' => $allCategories,
            'levels' => $allLevels,
            'settings' => $this->settings,
            'logo' => $this->logo
        ];

        view('admin.admin', $data);
    }

    public function addAdmin()
    {
        global $request;
        $username = $request->username;
        if (Sanitize::notGiven($username)) {
            $_SESSION['adminMsg'] = 'لطفا نام کاربری را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectAdmin';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }
        $username = Sanitize::clean($username);
        $newAdmin = $this->teacherModel->get(['username', 'fullname'], ['username' => $username]);
        if (empty($newAdmin)) {
            $_SESSION['adminMsg'] = 'مدیر جدید لازم است قبلا به عنوان استاد ثبت‌نام کرده باشد!';
            $_SESSION['whichDiv'] = 'rectAdmin';
            $_SESSION['class'] = 'danger';
            redirect('admin');
        }
        $res = $this->teacherModel->update([
            'role' => 'admin'
        ], [
            'username' => $username
        ]);
        
        $_SESSION['adminMsg'] = 'مدیر جدید افزوده شد.';
        $_SESSION['whichDiv'] = 'rectAdmin';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function delete()
    {
        global $request;
        $adminId = $request->routeParam('admin_id');
        $this->teacherModel->update([
            'role' => 'teacher'
        ], [
            'id' => $adminId
        ]);
        $_SESSION['adminMsg'] = 'مدیر حذف شد!';
        $_SESSION['whichDiv'] = 'rectAdmin';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function loginPage()
    {
        view('admin.login');
    }

    public function login()
    {
        global $request;
        $username = Sanitize::clean($request->username);
        $password = Sanitize::clean($request->password);
        if (Sanitize::notGiven($username)) {
            $_SESSION['loginMsg'] = 'لطفا نام کاربری را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('admin/login');
            die();
        }
        if (Sanitize::notGiven($password)) {
            $_SESSION['loginMsg'] = 'لطفا رمز عبور را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('admin/login');
            die();
        }
        $admin = $this->teacherModel->get(['username', 'fullname', 'password' , 'role'], ['AND' => ['username' => $username, 'password' => sha1($password), 'role' => 'admin']]);
        if (empty($admin)) {
            $_SESSION['loginMsg'] = 'نام کاربری یا رمز عبور اشتباه است و یا شما به عنوان مدیر در سیستم شناخته نشده‌اید!';
            $_SESSION['class'] = 'danger';
            view('admin/login');
            die();
        }
        $_SESSION['admin'] = $admin[0];
        redirect('admin');
    }

    public function setting()
    {
        $msg = '';
        global $request;
        $companyName = Sanitize::clean($request->companyName);
        if (Sanitize::notGiven($companyName)) {
            $companyName = $this->settings['companyName'];
        }

        $pageSize = Sanitize::clean($request->pageSize);
        if (Sanitize::notGiven($pageSize)) {
            $pageSize = $this->settings['pageSize'];
        }
        if (!is_numeric($pageSize)) {
            $pageSize = 10;
        }
        
        $setting = [
            'companyName' => $companyName,
            'pageSize' => $pageSize
        ];

        $SettingFilename = BASEPATH . '/storage/json/setting.json';
        $h = fopen($SettingFilename, 'w');
        fputs($h, json_encode($setting));
        fclose($h);

        $error = $_FILES['uploadedLogo']['error'];
        if (!$error) {
            $uploadedFileName = $_FILES['uploadedLogo']['name'];
            $allowedFileExtensions = ['png', 'jpg', 'jpeg', 'gif'];
            $separatedUploadedFileName = explode('.', $uploadedFileName);
            $fileExtension = end($separatedUploadedFileName);
            $fileSize = $_FILES['uploadedLogo']['size'];

            if ($fileSize > 1000000) {
                $msg = 'اندازه فایل نباید بیشتر از یک مگابایت باشد!';
            }
            
            if (!in_array($fileExtension, $allowedFileExtensions)) {
                $msg = 'فرمت فایل باید png، jpg و یا gif باشد!';
            }
            $previousLogo = Glob(BASEPATH . '/assets/img/NFLogo.*');
            unlink($previousLogo[0]);
            $destFileName = BASEPATH . '/assets/img/NFLogo.' . $fileExtension;
            $tempFile = $_FILES['uploadedLogo']['tmp_name'];
            if (!move_uploaded_file($tempFile, $destFileName)) {
                $msg = 'خطا در روند آپلود لوگو!';
            }
        }
        
        $msg = 'تنظیمات با موفقیت اجرا شدند.';
        $_SESSION['msg'] = $msg;
        redirect('admin');
    }

    public function addTeacher()
    {
        global $request;
        $username = Sanitize::clean($request->username);
        if (Sanitize::notGiven($username)) {
            $_SESSION['teacherMsg'] = 'لطفا نام کاربری را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        $possiblePreviousUsername = $this->teacherModel->get(['username'], ['username' => $username]);
        if (sizeof($possiblePreviousUsername) > 0) {
            $_SESSION['teacherMsg'] = 'این نام کاربری قبلا انتخاب شده است!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        $fullname = Sanitize::clean($request->fullname);
        if (Sanitize::notGiven($fullname)) {
            $_SESSION['teacherMsg'] = 'لطفا نام نمایشی را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        $password = Sanitize::clean($request->password);
        if (Sanitize::notGiven($password)) {
            $_SESSION['teacherMsg'] = 'لطفا رمز عبور را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        if (strlen($password) < 6) {
            $_SESSION['regMsg'] = 'رمز عبور باید حداقل شش کاراکتر باشد!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        $repassword = Sanitize::clean($request->repassword);
        if (Sanitize::notGiven($repassword)) {
            $_SESSION['teacherMsg'] = 'لطفا تکرار رمز عبور را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        if ($password != $repassword) {
            $_SESSION['teacherMsg'] = 'رمزهای عبور وارد شده یکسان نیستند!';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }

        $createdTeacher = $this->teacherModel->create([
            'username' => $username,
            'fullname' => $fullname,
            'password' => sha1($password),
            'role' => 'teacher'
        ]);

        if ($createdTeacher) {
            $_SESSION['teacherMsg'] = 'پاسخ‌دهنده با موفقیت افزوده شد.';
            $_SESSION['whichDiv'] = 'rectTeacher';
            $_SESSION['class'] = 'success';
            redirect('admin');
            die();
        }
    }

    public function deleteTeacher()
    {
        global $request;
        $teacherId = $request->routeParam('teacher_id');
        $this->teacherModel->delete([
            'id' => $teacherId
        ]);
        $_SESSION['teacherMsg'] = 'پاسخ‌دهنده حذف شد!';
        $_SESSION['whichDiv'] = 'rectTeacher';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }
}
