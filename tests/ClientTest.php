<?php

namespace Zato\API\UnitTests;
require dirname(__DIR__) . '/vendor/autoload.php';

use Zato\API\HttpClient;

use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Zato\API\HttpClient 
     */
    protected $client;

    public function setUp()
    {
        $config = array(
            'user' => 'pubapi',
            'pass' => '123',
            'hostname' => '207.20.245.148',
            'port' => 11223);

        $this->client = new HttpClient($config);
        parent::setUp();
    }

    /**
     * This checks the last request sent
     *
     * @param $options
     */
    public function testIvan()
    {
        #$res = $this->client->post('json/zato.service.get-list', array('cluster_id' => 1, 'name_filter' => '*'));
        #$res = $this->client->ping();
        $res = $this->client->serviceInvoke(array('name' => 'zone-download.zone-ftp-details', 'data_format' => 'json'));
        var_dump($res);
    }
}
