<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
     \think\middleware\SessionInit::class,
    // admin 权限校验中间件
    \app\admin\middleware\AuthMiddleware::class,
    // 行为日志中间件
    \app\admin\middleware\AdminBehaviorLogMiddleware::class,
];
