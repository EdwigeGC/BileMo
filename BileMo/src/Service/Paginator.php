<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;


/**
 * Service to paginate object in templates
 *
 * @author Edwige Genty
 */
class Paginator
{
    /**
     * @var string $entityClass find name entity class concerned
     */
    private $entityClass;

    private $path;

    /**
     * @var int $limit maximum number of objects displayed
     */
    private $limit = 10;

    /**
     * @var int $currentPage page number
     */
    private $currentPage;

    /**
     * @var ObjectManager $manager
     */
    private $manager;

    /**
     * @var array $orderBy orders the request with argument like 'desc'
     */
    private $orderBy =[];

    /**
     * @var array $filterBy filters the request. Corresponds to 'WHERE' in sql request
     */
    private $filterBy =[];

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Provides the number of pages according to the number of objects to display
     *
     * @return float $totalPages
     */

    /**
     * Setter for EntityClass attribute
     * @param $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Getter for EntityClass attribute
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * Setter for limit attribute
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Getter for limit attribute
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Setter for page attribute
     * @param $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Getter for page attribute
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Setter for orderBy attribute
     * @param $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Setter for filterBy attribute
     * @param $filterBy
     */
    public function setFilterBy($filterBy)
    {
        $this->filterBy = $filterBy;
        return $this;
    }

    /**
     * Provides the result of the request
     * @return PaginatedRepresentation
     */
    public function getData(): PaginatedRepresentation
    {
        $offset = $this->currentPage * $this->limit - $this->limit;
        $repository = $this->manager->getRepository($this->entityClass);
        $total = count($repository->findBy($this->filterBy, $this->orderBy));
        $totalPages = ceil($total / $this->limit);
        $data = $repository->findBy($this->filterBy, $this->orderBy, $this->limit, $offset);

        $pagination = new PaginatedRepresentation(
            new CollectionRepresentation($data),
            $this->path,
            array(),
            $this->currentPage,
            $this->limit,
            $totalPages,
            'current page',
            'limit',
            true,
            $total
        );

        return $pagination;
    }

}
