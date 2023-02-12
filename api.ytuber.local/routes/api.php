<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskTypeController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Broadcast::routes(['middleware' => ['auth:sanctum']]);


/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('/test', function (Request $request) {
    return "ok";
});

    //Регистрация
    Route::post('/registration','App\Http\Controllers\UserController@register');
    Route::get('/user-create','App\Http\Controllers\ServiceWorkController@createUsers');
    Route::get('/activate-account','App\Http\Controllers\UserController@activateUser');
    Route::post('/register-captcha-validate', 'App\Http\Controllers\UserController@captchaValidate');
    Route::post('/reset-password', 'App\Http\Controllers\UserController@resetPassword');
    Route::get('/reset-new-password', 'App\Http\Controllers\UserController@resetNewPassword');
    Route::get('/allow-transaction/{id}', 'App\Http\Controllers\TransactionsController@allowTransaction');

    //Авторизация и выход
    Route::post('/authlogin','App\Http\Controllers\Auth\AuthenticatedSessionController@login');
    Route::post('/login','App\Http\Controllers\Auth\AuthenticatedSessionController@login');
    Route::get('/login','App\Http\Controllers\Auth\AuthenticatedSessionController@login')->name('login');
    Route::post('/authlogout','App\Http\Controllers\Auth\AuthenticatedSessionController@logout');
    Route::post('/login-captcha-validate', 'App\Http\Controllers\UserController@captchaValidate');

    Route::get('/yt-images-channels-fix/','App\Http\Controllers\ServiceWorkController@downloadImagesYoutube');
    Route::get('/yt-donwload-channel-img/{id}','App\Http\Controllers\ServiceWorkController@downloadChannelImg');

    //yoomoney платеж
    Route::post('/payment/yoomoney', 'App\Http\Controllers\PaymentController@yoomoney');

//Группа авторизованных запросов

