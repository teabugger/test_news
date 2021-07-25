<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use DateHelper;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use Exception;

class DateWithoutTimeFilter extends DateFilter
{
    /**
     * @param array $values
     */
    protected function filterProperty(string $property, $values, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null): void
    {
        $this->modifyDateForCompareWithoutTime($values);
        parent::filterProperty($property, $values, $queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
    }

    private function modifyDateForCompareWithoutTime(array &$values): void
    {
        if (isset($values[self::PARAMETER_BEFORE])) {
            try {
                $values[self::PARAMETER_BEFORE] = DateHelper::toEndOfDay(new DateTime($values[self::PARAMETER_BEFORE]))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                unset($values[self::PARAMETER_BEFORE]);
            }
        }

        if (isset($values[self::PARAMETER_STRICTLY_BEFORE])) {
            try {
                $values[self::PARAMETER_STRICTLY_BEFORE] = DateHelper::toStartOfDay(new DateTime($values[self::PARAMETER_STRICTLY_BEFORE]))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                unset($values[self::PARAMETER_STRICTLY_BEFORE]);
            }
        }

        if (isset($values[self::PARAMETER_AFTER])) {
            try {
                $values[self::PARAMETER_AFTER] = DateHelper::toStartOfDay(new DateTime($values[self::PARAMETER_AFTER]))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                unset($values[self::PARAMETER_AFTER]);
            }
        }

        if (isset($values[self::PARAMETER_STRICTLY_AFTER])) {
            try {
                $values[self::PARAMETER_STRICTLY_AFTER] = DateHelper::toEndOfDay(new DateTime($values[self::PARAMETER_STRICTLY_AFTER]))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                unset($values[self::PARAMETER_STRICTLY_AFTER]);
            }
        }
    }
}
