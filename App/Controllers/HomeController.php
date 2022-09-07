<?php

namespace App\Controllers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\Report;
use App\Models\Starred;
use App\Models\Teacher;
use App\Utils\Sanitize;

class HomeController
{
    private $logo;
    private $settings;
    private $questionModel;
    private $verta;
    private $categoryModel;
    private $levelModel;
    private $teacherModel;
    private $request;
    private $answerModel;
    private $starredModel;
    private $reportModel;

    public function __construct()
    {
        global $logo;
        global $settings;
        global $verta;
        global $request;
        $this->logo = $logo;
        $this->settings = $settings;
        $this->questionModel = new Question();
        $this->verta = $verta;
        $this->categoryModel = new Category();
        $this->levelModel = new Level();
        $this->teacherModel = new Teacher();
        $this->request = $request;
        $this->answerModel = new Answer();
        $this->starredModel = new Starred();
        $this->reportModel = new Report();
    }

    public function index()
    {
        if (isset($_GET['logout'])) {
            unset($_SESSION['admin']);
            unset($_SESSION['user']);
        }

        if (isUser() or isTeacher() or isGuest()) {
            if(isset($_GET['s'])) {
                $searchKey = Sanitize::clean($this->request->s);
    
                $where1 = ['title[~]' => $searchKey, 'content[~]' => $searchKey];
                
                $where2 = [];
    
                $cat = Sanitize::clean($this->request->selectCat);
                if(is_numeric($cat)) {
                    $where2['category_id'] = $cat;
                }
    
                $level = Sanitize::clean($this->request->selectLevel);
                if(is_numeric($level)) {
                    $where2['level_id'] = $level;
                }
    
                $teacher = Sanitize::clean($this->request->selectTeacher);
                if(is_numeric($teacher)) {
                    $where2['teacher_id'] = $teacher;
                }
    
                $where2['verified'] = 1;
            
                $where = ['OR' => $where1, 'AND' => $where2];
                $where =['AND' => $where];
                // $where = ['AND' => ['OR' =>['title[~]' => '2', 'content[~]' => '2'], 'AND' => ['teacher_id' => 1,'verified' => 1]]];
    
                $allQuestions = $this->questionModel->paginate(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], $where);
            } else {
                $allQuestions = $this->questionModel->paginate(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], ['verified' => 1]);
            }
            
        }

        if (isAdmin()) {
            if(isset($_GET['s'])) {
                $searchKey = Sanitize::clean($this->request->s);
    
                $where1 = ['title[~]' => $searchKey, 'content[~]' => $searchKey];
                
                $where2 = [];
    
                $cat = Sanitize::clean($this->request->selectCat);
                if(is_numeric($cat)) {
                    $where2['category_id'] = $cat;
                }
    
                $level = Sanitize::clean($this->request->selectLevel);
                if(is_numeric($level)) {
                    $where2['level_id'] = $level;
                }
    
                $teacher = Sanitize::clean($this->request->selectTeacher);
                if(is_numeric($teacher)) {
                    $where2['teacher_id'] = $teacher;
                }
    
                $where2['verified'] = [0,1];
            
                $where = ['OR' => $where1, 'AND' => $where2];
                $where =['AND' => $where];
                // $where = ['AND' => ['OR' =>['title[~]' => '2', 'content[~]' => '2'], 'AND' => ['teacher_id' => 1,'verified' => 1]]];
    
                $allQuestions = $this->questionModel->paginate(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], $where);
            } else {
                $allQuestions = $this->questionModel->paginate(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], ['verified' => [0,1]]);
            }
            
        }

        $categories = $this->categoryModel->getAll();

        $levels = $this->levelModel->getAll();

        $teachers = $this->teacherModel->get(['id', 'fullname'], ['role' => ['teacher', 'admin']]);

        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo,
            'questions' => $allQuestions['all'],
            'pageCount' => $allQuestions['pageCount'],
            'page' => $allQuestions['page'],
            'count' => $allQuestions['allCount'],
            'verta' => $this->verta,
            'categories' => $categories,
            'levels' => $levels,
            'teachers' => $teachers
        ];

        view('home.home', $data);
    }

    public function mkqu()
    {
        $categories = $this->categoryModel->get(['id', 'title']);
        $levels = $this->levelModel->get(['id', 'title']);
        $teachers = $this->teacherModel->get(['id', 'fullname'], ['OR' => [
            'role' => 'teacher',
            'username' => 'defaultuser'
        ]]);

        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo,
            'categories' => $categories,
            'levels' => $levels,
            'teachers' => $teachers
        ];

        view('home.mkqu', $data);
    }

    public function saveQu()
    {
        $data = [
            'settings' => $this->settings,
            'logo' => $this->logo,
        ];

        $title = Sanitize::clean($this->request->title);
        if (Sanitize::notGiven($title)) {
            $_SESSION['mkquMsg'] = 'لطفا عنوان سوال را وارد کنید!';
            $_SESSION['class'] = 'danger';
            redirect('mkqu');
            die();
        }
        $category = $this->request->category;
        if (!is_numeric($category)) {
            $_SESSION['mkquMsg'] = 'لطفا دسته‌بندی مد نظر خود را انتخاب کنید!';
            $_SESSION['class'] = 'danger';
            redirect('mkqu');
            die();
        }

        $level = $this->request->level;
        if (!is_numeric($level)) {
            $_SESSION['mkquMsg'] = 'لطفا سطح مد نظر خود را انتخاب کنید!';
            $_SESSION['class'] = 'danger';
            redirect('mkqu');
            die();
        }

        $teacherId = $this->request->teacherId;
        if (!is_numeric($teacherId)) {
            $_SESSION['mkquMsg'] = 'از چه کسی دوست دارید سوال خود را بپرسید!';
            $_SESSION['class'] = 'danger';
            redirect('mkqu');
            die();
        }
        $content = Sanitize::clean($this->request->content);
        if (Sanitize::notGiven($content)) {
            $_SESSION['mkquMsg'] = 'لطفا متن سوال را بنویسید!';
            $_SESSION['class'] = 'danger';
            redirect('mkqu');
            die();
        }

        if (isUser() or isTeacher()) {
            $userId = $_SESSION['user']['id'];
        }
        if (isAdmin()) {
            $userId = $_SESSION['admin']['id'];
        }

        $createdQuestion = $this->questionModel->create([
            'teacher_id' => $teacherId,
            'user_id' => $userId,
            'level_id' => $level,
            'category_id' => $category,
            'title' => $title,
            'content' => $content
        ]);
        
        if ($createdQuestion) {
            $_SESSION['mkquMsg'] = 'سوال شما با موفقیت ذخیره شد و منتظر تایید مدیر است!';
            $_SESSION['class'] = 'success';
            redirect('mkqu');
            die();
        }
    }

    public function unverify()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }
        $res = $questionId = $this->request->questionId;
        $this->questionModel->update(['verified' => 0], ['id' => $questionId]);
        echo $res;
    }

    public function verify()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }
        $questionId = $this->request->questionId;
        $this->questionModel->update(['verified' => 1], ['id' => $questionId]);
        echo $questionId;
    }

    public function remove()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }
        
        $questionId = $this->request->questionId;
        $answers = findAnswers($questionId);
        $starredQuestions = $this->starredModel->get(['id'], ['question_id' => $questionId]);
        $reportedQuestions = $this->reportModel->get(['id'], ['question_id' => $questionId]);
        
        foreach($answers as $answer){
            $this->answerModel->delete(['id' => $answer['id']]);
        }

        foreach($starredQuestions as $starredQuestion) {
            $this->starredModel->delete(['id' => $starredQuestion['id']]);
        }

        foreach($reportedQuestions as $reportedQuestion) {
            $this->reportModel->delete(['id' => $reportedQuestion['id']]);
        }

        $res = $this->questionModel->delete(['id' => $questionId]);
        
        echo $res;
    }

    public function answer()
    {
        $questionId = Sanitize::clean($this->request->question_id);
        if (!is_numeric($questionId)) {
            die('Access Forbidden');
        }

        $userId = Sanitize::clean($this->request->user_id);
        if (!is_numeric($userId)) {
            die('Access Forbidden');
        }

        $answer = Sanitize::clean($this->request->answer);
        if (Sanitize::notGiven($answer)) {
            redirect('');
        }

        $pageNumber = Sanitize::clean($this->request->pageNumber);
        if(!is_numeric($pageNumber)){
            $pageNumber = 1;
        }

        $createdAnswer = $this->answerModel->create([
            'teacher_id' => $userId,
            'question_id' => $questionId,
            'content' => $answer
        ]);
        
        if ($createdAnswer) {
            $_SESSION['answerMsg'] = 'پاسخ شما با موفقیت ارسال شد.';
            $_SESSION['class'] = 'success';
            redirect('?page=' . $pageNumber);
        }
    }

    public function removeAnswer()
    {
        $answerId = $this->request->routeParam('answer_id');
        if (!is_numeric($answerId)) {
            die('Access Forbidden');
        }
        $deletedAnswer = $this->answerModel->delete(['id' => $answerId]);
        if ($deletedAnswer) {
            $_SESSION['answerMsg'] = 'پاسخ مورد نظر با موفقیت حذف شد.';
            $_SESSION['class'] = 'success';
            redirect('');
        }
    }

    public function starred()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }

        $questionId = $this->request->questionId;
        $userId = $this->request->userId;
        if (!is_numeric($questionId) and !is_numeric($userId)) {
            die('Access Forbidden');
        }
        $starred = $this->starredModel->get(['id'], [
                'question_id' => $questionId,
                'user_id' => $userId
            ]);
            
        if (sizeof($starred) == 0) {
            $this->starredModel->create([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        } else {
            $this->starredModel->delete([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        }
        return true;
    }

    public function showStarredQ()
    {
        $userId = $this->request->routeParam('user_id');
        if (!is_numeric($userId)) {
            die('Access Denied');
        }
        $allQuestions = [];
        $all = $this->starredModel->paginate(['question_id'], ['user_id' => $userId]);
        foreach ($all['all'] as $starred) {
            $questionId = $starred['question_id'];
            $questions = $this->questionModel->get(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], ['id' => $questionId]);
            $allQuestions[] = $questions[0];
        }
        $data = [
                'logo' => $this->logo,
                'questions' => $allQuestions,
                'pageCount' => $all['pageCount'],
                'page' => $all['page'],
                'count' => $all['allCount'],
                'verta' => $this->verta
            ];

        view('home.starred', $data);
    }

    public function report()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }

        $questionId = $this->request->questionId;
        $userId = $this->request->userId;
        if (!is_numeric($questionId) and !is_numeric($userId)) {
            die('Access Forbidden');
        }
        $reportItem = $this->reportModel->get(['id'], [
                'question_id' => $questionId,
                'user_id' => $userId
            ]);
            
        if (sizeof($reportItem) == 0) {
            $this->reportModel->create([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        } else {
            $this->reportModel->delete([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        }
        return true;
    }

    public function showReports()
    {
        $allQuestions = [];
        $all = $this->reportModel->paginate(['question_id', 'user_id', 'created_at'], ['id[>]' => 0]);
        foreach ($all['all'] as $report) {
            $questionId = $report['question_id'];
            $questions = $this->questionModel->get(['id', 'title', 'content', 'teacher_id', 'user_id', 'category_id', 'level_id', 'created_at', 'verified'], ['id' => $questionId]);
            $user = $this->teacherModel->get(['fullname', 'id'], ['id' => $report['user_id']]);
            $questions[0]['reporting_user'] = $user[0]['fullname'];
            $questions[0]['reporting_userId'] = $user[0]['id'];
            $allQuestions[] = $questions[0];
        }
        $data = [
                'logo' => $this->logo,
                'questions' => $allQuestions,
                'pageCount' => $all['pageCount'],
                'page' => $all['page'],
                'count' => $all['allCount'],
                'verta' => $this->verta
            ];

        view('home.reports', $data);
    }

    public function removeReport()
    {
        if (!isAjaxRequest()) {
            die("Invalid Request!");
        }

        $questionId = $this->request->questionId;
        $userId = $this->request->userId;
        if (!is_numeric($questionId) and !is_numeric($userId)) {
            die('Access Forbidden');
        }
        $reportItem = $this->reportModel->get(['id'], [
                'question_id' => $questionId,
                'user_id' => $userId
            ]);
            
        if (sizeof($reportItem) == 0) {
            $this->reportModel->create([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        } else {
            $this->reportModel->delete([
                    'question_id' => $questionId,
                    'user_id' => $userId
                ]);
        }
        return true;
    }
}
