<?php
/**
 *
 */
use \think\facade\Route;

Route::post('login',"User/login");
Route::get('check',"User/check")->middleware(\app\middleware\JWTAuthMiddleware::class);