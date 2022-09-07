<?php

use App\Core\Routing\Route;

Route::get('/', 'HomeController@index');

Route::get('/loginPage', 'LoginController@loginPage');
Route::get('/regPage', 'LoginController@regPage');
Route::post('/register' , 'LoginController@register');
Route::post('/login', 'LoginController@login');

Route::post('/admin/setting', 'AdminController@setting', ['IsAdmin']);
Route::get('/admin/loginPage', 'AdminController@loginPage');
Route::post('/admin/login', 'AdminController@login');
Route::get('/admin', 'AdminController@index', ['IsAdmin']);
Route::post('/admin/addAdmin', 'AdminController@addAdmin', ['IsAdmin']);
Route::get('/admin/delete/{admin_id}', 'AdminController@delete' , ['IsAdmin']);

Route::post('/admin/addTeacher', 'AdminController@addTeacher', ['IsAdmin']);
Route::get('/admin/deleteTeacher/{teacher_id}', 'AdminController@deleteTeacher', ['IsAdmin']);

Route::post('/admin/addCategory', 'CategoryController@addCategory', ['IsAdmin']);
Route::get('/admin/editCategory/{category_id}' , 'CategoryController@editCategory', ['IsAdmin']);
Route::post('/admin/saveCategory' , 'CategoryController@saveCategory', ['IsAdmin']);
Route::get('/admin/deleteCategory/{category_id}' , 'CategoryController@deleteCategory', ['IsAdmin']);

Route::post('/admin/addLevel' , 'LevelController@addLevel', ['IsAdmin']);
Route::get('/admin/editLevel/{level_id}', 'LevelController@editLevel', ['IsAdmin']);
Route::post('/admin/saveLevel', 'LevelController@saveLevel', ['IsAdmin']);
Route::get('/admin/deleteLevel/{level_id}', 'LevelController@deleteLevel', ['IsAdmin']);

Route::get('/mkqu', 'HomeController@mkqu');
Route::post('/saveQu', 'HomeController@saveQu');
Route::get('/unverify', 'HomeController@unverify', ['IsAdmin']);
Route::get('/verify', 'HomeController@verify', ['IsAdmin']);
Route::get('/remove', 'HomeController@remove', ['IsAdmin']);

Route::post('/answer', 'HomeController@answer');
Route::get('/removeAnswer/{answer_id}', 'HomeController@removeAnswer', ['IsAdmin']);

Route::get('/users', 'UsersController@index');
Route::get('/profile/{user_id}', 'UsersController@profile', ['IsRightUser']);
Route::post('/saveProfile', 'UsersController@saveProfile');
Route::get('/removeUser/{user_id}', 'UsersController@removeUser', ['IsAdmin']);
Route::get('/showUserQ/{user_id}', 'UsersController@showUserQ');
Route::get('/showUserA/{user_id}', 'UsersController@showUserA');

Route::get('/starred', 'HomeController@starred');
Route::get('/showStarredQ/{user_id}', 'HomeController@showStarredQ', ['IsRightUser']);

Route::get('/report', 'HomeController@report');
Route::get('/showReports', 'HomeController@showReports', ['IsAdmin']);
Route::get('/removeReport', 'HomeController@removeReport', ['IsAdmin']);