Route::group(['middleware' => ['auth:sanctum']], function () {

    //bot-detection
    Route::get('/check-account', 'App\Http\Controllers\UserController@checkAccount');
    //настройки аккаунта
    Route::post('/change-settings', 'App\Http\Controllers\UserController@changeSettings');

    //user
    Route::get('/user', 'App\Http\Controllers\UserController@getUser');

    //token
    Route::get('/get-api-key', 'App\Http\Controllers\UserController@getApiKey');

    //youtube data
    Route::get('/get-video-info/{id}', 'App\Http\Controllers\YoutubeController@getVideoInfo');
    Route::get('/get-channel-info/{id}', 'App\Http\Controllers\YoutubeController@getChannelInfoFromId');
    Route::get('/get-channel-name-info/{name}', 'App\Http\Controllers\YoutubeController@getChannelInfoFromName');
    Route::get('/get-video-comments/', 'App\Http\Controllers\YoutubeController@getCommentsFromVideoId');
    Route::get('/get-subscribe-id/', 'App\Http\Controllers\YoutubeController@getSubscribeId');
    Route::post('/get-сhannel-url/', 'App\Http\Controllers\YoutubeController@getChannelInfoFromUrl');

    //Lider of site
    Route::get('/get-lider/top','App\Http\Controllers\LiderSiteController@get');


    //tasks
    Route::get('/refund-task/{id}','App\Http\Controllers\RefundController@refundTask');
    Route::post('/task/create', 'App\Http\Controllers\TaskController@create');
    Route::post('/task/update', 'App\Http\Controllers\TaskController@update');
    Route::get('/task/edit/{id}', 'App\Http\Controllers\TaskController@edit');
    Route::get('/task/get-list', 'App\Http\Controllers\TaskController@getList');
    Route::get('/task/delete/{id}', 'App\Http\Controllers\TaskController@delete');
    Route::get('/change-status-task/{id}', 'App\Http\Controllers\TaskController@changeStatusDisabled');
    Route::get('/get-completions/{id}', 'App\Http\Controllers\TaskController@getCompletions');

    //Статусы
    Route::get('/task-status/{id}', 'App\Http\Controllers\WorkController@checkTaskStatus');
    Route::get('/task-check/{id}', 'App\Http\Controllers\WorkController@checkTaskJob');

    //Общее
    Route::get('/task-type/get-list', 'App\Http\Controllers\TaskTypeController@getList');
    Route::get('/task-category/get-list', 'App\Http\Controllers\TaskCategoryController@getList');
    Route::get('/get-refferals', 'App\Http\Controllers\RefferalsController@get');
    Route::get('/get-refferals-latest', 'App\Http\Controllers\RefferalsController@latest5');
    Route::get('/referral-source/get-list', 'App\Http\Controllers\TaskReferralSources@getList');
    Route::get('/generate-api', 'App\Http\Controllers\UserController@generateApi');
    Route::get('/article/{id}', 'App\Http\Controllers\ArticlesController@get');

    //Платежи
    Route::get('/get-methods-payment', 'App\Http\Controllers\PaymentController@getMethodsPayment');
    Route::get('/get-payments', 'App\Http\Controllers\PaymentController@getMyPayments');
    Route::post('/create-payment', 'App\Http\Controllers\PaymentController@createPayment');

    //Переводы
    Route::get('/get-lates-transactions', 'App\Http\Controllers\TransactionsController@get');
    Route::post('/transaction-send', 'App\Http\Controllers\TransactionsController@make');

    //work
    Route::get('/work/get-list', 'App\Http\Controllers\WorkController@getList');
    Route::get('/user_balance', 'App\Http\Controllers\UserController@user_balance');
    Route::get('/task-comment/get-free/{id}', 'App\Http\Controllers\CommentsTasksController@getFree');

    //accounts
    Route::get('/account/get-list', 'App\Http\Controllers\AccountsController@getList');
    Route::post('/account/get-moderate', 'App\Http\Controllers\AccountsController@getModerate');
    Route::get('/account/get-moderate-google', 'App\Http\Controllers\AccountsController@getModerateGoogle');
    Route::get('/account/complete-moderate/{id}', 'App\Http\Controllers\AccountsController@completeModerate');
    Route::post('/account/delete', 'App\Http\Controllers\AccountsController@delete');
    Route::post('/account/activate', 'App\Http\Controllers\AccountsController@activate');
    Route::post('/account/get-moderate-again', 'App\Http\Controllers\AccountsController@getModerateAgain');

    //Другое
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@getData');
    Route::get('/complete-stat', 'App\Http\Controllers\DashboardController@getCompeted');
    Route::post('/captcha-validate', 'App\Http\Controllers\AccountsController@captchaValidate');
    Route::get('/get-news', 'App\Http\Controllers\ArticlesController@getNews');

    //Notifications
    Route::get('/latest-completed','App\Http\Controllers\NotificationsController@getCompleted');
    Route::get('/today-completed','App\Http\Controllers\NotificationsController@getCompletedToday');

    //Dashboard bonus
    Route::get('/get-daily-bonus','App\Http\Controllers\DashboardController@getDailyBonus');

    //Chat
    Route::post('/message-chat/','App\Http\Controllers\ChatController@chatMessage');
    Route::get('/message-delete/{id}','App\Http\Controllers\ChatController@deleteMessage');
    Route::get('/get-messages-main-chat','App\Http\Controllers\ChatController@getMessagesMainChat');
    Route::get('/join-chat-main','App\Http\Controllers\ChatController@chatRoomEnterMain');
    Route::get('/user-chat-ban/{id}','App\Http\Controllers\ChatController@banUserChat');

    //video watch client
    Route::get('get-watch-video','App\Http\Controllers\ClientWatchController@getWatchVideo');
    Route::post('get-watch-complete','App\Http\Controllers\YoutubeViewClientController@complete');

    //Support mail dashboard
    Route::post('send-mail-support','App\Http\Controllers\EmailController@supportMail');

    //service work
    Route::get('get-channels-for-images','App\Http\Controllers\ServiceWorkController@getChannelsForImages');

    //dzen
    Route::get('getLikesCountNow','App\Http\Controllers\DzenController@getLikesCountNow');



});

