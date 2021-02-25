<?php

namespace Yan9\Etocdn;

use Yan9\Etocdn\Plugins\PutFile;
use Yan9\Etocdn\Plugins\PutRemoteFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class EtocdnOssServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('etocdn', function($app, $config)
        {
            $accessKey  = $config['accessKey'];
            $secretKey  = $config['secretKey'];
            $accessOrg  = $config['accessOrg'];
            $accessBrand = $config['accessBrand'];
            $accessIdcInfo = $config['accessIdcInfo'];

            $cdnDomain = empty($config['cdnDomain']) ? '' : $config['cdnDomain'];
            $ssl       = empty($config['ssl']) ? false : $config['ssl']; 
            $isCname   = empty($config['isCName']) ? false : $config['isCName'];
            $debug     = empty($config['debug']) ? false : $config['debug'];
            $options = [
                'AccessKey' => $accessKey,
                'Org' => $accessOrg,
                'Brands' => $accessBrand,
                'Idcinfo' => $accessIdcInfo,
            ];
            $prefix = null;

            $endPoint  = $config['endpoint']; // 默认作为外部节点
            
            if($debug) Log::debug('OSS config:', $config);

            $client  = new OssClient($accessOrg, $accessBrand, $accessIdcInfo, $accessKey, $secretKey);
            $adapter = new EtocdnOssAdapter($client, $endPoint, $ssl, $isCname, $debug, $cdnDomain, $prefix, $options);

            //Log::debug($client);
            $filesystem =  new Filesystem($adapter);
            
            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());

            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

}
