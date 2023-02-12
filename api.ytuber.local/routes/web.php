<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route files avatars
// fix
/*
Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});*/

Route::get('/auth', function () {
    return view('auth.login');
});

//Route::post('/authlogin','App\Http\Controllers\Auth\AuthenticatedSessionController@login');
//Route::post('/authlogout','App\Http\Controllers\Auth\AuthenticatedSessionController@logout');

//require __DIR__.'/auth.php';
