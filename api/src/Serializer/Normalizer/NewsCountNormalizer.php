<?php

namespace App\Serializer\Normalizer;

use App\Entity\NewsCount;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NewsCountNormalizer implements NormalizerInterface
{
    /**
     * @param NewsCount $object
     */
    #[ArrayShape(['date' => "string", 'count' => "int"])]
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'date' => $object->getDate()->format('Y-m-d'),
            'count' => $object->getCount(),
        ];
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof NewsCount;
    }
}
