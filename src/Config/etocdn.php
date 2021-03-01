<?php

// 以下内容加入config/filesystems的disks数组中
return [
    'etocdn' => [
        'driver'        => 'etocdn',
        'accessKey'     => env('ETOCDN_ACCESS_KEY'),
        'secretKey'     => env('ETOCDN_SECRET_KEY'),
        'accessOrg'     => env('ETOCDN_ACCESS_ORG'),
        'accessBrand'   => env('ETOCDN_ACCESS_BRAND'),
        'accessIdcInfo' => env('ETOCDN_ACCESS_IDCINFO'),
        'accessOs'      => env('ETOCDN_ACCESS_OS'),
        'endpoint'      => '',
        'cdnDomain'     => '',
        'ssl'           => true,
        'isCName'       => false,
        'debug'         => false,
    ],
];
