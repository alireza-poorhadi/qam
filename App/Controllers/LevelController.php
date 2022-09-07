<?php

namespace App\Controllers;

use App\Models\Level;
use App\Utils\Sanitize;

class LevelController
{
    private $levelModel;

    public function __construct()
    {
        $this->levelModel = new Level();
    }

    public function addLevel()
    {
        global $request;
        $title = $request->title;
        if (Sanitize::notGiven($title)) {
            $_SESSION['levelMsg'] = 'لطفا سطح مورد نظر خود را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectLevel';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }
        $title = Sanitize::clean($title);

        $this->levelModel->create([
            'title' => $title
        ]);
        
        $_SESSION['levelMsg'] = 'سطح مورد نظر افزوده شد.';
        $_SESSION['whichDiv'] = 'rectLevel';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function editLevel()
    {
        global $request;
        $levelId = $request->routeParam('level_id');
        if (!is_numeric($levelId)) {
            die('Not Allowed Operation!');
        }
        $level = $this->levelModel->find($levelId);
        $data = [
            'id' => $level->id,
            'title' => $level->title
        ];

        view('admin.editLevel', $data);
    }

    public function saveLevel()
    {
        global $request;
        $levelId = $request->levelId;
        $title = $request->title;
        $title = Sanitize::clean($title);
        if (!is_numeric($levelId)) {
            die('Not Allowed Operation!');
        }
        $this->levelModel->update([
            'title' => $title
        ], [
            'id' => $levelId
        ]);
        $_SESSION['levelMsg'] = 'سطح مورد نظر ویرایش شد.';
        $_SESSION['whichDiv'] = 'rectLevel';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function deleteLevel()
    {
        global $request;
        $levelId = $request->routeParam('level_id');
        if (!is_numeric($levelId)) {
            die('Not Allowed Operation!');
        }
        $this->levelModel->delete([
            'id' => $levelId
        ]);
        $_SESSION['levelMsg'] = 'سطح مورد نظر حذف شد.';
        $_SESSION['whichDiv'] = 'rectLevel';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }
}
