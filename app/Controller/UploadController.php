<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use OSS\Core\OssException;
use OSS\Http\RequestCore;
use OSS\Http\ResponseCore;
use Qiniu\Auth;
use OSS\OssClient;

/**
 * @AutoController()
 */
class UploadController extends BaseController
{
    /**
     * @return array
     */
    public function qiniu()
    {
        $accessKey = env('QINIU_ACCESS_KEY');
        $secretKey = env('QINIU_SECRET_KEY');

        $auth = new Auth($accessKey, $secretKey);

        $bucket = env('QINIU_BUCKET');

        // 简单上传凭证
        $expires = env('QINIU_EXPIRES');

        $policy = null;
        $upToken = $auth->uploadToken($bucket, null, $expires, $policy, true);

        return ['up_token' => $upToken];
    }

    /**
     * @return array
     * @throws \OSS\Http\RequestCore_Exception
     */
    public function oss()
    {
        $accessKeyId = "LTAI4FryGduyVpCZcdKQGFPw";
        $accessKeySecret = "3VODZot4fG6LY60HznPHuc80BC2HTF";
// Endpoint以杭州为例，其它Region请按实际情况填写。
        $endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
        $bucket= "jieao-mining";
        $object = "hyperf-test";
        $securityToken = null;

// 设置URL的有效期为3600秒。
        $timeout = 3600;
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false, $securityToken);
            // 生成PutObject的签名URL。
            $signedUrl = $ossClient->signUrl($bucket, $object, $timeout, "PUT");
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return 222;
        }
        print(__FUNCTION__ . ": signedUrl: " . $signedUrl . "\n");
        var_dump($signedUrl);
        $content = "Hello OSS.";
        $request = new RequestCore($signedUrl);
// 生成的URL以PUT方式访问。
        $request->set_method('PUT');
        $request->add_header('Content-Type', '');
        $request->add_header('Content-Length', strlen($content));
        $request->set_body($content);
        $request->send_request();
        $res = new ResponseCore($request->get_response_header(),
            $request->get_response_body(), $request->get_response_code());
        if ($res->isOK()) {
            print(__FUNCTION__ . ": OK" . "\n");
        } else {
            print(__FUNCTION__ . ": FAILED" . "\n");
        };
        return 111;
    }

    /**
     * 服务端通过PHP代码完成签名，并且设置上传回调，然后通过表单直传数据到OSS
     * https://help.aliyun.com/document_detail/91771.html?spm=a2c4g.11186623.2.17.69836e28oNnF3c#concept-nhs-ldt-2fb
     */
    public function oss2()
    {
    }


}
