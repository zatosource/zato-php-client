<?php
namespace zato\Exceptions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class ApiResponseException
 *
 * @package zato\API\Exceptions
 */
class ApiResponseException extends \Exception
{
    /**
     * @var array
     */
    protected $errorDetails = [];

    public function __construct(RequestException $e)
    {
        $message = $e->getMessage();
        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $this->errorDetails = $responseBody;
            $message .= ' [details] '.$this->errorDetails;
        } elseif ($e instanceof ServerException) {
            $message .= ' [details] Server error';
        } elseif (!$e->hasResponse()) {
            $request = $e->getRequest();
            // Unsuccessful response, log what we can
            $message .= ' [url] '.$request->getUri();
            $message .= ' [http method] '.$request->getMethod();
            $message .= ' [body] '.$request->getBody()->getContents();
        }
        parent::__construct($message, $e->getCode());
    }

    public function getErrorDetails()
    {
        return $this->errorDetails;
    }
}