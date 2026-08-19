<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class Paginator
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them under parameters section in config/services.yaml file.
     *
     * See https://symfony.com/doc/current/best_practices.html#use-constants-to-define-options-that-rarely-change
     */
    final public const PAGE_SIZE = 10;

    private int $currentPage;
    private int $numResults;

    /**
     * @var \Traversable<array-key, object>
     */
    private \Traversable $results;

    public function __construct(
        private readonly DoctrineQueryBuilder $queryBuilder,
        private readonly int $pageSize = self::PAGE_SIZE,
    ) {
	error_log(sprintf('>>> Entering __construct($queryBuilder=%s,$pageSize=%s)', is_scalar($queryBuilder) ? $queryBuilder : gettype($queryBuilder), is_scalar($pageSize) ? $pageSize : gettype($pageSize)));
	error_log(sprintf('<<< Exiting __construct($queryBuilder=%s,$pageSize=%s)', is_scalar($queryBuilder) ? $queryBuilder : gettype($queryBuilder), is_scalar($pageSize) ? $pageSize : gettype($pageSize)));
    }

    public function paginate(int $page = 1): self
    {
		error_log(sprintf('>>> Entering paginate($page=%s)', is_scalar($page) ? $page : gettype($page)));
        $this->currentPage = max(1, $page);
        $firstResult = ($this->currentPage - 1) * $this->pageSize;

        $query = $this->queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->pageSize)
            ->getQuery();

        /** @var array<string, mixed> $joinDqlParts */
        $joinDqlParts = $this->queryBuilder->getDQLPart('join');

        if (0 === \count($joinDqlParts)) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        /** @var DoctrinePaginator<object> $paginator */
        $paginator = new DoctrinePaginator($query, true);

        /** @var array<string, mixed> $havingDqlParts */
        $havingDqlParts = $this->queryBuilder->getDQLPart('having');

        $useOutputWalkers = \count($havingDqlParts ?: []) > 0;
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $this->results = $paginator->getIterator();
        $this->numResults = $paginator->count();

		error_log(sprintf('<<< Exiting paginate($page=%s)', is_scalar($page) ? $page : gettype($page)));
        return $this;
    }

    public function getCurrentPage(): int
    {
		error_log(sprintf('>>> Entering getCurrentPage()'));
		error_log(sprintf('<<< Exiting getCurrentPage()'));
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
		error_log(sprintf('>>> Entering getLastPage()'));
		error_log(sprintf('<<< Exiting getLastPage()'));
        return (int) ceil($this->numResults / $this->pageSize);
    }

    public function getPageSize(): int
    {
		error_log(sprintf('>>> Entering getPageSize()'));
		error_log(sprintf('<<< Exiting getPageSize()'));
        return $this->pageSize;
    }

    public function hasPreviousPage(): bool
    {
		error_log(sprintf('>>> Entering hasPreviousPage()'));
		error_log(sprintf('<<< Exiting hasPreviousPage()'));
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
		error_log(sprintf('>>> Entering getPreviousPage()'));
		error_log(sprintf('<<< Exiting getPreviousPage()'));
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
		error_log(sprintf('>>> Entering hasNextPage()'));
		error_log(sprintf('<<< Exiting hasNextPage()'));
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
		error_log(sprintf('>>> Entering getNextPage()'));
		error_log(sprintf('<<< Exiting getNextPage()'));
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
		error_log(sprintf('>>> Entering hasToPaginate()'));
		error_log(sprintf('<<< Exiting hasToPaginate()'));
        return $this->numResults > $this->pageSize;
    }

    public function getNumResults(): int
    {
		error_log(sprintf('>>> Entering getNumResults()'));
		error_log(sprintf('<<< Exiting getNumResults()'));
        return $this->numResults;
    }

    /**
     * @return \Traversable<int, object>
     */
    public function getResults(): \Traversable
    {
		error_log(sprintf('>>> Entering getResults()'));
		error_log(sprintf('<<< Exiting getResults()'));
        return $this->results;
    }
}
