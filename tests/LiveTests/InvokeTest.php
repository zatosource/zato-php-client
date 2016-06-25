<?php


namespace Zato\UnitTests\LiveTests;


class InvokeTest extends BasicTest
{

    public function testInvokeSuccess()
    {

        $res = $this->client->serviceInvoke(array('name' => 'zone-download.zone-ftp-details'));

        var_dump($res);
    }
}

