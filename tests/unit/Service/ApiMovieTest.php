<?php

namespace App\Tests\unit\Service;

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
 * @package App\Tests\unit\Service
 */
class ApiMovieTest extends Unit
{
    /**
     * @covers \App\Service\ApiMovie::getOrCreateMovie()
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testGetOrCreateMovie()
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

        $apiMovie->getOrCreateMovie([]);
    }
}
