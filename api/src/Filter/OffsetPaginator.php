<?php

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use StringHelper;

/**
 * Filters person by privacy and owner.
 */
class OffsetPaginator extends AbstractContextAwareFilter
{
    private const DEFAULT_OFFSET = 0;
    private const DEFAULT_LIMIT = 10;

    public function apply(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): void {
        $this->filterProperty(
            '',
            '',
            $queryBuilder,
            $queryNameGenerator,
            $resourceClass,
            $operationName);
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'offset' => [
                'property' => 'offset',
                'type' => 'integer',
                'required' => false,
                'default' => 0,
            ],
            'limit' => [
                'property' => 'limit',
                'type' => 'integer',
                'required' => false,
                'default' => 10,
            ],
        ];
    }

    /**
     * @param string $value
     */
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?string $operationName = null
    ): void {
        if (!$this->isPropertyValid()) {
            return;
        }

        $offset = $_GET['offset'] ?? self::DEFAULT_OFFSET;
        $limit = $_GET['limit'] ?? self::DEFAULT_LIMIT;

        $queryBuilder->setFirstResult((int) $offset)->setMaxResults((int) $limit);
    }

    protected function isPropertyValid(): bool
    {
        $offset = $_GET['offset'] ?? (string) self::DEFAULT_OFFSET;
        $limit = $_GET['limit'] ?? (string) self::DEFAULT_LIMIT;

        return StringHelper::isNonNegativeInteger($offset) && StringHelper::isPositiveInteger($limit);
    }
}
