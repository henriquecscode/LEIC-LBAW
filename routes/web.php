<?php


use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. 
|
*/
// Home
Route::get('/', 'StaticPagesController@home');
Route::get('/home', 'StaticPagesController@home');

// M01
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('forgot_password', 'Auth\RegisterController@showForgot');
Route::post('forgot_password', 'Auth\RegisterController@forgot');
Route::get('reset_password/{token}', 'Auth\RegisterController@showReset')->name('password.reset');;
Route::post('reset_password', 'Auth\RegisterController@reset');

// M02
Route::get('members/{id}', 'MemberController@show')->where('id', '[0-9]+');
Route::get('members/{id}/edit', 'MemberController@edit')->where('id', '[0-9]+');
Route::post('members/{id}/edit', 'MemberController@update')->where('id', '[0-9]+');
Route::post('appeal', 'MemberController@appeal');
Route::get('/self/subscriptions', 'MemberController@showTypeSubscriptions');
Route::get('self/subscriptions/posts', 'MemberController@showPostSubscriptions');
Route::get('self/subscriptions/members','MemberController@showMemberSubscriptions');
Route::get('self/subscriptions/categories', 'MemberController@showCategorySubscriptions');
Route::get('self/notifications', 'MemberController@viewNotifications');
Route::post('self/notifications/read/{notification_id}', 'MemberController@readNotification')->where('id', '[0-9]+');
Route::post('removeAccount/{id}', 'AdminController@removeMember')->where('id', '[0-9]+');


// M03 
Route::post('subscriptions/post/{post_id}', 'SubscriptionPostController@subscribe')->where('post_id', '[0-9]+');
Route::post('unsubscriptions/post/{post_id}', 'SubscriptionPostController@unsubscribe')->where('post_id', '[0-9]+');
Route::post('subscriptions/members/{member_id}', 'SubscriptionMemberController@subscribe' )->where('member_id', '[0-9]+');
Route::post('unsubscriptions/members/{member_id}', 'SubscriptionMemberController@unsubscribe')->where('member_id', '[0-9]+');
Route::post('subscriptions/categories/{category_id}', 'SubscriptionCategoryController@subscribe')->where('category_id', '[0-9]+');
Route::post('unsubscriptions/categories/{category_id}', 'SubscriptionCategoryController@unsubscribe')->where('category_id', '[0-9]+');

// M04
Route::get('question/{id}', 'QuestionController@show')->where('id', '[0-9]+');
Route::get('questions/byTime', 'QuestionController@showByTime');
Route::get('questions/byVotes', 'QuestionController@showByVotes');
Route::get('questions/byNotAnswered', 'QuestionController@showByNotAnswered');
Route::get('category', 'CategoryController@showAll');
Route::get('questions/category/{id}', 'CategoryController@show')->where('id', '[0-9]+');
Route::get('questions/category/{id}/search', 'CategoryController@search')->where('id', '[0-9]+');
Route::get('questions/category/{id}/byTime', 'CategoryController@showByTime')->where('id', '[0-9]+');
Route::get('questions/category/{id}/byVotes', 'CategoryController@showByVotes')->where('id', '[0-9]+');
Route::get('questions/category/{id}/byNotAnswered', 'CategoryController@showByNotAnswered')->where('id', '[0-9]+');
Route::get('search', 'SearchController@search');

// M05
Route::get('create_question', 'QuestionController@showCreationForm');
Route::post('create_question', 'QuestionController@create');
Route::get('question/edit_question/{question_id}', 'QuestionController@showEditForm')->where('question_id', '[0-9]+');
Route::post('question/edit_question/{question_id}', 'QuestionController@edit')->where('question_id', '[0-9]+');
Route::post('question/resolve/{question_id}', 'QuestionController@resolve')->where('question_id', '[0-9]+');
Route::post('question/remove_question/{question_id}', 'QuestionController@remove')->where('question_id', '[0-9]+');

// M06
Route::post('post/{post_id}/vote/{value}', 'MemberController@vote')->where('post_id', '[0-9]+')->where('value', '-1|0|1');
Route::post('comment/create/{parent_id}', 'CommentController@create')->where('parent_id', '[0-9]+');
Route::post('comment/edit/{comment_id}', 'CommentController@edit')->where('comment_id', '[0-9]+');
Route::post('comment/remove/{comment_id}', 'CommentController@remove')->where('comment_id', '[0-9]+');
Route::post('post/{post_id}/report', 'MemberController@report')->where('post_id', '[0-9]+');

// // M07
Route::get('feed', 'MemberController@feed');

// // M08
Route::get('contactos', 'StaticPagesController@contactos');
Route::get('faqs', 'StaticPagesController@faqs');
Route::get('about_us', 'StaticPagesController@about_us');

// // M09
Route::get('reports', 'AdminController@showReports');
Route::get('appeals', 'AdminController@showAppeals');
Route::post('blocks/members/{member_id}', 'AdminController@block')->where('member_id', '[0-9]+');
Route::post('unblocks/members/{member_id}', 'AdminController@unblock')->where('member_id', '[0-9]+');
Route::get('edit_categories', 'AdminController@showCategoriesEditForm');
Route::post('edit_categories', 'AdminController@editCategories');
Route::post('moderators/add_moderator/{member_id}', 'AdminController@addMod')->where('member_id', '[0-9]+');
Route::post('moderators/remove_moderator/{member_id}', 'AdminController@removeMod')->where('member_id', '[0-9]+');
Route::get('admin', 'StaticPagesController@admin');
Route::post('reports/removeReport/{report_id}', 'AdminController@removeReport')->where('report_id', '[0-9]+');