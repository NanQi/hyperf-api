<?php

declare(strict_types=1);

return [

    # 非对称加密使用字符串,请使用自己加密的字符串
    'secret' => env('JWT_SECRET', 'hyperf'),

    # token过期时间，单位为秒
    'ttl' => env('JWT_TTL', 7200),

];
