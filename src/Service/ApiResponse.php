<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiResponse
 * @package App\Service
 */
class ApiResponse
{
    /**
     * @param $content
     * @param int $status
     * @param string $mimeType
     * @param string $format
     * @return Response
     */
    public function response($content, int $status, string $mimeType, string $format = ''): Response
    {
        if (!empty($format)) {
            $content = ApiSerializer::transformToString($format, $content);
        }
        return new Response(
            $content,
            $status,
            ['Content-Type' => $mimeType]
        );
    }
}
