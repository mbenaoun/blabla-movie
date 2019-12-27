<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

/**
 * Class ApiRequest
 * @package App\Service
 */
class ApiRequest
{
    /**
     * @param string $method
     * @param array $params
     * @param string $uri
     * @return array|null
     */
    public function request(string $method, array $params, string $uri = 'http://www.omdbapi.com/?apikey=198fab5e')
    {
        if (is_array($params) && count($params) > 0) {
            $stringParam = '';
            foreach ($params as $key => $value) {
                $stringParam .= '&' . $key . '=' . $value;
            }
            $uri .= $stringParam;
        }
        $client = HttpClient::create();
        $content = null;
        try {
            $response = $client->request($method, $uri);
            if ($response->getStatusCode() == Response::HTTP_OK) {
                $content = $response->toArray();
            }
        } catch (ExceptionInterface $exception) {
            $content = null;
        }

        return $content;
    }
}
