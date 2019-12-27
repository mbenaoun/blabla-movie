<?php

namespace App\Tests\unit;

use App\Entity\Movie;
use App\Service\ApiEntity;
use App\Service\ApiMovie;
use App\Service\ApiRequest;
use App\Service\ApiSerializer;
use Codeception\Stub;
use Codeception\Test\Unit;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class ApiMovieTest
 * @package App\Tests\unit
 */
class ApiMovieTest extends Unit
{
    /**
     * @covers \App\Service\ApiMovie::getOrCreateMovie()
     * @dataProvider providerGetOrCreateMovieGetOK
     * @param array $data
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testGetOrCreateMovieGetOK(array $data)
    {
        $movie = Stub::makeEmpty(Movie::class);

        $apiEntity = Stub::make(ApiEntity::class, [
            'findOneBy' => Stub\Expected::once($movie),
            'create' => Stub\Expected::never(),
        ], $this);

        $apiRequest = Stub::make(ApiRequest::class, [
            'request' => Stub\Expected::never(''),
        ], $this);

        $apiSerializer = Stub::make(ApiSerializer::class, [
            'dataDenormalize' => Stub\Expected::never($movie),
        ], $this);

        /** @var ApiMovie $apiMovie */
        $apiMovie = Stub::make(ApiMovie::class, [
            'apiRequest' => $apiRequest,
            'apiSerializer' => $apiSerializer,
            'apiEntity' => $apiEntity,
        ], $this);

        $apiMovie->getOrCreateMovie($data);
    }

    /**
     * @return array
     */
    public static function providerGetOrCreateMovieGetOK(): array
    {
        return [
            'case with movieId' => [
                'data' => [
                    'movieId' => 1,
                ],
            ],
            'case with movieTitle' => [
                'data' => [
                    'movieTitle' => 'TITLE',
                ],
            ],
        ];
    }
}
