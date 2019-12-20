<?php

namespace App\Aspect;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Framework\Logger\StdoutLogger;
use Hyperf\HttpServer\Contract\RequestInterface;
use PDepend\Util\Log;

/**
 * @Aspect
 */
class RequestLogAspect extends AbstractAspect
{
    /**
     * @Inject
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    // 要切入的类，可以多个，亦可通过 :: 标识到具体的某个方法，通过 * 可以模糊匹配
    public $classes = [
        'App\Controller*',
    ];

    // 要切入的注解，具体切入的还是使用了这些注解的类，仅可切入类注解和类方法注解
    public $annotations = [
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->logger->info('Request Start ' . json_encode($this->request->all()));
        // 切面切入后，执行对应的方法会由此来负责
        // $proceedingJoinPoint 为连接点，通过该类的 process() 方法调用原方法并获得结果
        // 在调用前进行某些处理
        $result = $proceedingJoinPoint->process();
        // 在调用后进行某些处理
        return $result;
    }
}