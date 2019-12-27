<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Repository\MovieRepository;
use App\Service\ApiCache;
use App\Service\ApiChuckNorris;
use App\Service\ApiEntity;
use App\Service\ApiSerializer;
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
class UserController extends ControllerAbstract
{
    /**
     * @param Request $request
     * @param ApiSerializer $apiSerializer
     * @param ApiEntity $apiEntity
     * @param ApiChuckNorris $apiChuckNorris
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
        ApiChuckNorris $apiChuckNorris
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);
        $content = $request->getContent();

        //Check if easter egg activate ;)
        $easterEgg = $apiChuckNorris->easterEgg($content, $format);

        if (!is_null($easterEgg)) {
            return $this->apiResponse->response($easterEgg, Response::HTTP_CREATED, $mimeType, $format);
        }

        try {
            // Transform Body request to an User Object
            $user = $apiSerializer->dataDenormalize(
                $request->getContent(),
                User::class,
                $format
            );
            // Create the user in db
            $apiEntity->create($user);
            // Format User data in json to return it
            $jsonObject = $apiSerializer->objectSerialize($format, $user, ['movies']);
            // Set User data in cache redis
            $this->apiCache->set(ApiCache::USERS_HASH_KEY, $user->getId(), $user);
        } catch (Exception $e) {
            return $this->apiResponse->response(
                'Impossible to create User',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $mimeType
            );
        }

        return $this->apiResponse->response($jsonObject, Response::HTTP_CREATED, $mimeType);
    }

    /**
     * @param Request $request
     * @param ApiSerializer $apiSerializer
     * @param ApiEntity $apiEntity
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
        ApiEntity $apiEntity
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);

        try {
            /** @var User $user */
            $user = $apiEntity->find(User::class, $request->get('user_id'));
        } catch (EntityNotFoundException $e) {
            return $this->apiResponse->response(
                'No User Found',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $mimeType
            );
        }

        /** @var MovieRepository $movieRepository */
        $movieRepository = $apiEntity->getRepository(Movie::class);

        $movies = $movieRepository->findAllMovieByUser($user);

        $jsonObject = $apiSerializer->objectSerialize($format, $movies, ['users']);

        return $this->apiResponse->response($jsonObject, Response::HTTP_CREATED, $mimeType);
    }
}
