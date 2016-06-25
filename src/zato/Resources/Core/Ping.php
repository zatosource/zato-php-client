<?php
namespace zato\Resources\Core;
use zato\Resources\ResourceAbstract;


class Ping extends ResourceAbstract
{
    /**
     * {@inheritdoc}
     */
    protected $resourceName = 'zato.ping';

    public function execute()
    {
        return $this->client->post('ping', array());
    }
}