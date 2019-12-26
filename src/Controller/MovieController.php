<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\ApiEntity;
use App\Service\ApiResponse;
use App\Service\ApiSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieController
 * @package App\Controller
 * @Route("/v1/movies", name="movies_")
 */
class MovieController extends AbstractController
{
    /**
     * @param Request $request
     * @param ApiEntity $apiEntity
     * @param ApiSerializer $apiSerializer
     * @param ApiResponse $apiResponse
     * @return Response
     * @Route(
     *     "/users.{_format}",
     *     name="users",
     *     format="json",
     *     requirements={
     *          "_format": "xml|json",
     *     },
     *     methods={"GET"}
     *)
     */
    public function users(
        Request $request,
        ApiEntity $apiEntity,
        ApiSerializer $apiSerializer,
        ApiResponse $apiResponse
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);

        /** @var MovieRepository $movieRepository */
        $movieRepository = $apiEntity->getRepository(Movie::class);

        $movies = $movieRepository->findAllMovieWithUser();

        $jsonObject = $apiSerializer->objectSerialize($format, $movies, ['movies']);

        return $apiResponse->response($jsonObject, Response::HTTP_OK, $mimeType);
    }
}
