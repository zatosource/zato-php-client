<?php
namespace Zato\API\Resources\Core;
use Zato\API\Resources\ResourceAbstract;


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