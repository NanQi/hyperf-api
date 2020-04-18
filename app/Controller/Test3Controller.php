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
use App\Request\TestRequest;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Rule;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController()
 */
class Test3Controller extends BaseController
{
    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */

    protected $validationFactory;

    /**
     * 验证方式一
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request)
    {
        $validator = $this->validationFactory->make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'required',
                'gender' => [
                    'required',
                    Rule::in([1, 2]),
                ],
            ],
            [
                'phone.required' => '请输入手机号',
                'gender.required' => '请输入性别',
                'gender.in' => '输入性别错误',
            ]
        );

        if ($validator->fails()){
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->response->json([
                'code' => 423,
                'message' => $errorMessage,
            ]);
        }
        return $this->response->json(['data' => $request->all()]);
        // Do something
    }

    /**
     * 验证方式二
     * @param TestRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index2(TestRequest $request)
    {
        return $this->response->json(['meg' => '你好']);
    }


}
