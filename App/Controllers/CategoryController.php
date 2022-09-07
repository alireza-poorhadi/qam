<?php

namespace App\Controllers;

use App\Models\Category;
use App\Utils\Sanitize;

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function addCategory()
    {
        global $request;
        $title = $request->title;
        if (Sanitize::notGiven($title)) {
            $_SESSION['categoryMsg'] = 'لطفا دسته‌بندی مورد نظر خود را وارد کنید!';
            $_SESSION['whichDiv'] = 'rectCategory';
            $_SESSION['class'] = 'danger';
            redirect('admin');
            die();
        }
        $title = Sanitize::clean($title);

        $this->categoryModel->create([
            'title' => $title
        ]);

        $_SESSION['categoryMsg'] = 'دسته‌بندی جدید اضافه شد.';
        $_SESSION['whichDiv'] = 'rectCategory';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function editCategory()
    {
        global $request;
        $categoryId = $request->routeParam('category_id');
        if (!is_numeric($categoryId)) {
            die('Not Allowed Operation!');
        }
        $category = $this->categoryModel->find($categoryId);
        $data = [
            'id' => $category->id,
            'title' => $category->title
        ];

        view('admin.editCategory', $data);
    }

    public function saveCategory()
    {
        global $request;
        $categoryId = $request->categoryId;
        $title = $request->title;
        $title = Sanitize::clean($title);
        if (!is_numeric($categoryId)) {
            die('Not Allowed Operation!');
        }
        $this->categoryModel->update([
            'title' => $title
        ], [
            'id' => $categoryId
        ]);
        $_SESSION['categoryMsg'] = 'دسته‌بندی ویرایش شد.';
        $_SESSION['whichDiv'] = 'rectCategory';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }

    public function deleteCategory()
    {
        global $request;
        $categoryId = $request->routeParam('category_id');
        if (!is_numeric($categoryId)) {
            die('Not Allowed Operation!');
        }
        $this->categoryModel->delete([
            'id' => $categoryId
        ]);
        $_SESSION['categoryMsg'] = 'دسته‌بندی حذف شد.';
        $_SESSION['whichDiv'] = 'rectCategory';
        $_SESSION['class'] = 'success';
        redirect('admin');
    }
}
