<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get": {
 *             "openapi_context": {
 *                 "summary": "Retrieves the collection of count of News by dates",
 *                 "tags": {"NewsCount"},
 *                 "parameters": {
 *                     {
 *                         "in": "path",
 *                         "name": "publishedAt[before]",
 *                         "type": "string",
 *                         "example": "2021-07-25",
 *                     },
 *                     {
 *                         "in": "path",
 *                         "name": "publishedAt[strictly_before]",
 *                         "type": "string",
 *                         "example": "2021-07-25",
 *                     },
 *                     {
 *                         "in": "path",
 *                         "name": "publishedAt[after]",
 *                         "type": "string",
 *                         "example": "2021-07-25",
 *                     },
 *                     {
 *                         "in": "path",
 *                         "name": "publishedAt[strictly_after]",
 *                         "type": "string",
 *                         "example": "2021-07-25",
 *                     },
 *                 }
 *             }
 *         }
 *     },
 *     itemOperations={},
 *     shortName="NewsCount",
 *     paginationEnabled=false
 * )
 */
class NewsCount
{
    private DateTime $date;
    private int $count;

    public function __construct(DateTime $date, int $count)
    {
        $this->date = $date;
        $this->count = max(0, $count);
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
