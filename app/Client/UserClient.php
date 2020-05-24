<?php
/**
 * author: NanQi
 * datetime: 2020/5/23 19:17
 */

namespace App\Client;


class UserClient extends BaseClient
{

    public function test(string $url)
    {
        $res = $this->client->get($url);
        $body = $res->getBody();
        return $body->getContents();
    }

    public function testFallback()
    {
        return "fallback2";
    }
}