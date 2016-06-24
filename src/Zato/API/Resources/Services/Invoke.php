<?php
namespace Zato\API\Resources\Services;

use Zato\API\Resources\ResourceAbstract;


class Invoke extends ResourceAbstract
{
    /**
     * {@inheritdoc}
     */
    protected $resourceName = 'zato.service.invoke';

    public function execute($params = array())
    {
        $response = $this->client->post('/zato/json/zato.service.invoke', $params);
        return base64_decode($response->zato_service_invoke_response->response);
    }
}