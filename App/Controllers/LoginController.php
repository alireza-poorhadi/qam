<?php

namespace App\Controllers;

use App\Models\Teacher;
use App\Utils\Sanitize;

class LoginController
{
    private $settings;
    private $logo;
    private $teacherModel;

    public function __construct()
    {
        global $settings;
        global $logo;

        $this->settings = $settings;
        $this->logo = $logo;
        $this->teacherModel = new Teacher();
    }

    public function loginPage()
    {
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo
        ];
        view('login.loginPage', $data);
    }

    public function regPage()
    {
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo
        ];
        view('login/regPage', $data);
    }

    public function register()
    {
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo
        ];

        global $request;
        $username = Sanitize::clean($request->username);
        if (Sanitize::notGiven($username)) {
            $_SESSION['regMsg'] = 'لطفا نام کاربری را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        $possiblePreviousUsername = $this->teacherModel->get(['username'], ['username' => $username]);
        if (sizeof($possiblePreviousUsername) > 0) {
            $_SESSION['regMsg'] = 'این نام کاربری قبلا انتخاب شده است!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        $fullname = Sanitize::clean($request->fullname);
        if (Sanitize::notGiven($fullname)) {
            $_SESSION['regMsg'] = 'لطفا نام نمایشی را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        $password = Sanitize::clean($request->password);
        if (Sanitize::notGiven($password)) {
            $_SESSION['regMsg'] = 'لطفا رمز عبور را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        if (strlen($password) < 6) {
            $_SESSION['regMsg'] = 'رمز عبور باید حداقل شش کاراکتر باشد!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        $repassword = Sanitize::clean($request->repassword);
        if (Sanitize::notGiven($repassword)) {
            $_SESSION['regMsg'] = 'لطفا تکرار رمز عبور را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        if ($password != $repassword) {
            $_SESSION['regMsg'] = 'رمزهای عبور وارد شده یکسان نیستند!';
            $_SESSION['class'] = 'danger';
            view('login/regPage', $data);
            die();
        }

        $createdUser = $this->teacherModel->create([
            'username' => $username,
            'fullname' => $fullname,
            'password' => sha1($password),
            'role' => 'user'
        ]);

        if ($createdUser) {
            $_SESSION['regMsg'] = 'کاربر با موفقیت افزوده شد.';
            $_SESSION['class'] = 'success';
            view('login/regPage', $data);
            die();
        }
    }

    public function login()
    {
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo
        ];
        
        global $request;

        $username = Sanitize::clean($request->username);
        if (Sanitize::notGiven($username)) {
            $_SESSION['loginMsg'] = 'لطفا نام کاربری را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/loginPage', $data);
            die();
        }

        $password = Sanitize::clean($request->password);
        if (Sanitize::notGiven($password)) {
            $_SESSION['loginMsg'] = 'لطفا رمز عبور را وارد کنید!';
            $_SESSION['class'] = 'danger';
            view('login/loginPage', $data);
            die();
        }

        $user = $this->teacherModel->get(['id', 'username', 'fullname', 'role'], [
            'username' => $username,
            'password' => sha1($password)
        ]);

        if (sizeOf($user) == 0) {
            $_SESSION['loginMsg'] = 'نام کاربری یا رمز عبور نادرست است!';
            $_SESSION['class'] = 'danger';
            view('login/loginPage', $data);
            die();
        }

        $user = $user[0];

        if ($user['role'] == 'admin') {
            $_SESSION['admin'] = $user;
        } else if ($user['role'] == 'user' or $user['role'] == 'teacher') {
            $_SESSION['user'] = $user;
        }

        redirect('');
    }
}