<?php

namespace Zato\API\Resources;

use Zato\API\HttpClient;

abstract class ResourceAbstract
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }
}