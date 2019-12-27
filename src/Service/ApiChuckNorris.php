<?php

namespace App\Service;

/**
 * Class ApiChuckNorris
 * @package App\Service
 */
class ApiChuckNorris
{
    private const EASTER_EGG_KEY = 'chuck norris';

    /** @var ApiRequest $apiRequest */
    private $apiRequest;

    /**
     * ApiChuckNorris constructor.
     * @param ApiRequest $apiRequest
     */
    public function __construct(ApiRequest $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    /**
     * @param string $data
     * @param string $format
     * @return array|null
     */
    public function easterEgg(string $data, string $format)
    {
        $dataToArray = ApiSerializer::getDataFromFormat($format, $data);

        if (strtolower($dataToArray['pseudo']) == self::EASTER_EGG_KEY) {
            $content = $this->apiRequest->request('GET', ['t' => self::EASTER_EGG_KEY]);
            if (is_array($content)) {
                return [
                    'message' => "Chuck Norris ne pas être créer par le site, c'est lui qui a créer le site !",
                    'movie' => [
                        'title' => $content['Title'],
                        'poster' => $content['Poster'],
                    ],
                ];
            }
        }
        return null;
    }
}
