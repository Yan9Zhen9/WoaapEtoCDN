# yan9/etocdn

Laravel package

- 安装后执行命令 `php artisan vendor:publish --provider="Yan9\Etocdn\EtocdnServiceProvider"`

- 配置 config/etocdn.php

- 修改配置文件后要使用命令 `php artisan config:clear` 清除一下！

### 使用示例
```angular2html
    // 精确到秒的时间戳
    $datetime = date('Y-m-d H:i:s');
    // 公钥
    $accessKey = 'accessKey';
    // 私钥
    $secretKey = 'secretKey';
    // 机构
    $org = 191;
    // 品牌
    $brands = 111;
    // 机房
    $idcinfo = 1;

    // 实例化基础类
    $this->ossClient = new OssClient($org, $brands, $idcinfo, $datetime, $accessKey, $secretKey);
    
    // 第一个参数为系统识别的场景名称，尽量填写适合每个场景文件的命名，不会作为文件名称返回
    // 第二个参数为文件路径
    return $this->ossClient->put('test.jpg', '/Users/Desktop/123.jpg');
```

### 返回示例
```angular2html
{
    "code": 0,
    "message": "成功",
    "data": [
        {
            "id": 197,
            "fileName": "37_20210219174552748.jpg",
            "ossUrl": "http://cdn.zxlycr.top/37/2021-02-19/37_20210219174552748.jpg",
            "period": "20210219"
        },
        {
            "id": 198,
            "fileName": "37_20210219174552748_50*50.jpg",
            "ossUrl": "http://cdn.zxlycr.top/37/2021-02-19/37_20210219174552748_50*50.jpg",
            "period": "20210219"
        },
        {
            "id": 199,
            "fileName": "37_20210219174552748_300*300.jpg",
            "ossUrl": "http://cdn.zxlycr.top/37/2021-02-19/37_20210219174552748_300*300.jpg",
            "period": "20210219"
        },
        {
            "id": 200,
            "fileName": "37_20210219174552748_800*800.jpg",
            "ossUrl": "http://cdn.zxlycr.top/37/2021-02-19/37_20210219174552748_800*800.jpg",
            "period": "20210219"
        }
    ]
}
```