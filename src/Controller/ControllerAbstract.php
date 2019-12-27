<?php

namespace App\Controller;

use App\Service\ApiCache;
use App\Service\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ControllerAbstract
 * @package App\Controller
 */
abstract class ControllerAbstract extends AbstractController
{
    /** @var ApiCache $apiCache */
    public $apiCache;

    /** @var ApiResponse $apiResponse */
    public $apiResponse;

    /**
     * ControllerAbstract constructor.
     * @param ApiCache $apiCache
     * @param ApiResponse $apiResponse
     */
    public function __construct(ApiCache $apiCache, ApiResponse $apiResponse)
    {
        $this->apiCache = $apiCache;
        $this->apiResponse = $apiResponse;
    }
}
