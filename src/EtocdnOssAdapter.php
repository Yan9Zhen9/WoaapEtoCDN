<?php

namespace Yan9\Etocdn;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Illuminate\Support\Facades\Log;
use Yan9\Etocdn\Expections\OssException;

class EtocdnOssAdapter extends AbstractAdapter
{
    protected $debug;

    protected $client;

    protected $endPoint;
    
    protected $cdnDomain;

    protected $ssl;

    protected $isCname;

    protected $options;

    protected $getData = NULL;


    /**
     * EtocdnOssAdapter constructor.
     *
     * @param OssClient $client
     * @param string    $endPoint
     * @param bool      $ssl
     * @param bool      $isCname
     * @param bool      $debug
     * @param null      $prefix
     * @param array     $options
     */
    public function __construct(
        OssClient $client,
        $endPoint,
        $ssl,
        $isCname = false,
        $debug = false,
        $cdnDomain,
        $prefix = null,
        array $options = []
    )
    {
        $this->debug = $debug;
        $this->client = $client;
        $this->setPathPrefix($prefix);
        $this->endPoint = $endPoint;
        $this->ssl = $ssl;
        $this->isCname = $isCname;
        $this->cdnDomain = $cdnDomain;
        $this->options = $options;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function write($path, $contents, Config $config)
    {
        $object = $this->applyPathPrefix($path);

        try {
            $result = $this->client->put($object, $contents);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }
        $this->getData = $result['data'];
        return $result;
    }

    protected function logErr($fun, $e){
        if( $this->debug ){
            Log::error($fun . ": FAILED");
            Log::error($e->getMessage());
        }
    }

    public function getUrl($path)
    {
        if (is_array($this->getData)) {
            if (1 == count(array_values($this->getData))) {
                return $this->getData[0]['ossUrl'];
            } else {
                return array_column($this->getData,'ossUrl');
            }
        } else {
            return false;
        }
    }

    public function update($path, $contents, Config $config)
    {
        // TODO: Implement update() method.
    }

    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }

    public function rename($path, $newpath)
    {
        // TODO: Implement rename() method.
    }

    public function copy($path, $newpath)
    {
        // TODO: Implement copy() method.
    }

    public function delete($path)
    {
        // TODO: Implement delete() method.
    }

    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }

    public function has($path)
    {
        // TODO: Implement has() method.
    }

    public function read($path)
    {
        // TODO: Implement read() method.
    }

    public function readStream($path)
    {
        // TODO: Implement readStream() method.
    }

    public function listContents($directory = '', $recursive = false)
    {
        // TODO: Implement listContents() method.
    }

    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }

    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }

    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }

    public function writeStream($path, $resource, Config $config)
    {
        $object = $this->applyPathPrefix($path);

        try {
            $result = $this->client->writeStream($object, $resource);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }
        $this->getData = $result['data'];
        return $result;
    }

}
