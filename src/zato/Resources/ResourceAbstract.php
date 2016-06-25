<?php

namespace zato\Resources;

use zato\ZatoClient;

abstract class ResourceAbstract
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param HttpClient $client
     */
    public function __construct(ZatoClient $client)
    {
        $this->client = $client;
    }
}