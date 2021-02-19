<?php

namespace Yan9\Etocdn;

use GuzzleHttp\Client;
use Yan9\Etocdn\Expections\OssException;

class OssClient
{
    const ETOCDN_URL = "http://10.100.20.241:21400/file/uploadByFile";

    private $accessOrg;
    private $accessBrand;
    private $accessIdcInfo;
    private $dataTime;
    private $accessKey;
    private $secretKey;


    public function __construct($accessOrg, $accessBrand, $accessIdcInfo, $dataTime, $accessKey = NULL, $secretKey = NULL)
    {
        $accessOrg = trim($accessOrg);
        $accessBrand = trim($accessBrand);
        $accessIdcInfo = trim($accessIdcInfo);
        $dataTime = trim($dataTime);

        if (empty($accessOrg)) {
            throw new OssException("access org id is empty");
        }
        if (empty($accessBrand)) {
            throw new OssException("access brand secret is empty");
        }
        if (empty($accessIdcInfo)) {
            throw new OssException("access idcinfo is empty");
        }
        if (empty($dataTime)) {
            throw new OssException("access datetime is empty");
        }

        $this->accessOrg = $accessOrg;
        $this->accessBrand = $accessBrand;
        $this->accessIdcInfo = $accessIdcInfo;
        $this->dataTime = $dataTime;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function put($object, $content, $header = NULL)
    {
        $this->precheckCommon($object, $content);

        $header['DateTime'] = $this->dataTime;
        $header['Sign'] = self::getSign();
        $header['AccessKey'] = $this->accessKey;
        $header['Org'] = $this->accessOrg;
        $header['Brands'] = $this->accessBrand;
        $header['Idcinfo'] = $this->accessIdcInfo;

        $multipart = [
            [
                'name' => 'fileName',
                'contents' => $object,
            ],
            [
                'name' => 'file',
                'contents' => fopen($content, 'r')
            ]
        ];

        $httpClient = new Client();
        $response = $httpClient->request('post', self::ETOCDN_URL, ['headers' => $header, 'multipart' => $multipart]);

        if ($response instanceof \Psr\Http\Message\ResponseInterface) {
            $body = $response->getBody();
        }

        if (empty($body)) {
            return false;
        }
        $status = $response->getStatusCode();
        $contents = $body->getContents();

        $resQm = json_decode($contents, true);

        if (intval($status) == 200 && JSON_ERROR_NONE === json_last_error()) {
            return $resQm;
        } else {
            return false;
        }
    }

    private function precheckCommon($object, $content, $isCheckObject = true)
    {
        if ($isCheckObject) {
            $this->throwOssExceptionWithMessageIfEmpty($object, "object name is empty");
        }
        if ($content) {
            $this->throwOssExceptionWithMessageIfEmpty($content, "content is empty");
        }
    }

    public function throwOssExceptionWithMessageIfEmpty($name, $errMsg)
    {
        if (empty($name)) {
            throw new OssException($errMsg);
        }
    }

    private function getSign()
    {
        $str = $this->accessKey . $this->secretKey . $this->dataTime . $this->accessOrg . $this->accessBrand . $this->accessIdcInfo;
        return hash_hmac('md5', $str, $this->secretKey);
    }

}
