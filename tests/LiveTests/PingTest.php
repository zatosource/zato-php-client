<?php


namespace Zato\UnitTests\LiveTests;


class PingTest extends BasicTest
{

    public function testPingSuccess()
    {


        #$res = $this->client->post('json/zato.service.get-list', array('cluster_id' => 1, 'name_filter' => '*'));
        $res = $this->client->ping();
        #$res = $this->client->serviceInvoke(array('name' => 'zone-download.zone-ftp-details'));

        var_dump($res);
    }
}
