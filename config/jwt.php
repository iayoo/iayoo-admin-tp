<?php
/**
 *
 */
return [
    // 默认语言
    'private_key'    => env('jwt.private_key', ''),

    'jti'   => env('jwt.jti', 'test'),

    'expire_time'   => env('jwt.expire_time', 1),
];