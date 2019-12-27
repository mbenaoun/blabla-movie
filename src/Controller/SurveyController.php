<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Exception\MovieException;
use App\Repository\MovieRepository;
use App\Service\ApiEntity;
use App\Service\ApiMovie;
use App\Service\ApiSerializer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

/**
 * Class SurveyController
 * @package App\Controller
 * @Route("/v1/survey", name="survey_")
 */
class SurveyController extends ControllerAbstract
{
    /**
     * @param Request $request
     * @param ApiEntity $apiEntity
     * @param ApiMovie $apiMovie
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
        ApiEntity $apiEntity,
        ApiMovie $apiMovie
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);
        $content = $request->getContent();
        $statusResp = Response::HTTP_INTERNAL_SERVER_ERROR;
        $formatResp = "";

        $data = ApiSerializer::getDataFromFormat($format, $content);

        try {
            /** @var User $user */
            $user = $apiEntity->find(User::class, $data['userId']);
        } catch (EntityNotFoundException $entityNotFoundException) {
            return $this->apiResponse->response(
                "Impossible to find the User (" . $user->getId() . ")!",
                $statusResp,
                $mimeType,
                $formatResp
            );
        }

        $movie = null;
        try {
            $movie = $apiMovie->getOrCreateMovie($data);
        } catch (MovieException $e) {
            return $this->apiResponse->response($e->getMessage(), $statusResp, $mimeType, $formatResp);
        }

        $isAttached = $user->attachMovie($movie);
        if ($isAttached) {
            try {
                $apiEntity->update($user);
                $contentResp = ['userId' => $user->getId(), 'movieId' => $movie->getId()];
                $statusResp = Response::HTTP_CREATED;
                $formatResp = $format;
            } catch (Exception $exception) {
                $contentResp = "Impossible to attach an new movie to User (" . $user->getId() . ")! Technical error.";
            }
        } else {
            $contentResp = "Impossible to attach an new movie to User (" . $user->getId() . ")! Technical error.";
        }

        return $this->apiResponse->response($contentResp, $statusResp, $mimeType, $formatResp);
    }

    /**
     * @param Request $request
     * @param ApiEntity $apiEntity
     * @return Response
     * @Route(
     *     "/{user_id}/{movie_id?}.{_format}",
     *     name="delete",
     *     format="json",
     *     requirements={
     *          "_format": "xml|json",
     *     },
     *     methods={"DELETE"}
     *)
     */
    public function delete(
        Request $request,
        ApiEntity $apiEntity
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);
        $userId = $request->get('user_id');
        $movieId = $request->get('movie_id');
        $statusResp = Response::HTTP_INTERNAL_SERVER_ERROR;

        try {
            /** @var User $user */
            $user = $apiEntity->find(User::class, $userId);
        } catch (EntityNotFoundException $e) {
            return $this->apiResponse->response(
                "Impossible to find the User (" . $userId . ")",
                $statusResp,
                $mimeType
            );
        }

        if ($user->getMovies()->count() > 0) {
            if (is_null($movieId)) {
                $user->setMovies(new ArrayCollection());
            } else {
                try {
                    /** @var Movie $movie */
                    $movie = $apiEntity->find(Movie::class, $movieId);
                    $user->removeMovie($movie);
                } catch (EntityNotFoundException $e) {
                    return $this->apiResponse->response(
                        "Impossible to find the Movie (" . $movieId . ")",
                        $statusResp,
                        $mimeType
                    );
                }
            }

            try {
                $apiEntity->update($user);
                $contentResp = "Delete Successfully !";
                $statusResp = Response::HTTP_OK;
            } catch (ORMException $e) {
                $contentResp = "Impossible to delete Movie(s) for the User (" . $userId . ")";
            }
        } else {
            $contentResp = "No Movie(s) found for the User (" . $userId . ")";
        }

        return $this->apiResponse->response(
            $contentResp,
            $statusResp,
            $mimeType
        );
    }

    /**
     * @param Request $request
     * @param ApiSerializer $apiSerializer
     * @param ApiEntity $apiEntity
     * @return Response
     * @Route(
     *     "/best-movie.{_format}",
     *     name="best_movie",
     *     format="json",
     *     requirements={
     *          "_format": "xml|json",
     *     },
     *     methods={"GET"}
     *)
     */
    public function getBestMovie(
        Request $request,
        ApiEntity $apiEntity,
        ApiSerializer $apiSerializer
    ): Response {
        $format = $request->getRequestFormat();
        $mimeType = $request->getMimeType($format);

        /** @var MovieRepository $movieRepository */
        $movieRepository = $apiEntity->getRepository(Movie::class);

        $movie = $movieRepository->findBestMovie();
        if ($movie instanceof Movie) {
            $jsonObject = $apiSerializer->objectSerialize($format, $movie, ['users']);

            return $this->apiResponse->response($jsonObject, Response::HTTP_OK, $mimeType);
        } else {
            return $this->apiResponse->response(
                "No Best Movie Found",
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $mimeType
            );
        }
    }
}
