<?php

use App\Utils\Url;

function siteURL($route = '')
{
    return $_ENV['BASE_URL'] . $route;
}

function assetURL($route = '')
{
    return siteURL('/assets/' . $route);
}

function redirect($route = '')
{
    header('location:'. siteURL($route));
}

function view($path, $data = []) # errors.404
{
    extract($data);
    $path = str_replace('.', '/', $path); # errors/404
    include_once BASEPATH . '/views/' . $path . '.php';
}

function niceDump($var)
{
    echo '<pre>';
    echo '<div style="margin: 20px auto; border: 1px solid maroon; border-left: 7px solid maroon; border-radius: 5px; padding: 10px; width: 95%;">';
    var_dump($var);
    echo '</div>';
    echo '</pre>';
}

function dd($var)
{
    niceDump($var);
    die();
}

function queryBuilder($newQuery)
{
    $urlParts = parse_url(Url::currentWhole());
    $uri = '';
    if (array_key_exists('query', $urlParts)) {
        $uri = $urlParts['query'];
    }
    $uri .= '&' . $newQuery;
    parse_str($uri, $get_array);

    return '?' . http_build_query($get_array);
}

function xssClean($str) {
    return htmlspecialchars($str);
}

function find($id, $model)
{
    $model = 'App\Models\\' . $model;
    $model = new $model();

    return $model->find($id);
}

function findAnswers($id)
{
    $answerModel = new App\Models\Answer();
    $answers = $answerModel->get(['id', 'teacher_id', 'content', 'created_at'], [
        'question_id' => $id
    ]);
    return $answers;
}

function findQuestion($id)
{
    $questionModel = new \App\Models\Question();
    $question = $questionModel->get(['id', 'teacher_id', 'user_id', 'level_id', 'category_id', 'title', 'content', 'created_at'], ['id' => $id]);
    return $question[0];
}

function findDefaultUserId()
{
    $teacherModel = new App\Models\Teacher();
    $defaultUser = $teacherModel->get(['id'], ['username' => 'defaultuser']);
    return $defaultUser[0]['id'];
}

function isAdmin(){
    if (isset($_SESSION['admin'])) {
        return true;
    }
    return false;
}

function isTeacher(){
    if (isset($_SESSION['user']) and $_SESSION['user']['role'] == 'teacher') {
        return true;
    }
    return false;
}

function isUser(){
    if (isset($_SESSION['user']) and $_SESSION['user']['role'] == 'user') {
        return true;
    }
    return false;
}

function isGuest()
{
    if (!isset($_SESSION['user']) and !isset($_SESSION['admin'])) {
        return true;
    }
    return false;
}

function isAjaxRequest()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
}

function getCountQuestions($id)
{
    $questionModel = new \App\Models\Question();
    return $questionModel->count(['AND' => ['user_id' => $id, 'verified' => 1]]);
}

function getCountAnswers($id)
{
    $answerModel = new \App\Models\Answer();
    return $answerModel->count(['teacher_id' => $id]);
}

function canFindStarredQuestion($userId, $questionId)
{
    $starredModel = new App\Models\Starred();
    $starredQuestion = $starredModel->get(['id'], ['AND' => ['user_id' => $userId, 'question_id' => $questionId]]);
    if(sizeof($starredQuestion) == 0) {
        return false;
    }
    return true;
}

function canFindReportedQuestion($userId, $questionId)
{
    $reportModel = new App\Models\Report();
    $reportedQuestion = $reportModel->get(['id'], ['AND' => ['user_id' => $userId, 'question_id' => $questionId]]);
    if(sizeof($reportedQuestion) == 0) {
        return false;
    }
    return true;
}

function reportedQuestionsCount()
{
    $reportModel = new App\Models\Report();
    return $reportModel->count(['id[>]' => 0]);
}