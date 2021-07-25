<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NewsCount;
use DateHelper;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class NewsCountRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllNewsCountByDates(): Collection
    {
        $query = <<< 'EOB'
            SELECT date(published_at) as date, count(*) as count
            FROM news
            GROUP BY date
            ORDER BY date DESC
            EOB;

        return $this->getResults($query);
    }

    public function getNewsCountByDatesForDateRange(?DateTime $firstDate = null, ?DateTime $lastDate = null): Collection
    {
        if (null === $firstDate && null === $lastDate) {
            return $this->getAllNewsCountByDates();
        }

        if (null !== $firstDate && null !== $lastDate) {
            return $this->getNewsCountByDatesBetweenDates($firstDate, $lastDate);
        }

        if (null !== $firstDate) {
            return $this->getNewsCountByDatesAfterDate($firstDate);
        }

        if (null !== $lastDate) {
            return $this->getNewsCountByDatesBeforeDate($lastDate);
        }

        return new ArrayCollection();
    }

    private function getNewsCountByDatesBetweenDates(DateTime $firstDate, DateTime $lastDate): Collection
    {
        $lastDateClone = DateHelper::toStartOfNextDay($lastDate);
        $firstDateFormatted = $firstDate->format('Y-m-d');
        $lastDateFormatted = $lastDateClone->format('Y-m-d');
        $query = <<<EOB
            SELECT date(published_at) as date, count(*) as count
            FROM news
            WHERE published_at BETWEEN '{$firstDateFormatted}' AND '{$lastDateFormatted}'
            GROUP BY date
            ORDER BY date DESC
            EOB;

        return $this->getResults($query);
    }

    private function getNewsCountByDatesAfterDate(DateTime $firstDate): Collection
    {
        $firstDateClone = clone $firstDate;
        $firstDateClone = DateHelper::toEndOfPreviousDay($firstDateClone);
        $firstDateFormatted = $firstDateClone->format('Y-m-d');
        $query = <<<EOB
            SELECT date(published_at) as date, count(*) as count
            FROM news
            WHERE published_at > '{$firstDateFormatted}'
            GROUP BY date
            ORDER BY date DESC
            EOB;

        return $this->getResults($query);
    }

    private function getNewsCountByDatesBeforeDate(DateTime $lastDate): Collection
    {
        $lastDateDateClone = clone $lastDate;
        $lastDateDateClone = DateHelper::toStartOfNextDay($lastDateDateClone);
        $lastDateFormatted = $lastDateDateClone->format('Y-m-d');
        $query = <<<EOB
            SELECT date(published_at) as date, count(*) as count
            FROM news
            WHERE published_at < '{$lastDateFormatted}'
            GROUP BY date
            ORDER BY date DESC
            EOB;

        return $this->getResults($query);
    }

    private function getResults(string $sql): Collection
    {
        $entityManager = $this->entityManager;
        $collection = new ArrayCollection();

        try {
            $statement = $entityManager->getConnection()->prepare($sql);
            $result = $statement->executeQuery();
            foreach ($result->fetchAllAssociative() as $item) {
                $collection[] = new NewsCount(new DateTime($item['date']), (int) $item['count']);
            }

            return $collection;
        } catch (DBALException | DriverException | Exception $e) {
            return new ArrayCollection();
        }
    }
}
