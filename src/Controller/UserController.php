<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Repository\MovieRepository;
use App\Service\ApiEntity;
use App\Service\ApiResponse;
use App\Service\ApiSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Exception;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/v1/users", name="users_")
 */
class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param ApiSerializer $apiSerializer
     * @param ApiEntity $apiEntity
     * @param ApiResponse $apiResponse
     * @return Response
     * @throws ExceptionInterface
     * @Route(
     *     ".{_format}",
     *     name="create",
     *     format="json",
     *     requirements={
     *          "_format": "xml|json",
     *     },
     *     methods={"POST"}
     *)
     */
    public function create(
        Request $request,
        ApiSerializer $apiSerializer,
        ApiEntity $apiEntity,
        ApiResponse $apiResponse
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);

        try {
            $user = $apiSerializer->dataDenormalize(
                $request->getContent(),
                User::class,
                $format
            );
            $apiEntity->create($user);
            $jsonObject = $apiSerializer->objectSerialize($format, $user, ['movies']);
        } catch (Exception $e) {
            return $apiResponse->response(
                'Impossible to create User',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $mimeType
            );
        }

        return $apiResponse->response($jsonObject, Response::HTTP_CREATED, $mimeType);
    }

    /**
     * @param Request $request
     * @param ApiSerializer $apiSerializer
     * @param ApiEntity $apiEntity
     * @param ApiResponse $apiResponse
     * @return Response
     * @Route(
     *     "/{user_id}/movies.{_format}",
     *     name="movies",
     *     format="json",
     *     requirements={
     *          "_format": "xml|json",
     *     },
     *     methods={"GET"}
     *)
     */
    public function movies(
        Request $request,
        ApiSerializer $apiSerializer,
        ApiEntity $apiEntity,
        ApiResponse $apiResponse
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);

        try {
            /** @var User $user */
            $user = $apiEntity->find(User::class, $request->get('user_id'));
        } catch (EntityNotFoundException $e) {
            return $apiResponse->response(
                'No User Found',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $mimeType
            );
        }

        /** @var MovieRepository $movieRepository */
        $movieRepository = $apiEntity->getRepository(Movie::class);
        $movies = $movieRepository->findAllMovieByUser($user);

        $jsonObject = $apiSerializer->objectSerialize($format, $movies, ['users']);

        return $apiResponse->response($jsonObject, Response::HTTP_CREATED, $mimeType);
    }
}
