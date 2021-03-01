<?php

namespace Yan9\Etocdn;

use GuzzleHttp\Client;
use Yan9\Etocdn\Expections\OssException;

class OssClient
{
    const DEV_CDN_DOMIAN = "https://sit-oss-api.etocdn.cn";
    const PROD_CDN_DOMIAN = "https://oss-api.etocdn.cn";

    const ETOCDN_STRING_URL = "/file/uploadByFileStr";
    const ETOCDN_REMOTE_URL = "/file/uploadByUrl";
    const ETOCDN_REMOTE_BATCH_URL = "/file/uploadListByUrl";

    private $accessOrg;
    private $accessBrand;
    private $accessIdcInfo;
    private $accessOs;
    private $accessKey;
    private $secretKey;


    public function __construct($accessOrg, $accessBrand, $accessIdcInfo, $accessOs, $accessKey = NULL, $secretKey = NULL)
    {
        $accessOrg = trim($accessOrg);
        $accessBrand = trim($accessBrand);
        $accessIdcInfo = trim($accessIdcInfo);

        if (empty($accessOrg)) {
            throw new OssException("access org id is empty");
        }
        if (empty($accessBrand)) {
            throw new OssException("access brand secret is empty");
        }
        if (empty($accessIdcInfo)) {
            throw new OssException("access idcinfo is empty");
        }
        if (empty($accessOs)) {
            throw new OssException("access os is empty");
        }

        $this->accessOrg = $accessOrg;
        $this->accessBrand = $accessBrand;
        $this->accessIdcInfo = $accessIdcInfo;
        $this->accessOs = $accessOs;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    private function getHeader()
    {
        $DateTime = date('Y-m-d H:i:s');
        return [
            'DateTime' => $DateTime,
            'Sign' => self::getSign($DateTime),
            'AccessKey' => $this->accessKey,
            'Org' => $this->accessOrg,
            'Brands' => $this->accessBrand,
            'Idcinfo' => $this->accessIdcInfo,
            'Os' => $this->accessOs,
        ];
    }

    private function getDomian()
    {
        switch (config('filesystems.disks.etocdn.cdnEnv')) {
            case 'PROD':
                $cdn_domian = self::PROD_CDN_DOMIAN;
                break;
            default:
                $cdn_domian = self::DEV_CDN_DOMIAN;
                break;
        }
        return $cdn_domian;
    }

    public function put($object, $content, $options = NULL)
    {
        $this->precheckCommon($object, $content);
        $post_fields = [
            'fileName' => $object,
            'fileStr' => base64_encode($content),
            'keepFileNameStatus' => $options['save_by_file_name'] ?? false,
        ];
        return $this->post(self::getDomian() . self::ETOCDN_STRING_URL, self::getHeader(), $post_fields);
    }

    public function writeStream($object, $content, $options = NULL)
    {
        $this->precheckCommon($object, $content, false);
        $post_fields = [
            'fileName' => $object,
            'fileUrl' => $content,
            'keepFileNameStatus' => $options['save_by_file_name'] ?? false,
        ];
        return $this->post(self::getDomian() . self::ETOCDN_REMOTE_URL, self::getHeader(), $post_fields);
    }

    public function writeBatch($object, $content, $options = NULL)
    {
        $this->precheckCommon($object, $content, false);
        $post_fields = [
            'fileUrlList' => unserialize($content),
            'keepFileNameStatus' => false,
        ];
        return $this->post(self::getDomian() . self::ETOCDN_REMOTE_BATCH_URL, self::getHeader(), $post_fields);
    }

    private function post($url, $headers, $post_fields)
    {
        $httpClient = new Client([
            'timeout' => 10,
            'verify' => false,
        ]);

        $res = $httpClient->request('post', $url, ['headers' => $headers, 'json' => $post_fields]);

        if ($res instanceof \Psr\Http\Message\ResponseInterface) {
            $body = $res->getBody();
        }

        if (empty($body)) {
            return false;
        }
        $status = $res->getStatusCode();
        $contents = $body->getContents();

        $result = json_decode($contents, true);

        if (intval($status) == 200 && JSON_ERROR_NONE === json_last_error()) {
            return $result;
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

    private function getSign($dataTime)
    {
        $str = $this->accessKey . $this->secretKey . $dataTime . $this->accessOrg . $this->accessBrand . $this->accessIdcInfo . $this->accessOs;
        return hash_hmac('md5', $str, $this->secretKey);
    }

}
