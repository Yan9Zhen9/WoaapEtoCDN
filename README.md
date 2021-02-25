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
```