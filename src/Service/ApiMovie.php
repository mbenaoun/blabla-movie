<?php

namespace App\Service;

use App\Entity\Movie;
use App\Exception\EntityNotFoundException;
use App\Exception\MovieException;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ApiMovie
{
    /** @var ApiEntity $apiEntity */
    private $apiEntity;

    /** @var ApiRequest $apiRequest */
    private $apiRequest;

    /** @var ApiSerializer $apiSerializer */
    private $apiSerializer;

    public function __construct(ApiRequest $apiRequest, ApiEntity $apiEntity, ApiSerializer $apiSerializer)
    {
        $this->apiRequest = $apiRequest;
        $this->apiEntity = $apiEntity;
        $this->apiSerializer = $apiSerializer;
    }

    /**
     * @param array $data
     * @return Movie
     * @throws ExceptionInterface
     * @throws MovieException
     */
    public function getOrCreateMovie(array $data): Movie
    {
        try {
            $movieIdKey = 'movieId';
            if (key_exists($movieIdKey, $data) && !empty($data[$movieIdKey])) {
                $param = ['id' => $data[$movieIdKey]];
            } else {
                $param = ['title' => $data['movieTitle']];
            }
            /** @var Movie $movie */
            $movie = $this->apiEntity->findOneBy(Movie::class, $param);
        } catch (EntityNotFoundException $entityNotFoundException) {
            $content = $this->apiRequest->request('GET', ['t' => $data['movieTitle']]);
            if (is_array($content)) {
                try {
                    /** @var Movie $movie */
                    $movie = $this->apiSerializer->dataDenormalize(
                        ['title' => $content['Title'], 'poster' => $content['Poster']],
                        Movie::class
                    );
                    $this->apiEntity->create($movie);
                } catch (Exception $exception) {
                    throw new MovieException('Impossible to create a new Movie !');
                }
            }
            throw new MovieException('Impossible to find the Movie !');
        }
        return $movie;
    }
}
