<?php
use app\base\ExceptionHandle;
use app\base\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
