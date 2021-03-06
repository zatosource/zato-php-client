<?php

namespace Zato\UnitTests;

require dirname(__DIR__).'/../vendor/autoload.php';

use zato\ZatoClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

class PingTest extends PHPUnit_Framework_TestCase
{
    
    public function testPingSuccess()
    {
        // Create a mock response
        $body = <<<JSON
{
  "zato_env": {
    "details": "",
    "result": "ZATO_OK",
    "cid": "K07E5BKXP7K7FQP81JQHJRXG30AW"
  },
  "zato_ping_response": {
    "pong": "zato"
  }
}
JSON;

        $mock = new MockHandler([
            new Response(200,
                [
                    'Server' => 'Zato',
                    'X-Zato-CID' => 'K07E5BKXP7K7FQP81JQHJRXG30AW'
                ], $body)
        ]);

        $handler = HandlerStack::create($mock);

        $client = new ZatoClient(array(), ['handler' => $handler]);
        
        $res = $client->ping();

        $this->assertObjectHasAttribute('zato_env', $res);
        $this->assertObjectHasAttribute('pong', $res->zato_ping_response);
    }
}
