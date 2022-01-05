<?php
/**
 *
 */
use \think\facade\Route;

Route::get('login',"User/login");
Route::get('check',"User/check")->middleware(\app\middleware\JWTAuthMiddleware::class);