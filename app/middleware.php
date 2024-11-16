<?php
// 全局中间件定义文件
use app\middleware\Authentication;
use app\middleware\CrossDomain;

return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class
    //登录凭据校验(应当在路由中间件部署)
//    Authentication::class,
    //统一设置跨域响应头
    CrossDomain::class,
];
