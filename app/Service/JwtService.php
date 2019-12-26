<?php
/**
 * author: NanQi
 * datetime: 2019/12/8 17:49
 */
declare(strict_types=1);

namespace App\Service;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class JwtService extends BaseService {

    protected $header = 'authorization';

    protected $prefix = 'bearer';

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

    public function getToken($uid)
    {
        $signer = new Sha256();

        $time = time();
        $token = (new Builder())
            ->issuedAt($time)
            ->expiresAt($time + 1)
            ->withClaim('uid', $uid)
            ->getToken($signer, new Key('wd2RknfaQEm9FdYATB1h0Azh9nKYMJACyyVf5kSGWGbLCROwvOjk7w09JJ1h1n56'));

        return $token;
    }

    public function parse()
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

        $flg = $curToken->verify($signer, 'wd2RknfaQEm9FdYATB1h0Azh9nKYMJACyyVf5kSGWGbLCROwvOjk7w09JJ1h1n56');
        if (!$flg) {
            return false;
        }

        $uid = $curToken->getClaim('uid');
        $flg = $curToken->isExpired();
        if ($flg) {
            $issued = $curToken->getHeader('issued');
            $issued = $curToken->getHeader('issued');
//            $time = time();
//            $token = (new Builder())
//                ->issuedAt()
//                ->expiresAt($time + 1)
//                ->withClaim('uid', $uid)
//                ->getToken($signer, new Key('wd2RknfaQEm9FdYATB1h0Azh9nKYMJACyyVf5kSGWGbLCROwvOjk7w09JJ1h1n56'));
//
//            return $token;
            $this->logger->info('checkExpired:' . $flg);
        }
        $this->logger->info('check:' . $uid);

        return true;
    }
}