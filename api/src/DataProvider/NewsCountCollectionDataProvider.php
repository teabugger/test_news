<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Common\Filter\DateFilterInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\NewsCount;
use App\Repository\NewsCountRepository;
use DateHelper;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Exception;

final class NewsCountCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private NewsCountRepository $newsCountRepository;

    public function __construct(NewsCountRepository $newsCountRepository)
    {
        $this->newsCountRepository = $newsCountRepository;
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = []): Collection
    {
        $filters = $context['filters']['publishedAt'] ?? [];

        $firstDate = $this->getFirstDate($filters);
        $lastDate = $this->getLastDate($filters);

        if (null === $firstDate && null === $lastDate) {
            return $this->newsCountRepository->getAllNewsCountByDates();
        }

        return $this->newsCountRepository->getNewsCountByDatesForDateRange($firstDate, $lastDate);
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return NewsCount::class === $resourceClass;
    }

    private function getFirstDate(array $filters): ?DateTime
    {
        $afterDate = $this->getDate($filters, DateFilterInterface::PARAMETER_AFTER);
        $strictlyAfterDate = $this->getDate($filters, DateFilterInterface::PARAMETER_STRICTLY_AFTER);

        if (null !== $strictlyAfterDate) {
            $strictlyAfterDate = DateHelper::toStartOfNextDay($strictlyAfterDate);
            if (null !== $afterDate) {
                return max($afterDate, $strictlyAfterDate);
            }

            return $strictlyAfterDate;
        }

        return $afterDate;
    }

    private function getLastDate(array $filters): ?DateTime
    {
        $beforeDate = $this->getDate($filters, DateFilterInterface::PARAMETER_BEFORE);
        $strictlyBeforeDate = $this->getDate($filters, DateFilterInterface::PARAMETER_STRICTLY_BEFORE);
        if (null !== $strictlyBeforeDate) {
            $strictlyBeforeDate = DateHelper::toEndOfPreviousDay($strictlyBeforeDate);
            if (null !== $beforeDate) {
                return min($beforeDate, $strictlyBeforeDate);
            }

            return $strictlyBeforeDate;
        }

        return $beforeDate;
    }

    private function getDate(array $filters, string $parameter): ?DateTime
    {
        if (!isset($filters[$parameter])) {
            return null;
        }

        try {
            return new DateTime($filters[$parameter]);
        } catch (Exception $e) {
            return null;
        }
    }
}
