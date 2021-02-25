# yan9/etocdn

Laravel package

## Require

Installation:

`composer require yan9/etocdn`

In `config/app.php` add this line to providers array:

`Yan9\Etocdn\EtocdnOssServiceProvider::class,`

## Configuration
```angular2html
'disks' => [
    ...
    'etocdn' => [
        'driver'        => 'etocdn',
        'accessKey'     => env('ETOCDN_ACCESS_KEY'),
        'secretKey'     => env('ETOCDN_SECRET_KEY'),
        'accessOrg'     => env('ETOCDN_ACCESS_ORG'),
        'accessBrand'   => env('ETOCDN_ACCESS_BRAND'),
        'accessIdcInfo' => env('ETOCDN_ACCESS_IDCINFO'),
        'cdnEnv'        => env('ETOCDN_CDNENV'),   // PROD 为正式，其他全部为测试
        'endpoint'      => '',
        'cdnDomain'     => '',
        'ssl'           => true,
        'isCName'       => false,
        'debug'         => false,
    ],
    ...
]
```

## Usage
```angular2html
    $image = $request->file('file');

    if (!$image->isValid()) {
        return '上传失败';
    }
    
    $ext = $image->getClientOriginalExtension();
    $realPath = $image->getRealPath();
    $filename = str_random() . '.' . $ext;
    $filename = trim($filename, '/');
    
    $storage = \Illuminate\Support\Facades\Storage::disk('etocdn');
    Log::debug('OSS config:', [$filename]);
    
    // 上传文件
    $result = $storage->put($filename, $realPath);
    if (!$result) return false;
    var_dump($storage->url($filename));

    // 上传单个url
    $filename = 'aaaa.jpg';
    $realPath = 'https://cdn.zxlycr.top/37/2021-02-24/37_20210224191240114.jpg';
    
    $result = $storage->put($filename, $realPath);
    if (!$result) return false;
    var_dump($storage->url($filename));
    
    // 批量上传url
    // 如果需要自定义文件名称，请用单个url上传，批量上传不会采用自定义文件名称
    // BatchUpload 为批量上传标识
    // 上传会保留原始路径，如果以“/”结尾，会将“/”去掉
    $realPath = [
    'https://cdn.zxlycr.top/37/2021-02-24/37_20210224191240114.jpg',
    'https://cdn.zxlycr.top/37/2021-02-25/aaaa.jpg'
    ];
    
    $result = $storage->put('BatchUpload', serialize($realPath));
    if (!$result) return false;
    var_dump($storage->url($realPath));
```