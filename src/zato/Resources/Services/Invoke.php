<?php
namespace zato\Resources\Services;

use zato\Resources\ResourceAbstract;


class Invoke extends ResourceAbstract
{
    /**
     * {@inheritdoc}
     */
    protected $resourceName = 'zato.service.invoke';

    public function execute($params = array())
    {

        if (!isset($params['data_format'])) {
            $params['data_format'] = 'json';
        }
        if (isset($params['payload'])) {
            $params['payload'] = base64_encode($params['payload']);
        }

        $endpoint = (isset($params['endpoint'])) ? $params['endpoint'] : '/zato/json/zato.service.invoke';
        
        $response = $this->client->post($endpoint, $params);

        return json_decode(base64_decode($response->zato_service_invoke_response->response));
    }

    public static function ex($params = array())
    {

    }
}