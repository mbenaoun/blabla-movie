<?php

namespace App\Service;

use App\Entity\EntityAbstract;
use App\Exception\DenormalizeException;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ApiSerializer
 * @package App\Service
 */
class ApiSerializer
{
    /**
     * @param string $format
     * @param EntityAbstract|EntityAbstract[] $entity
     * @param array $ignoredAttributes
     * @return string
     */
    public function objectSerialize(string $format, $entity, array $ignoredAttributes): string
    {
        $encoders = [new JsonEncoder(), new XmlEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($entity, $format, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttributes
        ]);
    }

    /**
     * @param $content
     * @param string $className
     * @param string $format
     * @return EntityAbstract
     * @throws DenormalizeException
     * @throws ExceptionInterface
     */
    public function dataDenormalize($content, string $className, string $format = ''): EntityAbstract
    {
        if (!empty($format)) {
            $content = self::getDataFromFormat($format, $content);
        }
        $normalizers = [
            new DateTimeNormalizer(),
            new ObjectNormalizer(
                null,
                null,
                null,
                new ReflectionExtractor()
            )
        ];
        $serializer = new Serializer($normalizers);
        $object = $serializer->denormalize($content, $className);
        if (!$object instanceof EntityAbstract) {
            throw new DenormalizeException('ERROR');
        }

        return $object;
    }

    /**
     * @param string $format
     * @param string $content
     * @return mixed
     */
    public static function getDataFromFormat(string $format, string $content)
    {
        if ($format == 'json') {
            $data = json_decode($content, true);
        } else {
            $data = xmlrpc_decode($content);
        }

        return $data;
    }

    /**
     * @param string $format
     * @param $content
     * @return false|string
     */
    public static function transformToString(string $format, $content)
    {
        if ($format == 'json') {
            $data = json_encode($content);
        } else {
            $data = xmlrpc_encode($content);
        }

        return $data;
    }
}
