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

/**
 * @OA\Info(
 *     title="API文档",
 *     version="1.0.0",
 *     description="这是公司API接口文档，只在开发和测试环境部署，请勿公开此地址",
 * )
 */
/**
 * @OA\Server(
 *     description="开发",
 *     url="http://mining.test/api/"
 * )
 * @OA\Server(
 *     description="测试",
 *     url="http://47.111.142.19:81/api/"
 * )
 * @OA\ExternalDocumentation(
 *     description="项目开发规范",
 *     url="http://47.111.142.19:82/index.php?m=doc&f=view&docID=5"
 * )
 *
 */


namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Snowflake\IdGeneratorInterface;

class IndexController extends BaseController
{
    /**
     * @Inject
     * @var IdGeneratorInterface
     */
    private $idGenerator;
    /**
     * @OA\Post(
     *     path="/app/share",
     *     tags={"应用"},
     *     summary="分享（弃用）",
     *     description="分享",
     *     @OA\RequestBody(
     *          description="参数",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="type",
     *                  type="string",
     *                  description="1.分享APP（暂时不用）；2.分享邀请码（暂时不用）；3.分享视频；",
     *              ),
     *              @OA\Property(
     *                  property="task_id",
     *                  type="string",
     *                  description="任务ID",
     *              ),
     *          )
     *     ),
     *     @OA\Response(response=200, description="请求成功"),
     * )
     */

    /**
     * @return array
     */
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        

        return [
            'method' => $method,
            'message' => "Hello {$user}." . $this->idGenerator->generate(),
        ];
    }
}
