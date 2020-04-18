<?php
/**
 * author: NanQi
 * datetime: 2019/12/8 17:49
 */
declare(strict_types=1);

namespace App\Service;

use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\RedisFactory;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class JwtService extends BaseService {

    protected $header = 'authorization';
    protected $prefix = 'bearer';

    /**
     * @Value("jwt.ttl")
     */
    private $token_ttl;

    /**
     * @Value("jwt.secret")
     */
    private $sign_key;

    /**
     * @Inject
     * @var RequestInterface $request
     */
    protected $request;

    /**
     * @Inject
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function getToken($uid, $issuedAt = null, $ttl = null)
    {
        var_dump("uid=".$uid);
        $issuedAt = $issuedAt ?? time();
        $ttl = $ttl ?? $this->token_ttl;

        $signer = new Sha256();

        $token = (new Builder())
            ->issuedAt($issuedAt)
            ->expiresAt($issuedAt + $ttl)
            ->withClaim('uid', $uid)
            ->getToken($signer, new Key($this->sign_key));
        return $token.'';
    }

    private function parse()
    {
        $header = $this->request->getHeader($this->header);
        if ($header && count($header) > 0 && preg_match('/'.$this->prefix.'\s*(\S+)\b/i', $header[0], $matches)) {
            return $matches[1];
        }
    }

    public function checkToken()
    {
        $headerToken = $this->parse();
        if (!$headerToken) {
            return false;
        }

        $curToken = (new Parser())->parse((string)$headerToken);
        $signer = new Sha256();

        $flg = $curToken->verify($signer, $this->sign_key);
        if (!$flg) {
            return false;
        }

        $uid = $curToken->getClaim('uid');

        $flg = $curToken->isExpired();
        if ($flg) {
            return false;
        }

        $this->logger->info('check:' . $uid);
        return $uid;
    }
}