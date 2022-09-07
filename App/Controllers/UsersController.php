<?php

namespace App\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Teacher;
use App\Utils\Sanitize;

class UsersController
{
    private $teacherModel;
    private $logo;
    private $settings;
    private $request;
    private $questionModel;
    private $verta;
    private $answerModel;

    public function __construct()
    {
        global $logo;
        global $settings;
        global $request;
        global $verta;
        $this->teacherModel = new Teacher();
        $this->logo = $logo;
        $this->settings = $settings;
        $this->request = $request;
        $this->questionModel = new Question();
        $this->verta = $verta;
        $this->answerModel = new Answer();
    }

    public function index()
    {
        $allUsers = $this->teacherModel->paginate(['id', 'fullname', 'role'], ['username[!]' => 'defaultuser']);

        $data = [
            'logo' => $this->logo,
            'settings' => $this->settings,
            'users' => $allUsers['all'],
            'pageCount' => $allUsers['pageCount'],
            'page' => $allUsers['page'],
            'allCount' => $allUsers['allCount']
        ];

        view('home.users', $data);
    }

    public function profile()
    {
        $userId = Sanitize::clean($this->request->routeParam('user_id'));
        if (!is_numeric($userId)) {
            die('Access Forbidden');
        }
        $user = $this->teacherModel->get(['id', 'username', 'fullname'], ['id' => $userId]);
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo,
            'user' => $user[0]
        ];

        view('home.profile', $data);
    }

    public function saveProfile()
    {
        $userId = Sanitize::clean($this->request->userId);
        if (!is_numeric($userId)) {
            die('Access Forbidden');
        }
        $userForHere = $this->teacherModel->find($userId);

        $user = [
            'id' => $userForHere->id,
            'username' => $userForHere->username,
            'fullname' => $userForHere->fullname
        ];

        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo,
            'user' => $user
        ];

        $username = Sanitize::clean($this->request->username);
        $userForCheck = $username;
        if (Sanitize::notGiven($username)) {
            $username = $userForHere->username;
        }

        $possiblePreviousUsername = $this->teacherModel->get(['username'], ['username' => $username]);
        if (sizeof($possiblePreviousUsername) > 0 and $possiblePreviousUsername[0]['username'] != $userForCheck) {
            $_SESSION['profileMsg'] = 'این نام کاربری قبلا انتخاب شده است!';
            $_SESSION['class'] = 'danger';
            view('home.profile', $data);
            die();
        }

        $fullname = Sanitize::clean($this->request->fullname);
        if (Sanitize::notGiven($fullname)) {
            $fullname = $userForHere->fullname;
        }

        $password = Sanitize::clean($this->request->password);
        if (Sanitize::notGiven($password)) {
            $updatedUser = $this->teacherModel->update([
                'username' => $username,
                'fullname' => $fullname,
            ], [
                'id' => $userId
            ]);

            $user = $this->teacherModel->get(['id', 'username' , 'fullname', 'role'], ['id' => $userId]);
            $user = $user[0];
            if ($user['role'] == 'user' or $user['role'] == 'teacher') {
                $_SESSION['user'] = $user;
            } else {
                $_SESSION['admin'] = $user;
            }
            $_SESSION['profileMsg'] = 'اطلاعات ذخیره شد.';
            $_SESSION['class'] = 'success';
            redirect('profile/' . $userId);
            die();
        }

        if (strlen($password) < 6) {
            $_SESSION['profileMsg'] = 'رمز عبور باید حداقل شش کاراکتر باشد!';
            $_SESSION['class'] = 'danger';
            view('home.profile', $data);
            die();
        }

        $repassword = Sanitize::clean($this->request->repassword);
        
        if ($password != $repassword) {
            $_SESSION['profileMsg'] = 'رمزهای عبور وارد شده یکسان نیستند!';
            $_SESSION['class'] = 'danger';
            view('home.profile', $data);
            die();
        }

        $updatedUser = $this->teacherModel->update([
            'username' => $username,
            'fullname' => $fullname,
            'password' => sha1($password),
        ], [
            'id' => $userId
        ]);

        $user = $this->teacherModel->get(['id', 'username' , 'fullname', 'role'], ['id' => $userId]);
        $user = $user[0];
        if ($user['role'] == 'user' or $user['role'] == 'teacher') {
            $_SESSION['user'] = $user;
        } else {
            $_SESSION['admin'] = $user;
        }
        
        $_SESSION['profileMsg'] = 'اطلاعات ذخیره شد.';
        $_SESSION['class'] = 'success';
        redirect('profile/' . $userId);
    }

    public function removeUser()
    {
        $userId = $this->request->routeParam('user_id');
        if(!is_numeric($userId)) {
            die('Access Denied');
        }
        $this->teacherModel->delete(['id' => $userId]);
        $_SESSION['delMsg'] = 'کاربر حذف شد.';
        $_SESSION['class'] = 'success';
        redirect('users');
    }

    public function showUserQ()
    {
        $userId = $this->request->routeParam('user_id');
        if(!is_numeric($userId)) {
            die('Access Denied');
        }

        $all = $this->questionModel->paginate(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], ['AND' => ['AND' => ['user_id' => $userId, 'verified' => 1]]]);
        
        $data = [
            'logo' => $this->logo,
            'questions' => $all['all'],
            'pageCount' => $all['pageCount'],
            'page' => $all['page'],
            'count' => $all['allCount'],
            'verta' => $this->verta
        ];

        view('home.showUserQ', $data);
    }

    public function showUserA()
    {
        $userId = $this->request->routeParam('user_id');
        if(!is_numeric($userId)) {
            die('Access Denied');
        }
        $all = $this->answerModel->paginate(['id', 'teacher_id', 'question_id', 'content', 'created_at'], ['teacher_id' => $userId]);

        $data = [
            'logo' => $this->logo,
            'answers' => $all['all'],
            'pageCount' => $all['pageCount'],
            'page' => $all['page'],
            'count' => $all['allCount'],
            'verta' => $this->verta
        ];

        view('home.showUserA', $data);
    }
}
