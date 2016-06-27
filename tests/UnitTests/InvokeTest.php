<?php

namespace Zato\UnitTests;

require dirname(__DIR__).'/../vendor/autoload.php';

use zato\ZatoClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

class InvokeTest extends \PHPUnit_Framework_TestCase
{

    public function testInvokeSuccess()
    {
        $payload = base64_encode('{"zato_service_has_wsdl_response": {"service_id": 5207, "has_wsdl": true}}');
        // Create a mock response
        $body = <<<JSON
{
  "zato_env": {
    "details": "",
    "result": "ZATO_OK",
    "cid": "K183532160854289289145189943570064602750"
  },
    "zato_service_invoke_response": {
    "response": "$payload"
  }
}
JSON;

        $mock = new MockHandler([
            new Response(200,
                [
                    'Server' => 'Zato',
                    'X-Zato-CID' => 'K183532160854289289145189943570064602750'
                ], $body)
        ]);

        $handler = HandlerStack::create($mock);

        $client = new ZatoClient(array(), ['handler' => $handler]);

        $res = $client->serviceInvoke(array('name' => 'zone-download.zone-ftp-details'));
        $this->assertEquals(json_decode(base64_decode($payload)), $res);
    }
}
