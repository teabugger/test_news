<?php

namespace App\Queue\Handlers;

use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateNewsHandler implements MessageHandlerInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function __invoke(string $message): void
    {
        $news = $this->serializer->deserialize($message, News::class, 'json');
        $violations = $this->validator->validate($news);
        if (0 === $violations->count()) {
            $this->entityManager->persist($news);
            $this->entityManager->flush();
        }
    }
}
