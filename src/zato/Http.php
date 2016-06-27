<?php
namespace zato;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\Request;
use zato\Exceptions\ApiResponseException;
use zato\Exceptions\AuthException;


class Http
{
    public static $curl;

    /**
     * Use the send method to call every endpoint
     *
     * @param HttpClient $client
     * @param string $endPoint E.g. "/ping"
     * @param array $options
     *                             Available options are listed below:
     *                             array $queryParams Array of unencoded key-value pairs, e.g. ["ids" => "1,2,3,4"]
     *                             array $postFields Array of unencoded key-value pairs, e.g. ["filename" => "blah.png"]
     *                             string $method "GET", "POST", etc. Default is GET.
     *                             string $contentType Default is "application/json"
     *
     * @return mixed The response body, parsed from JSON into an object. Also returns bool or null if something went wrong
     * @throws ApiResponseException
     * @throws AuthException
     */
    public static function send(
        ZatoClient $client,
        $endPoint,
        $options = []
    ) {
        $options = array_merge(
            [
                'method' => 'GET',
                'contentType' => 'application/json',
                'postFields' => null,
                'queryParams' => null,
            ],
            $options
        );

        $headers = array_merge(
            [
                'Accept' => 'application/json',
                'Content-Type' => $options['contentType'],
                'User-Agent' => $client->getUserAgent(),
            ],
            $client->getHeaders()
        );

        $request = new Request(
            $options['method'],
            $client->getApiUrl().$endPoint,
            $headers
        );

        $requestOptions = [];

        if (!empty($options['multipart'])) {
            $request = $request->withoutHeader('Content-Type');
            $requestOptions['multipart'] = $options['multipart'];
        } elseif (!empty($options['postFields'])) {
            $request = $request->withBody(\GuzzleHttp\Psr7\stream_for(json_encode($options['postFields'])));
        } elseif (!empty($options['file'])) {
            if (is_file($options['file'])) {
                $fileStream = new LazyOpenStream($options['file'], 'r');
                $request = $request->withBody($fileStream);
            }
        }
        // For debug
        $requestBody = $request->getBody()->getContents();

        if (!empty($options['queryParams'])) {
            foreach ($options['queryParams'] as $queryKey => $queryValue) {
                $uri = $request->getUri();
                $uri = $uri->withQueryValue($uri, $queryKey, $queryValue);
                $request = $request->withUri($uri, true);
            }
        }

        try {
            $response = $client->guzzle->send($request, $requestOptions);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $requestException = RequestException::create($e->getRequest(), $e->getResponse());
            throw new ApiResponseException($requestException);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $requestException = RequestException::create($e->getRequest(), $e->getResponse());

            if ($e->getCode() == 401) {
                throw new AuthException($requestException);

            } else {
                throw new ApiResponseException($requestException);

            }
        }
        catch (RequestException $e) {
            $requestException = RequestException::create($e->getRequest(), $e->getResponse());
            throw new ApiResponseException($requestException);
        } finally {

            $client->setDebug(
                $request->getHeaders(),
                $requestBody,
                isset($response) ? $response->getStatusCode() : null,
                isset($response) ? $response->getHeaders() : null,
                isset($e) ? $e : null
            );

            $request->getBody()->rewind();
        }

        return json_decode($response->getBody()->getContents());
    }
}