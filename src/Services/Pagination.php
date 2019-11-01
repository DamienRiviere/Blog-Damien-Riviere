<?php

namespace App\Services;

use App\Repository\Repository;

class Pagination
{
    /** SQL Request */
    private $query;

    /** SQL Count */
    private $queryCount;

    /** Number of item per page */
    private $perPage;

    /** Item count */
    private $count;

    private $items;

    private $id;

    public function __construct(string $query, string $queryCount, int $perPage = 10, int $id = null)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->perPage = $perPage;
        $this->id = $id;
    }

    public function getItems(string $class): array
    {
        if ($this->items === null) {
            $pages = $this->getPages();
            $currentPage = $this->getCurrentPage();

            if ($currentPage > $pages) {
                throw new \Exception("Cette page n'existe pas !");
            }

            $offset = $this->perPage * ($currentPage - 1);
            $query = Repository::getDb()->prepare(
                $this->query . " LIMIT {$this->perPage} OFFSET $offset"
            );
            $query->execute([$this->id]);
            $query->setFetchMode(\PDO::FETCH_CLASS, $class);
            $this->items = $query->fetchAll();
        }

        return $this->items;
    }

    public function previousLink(): ?string
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage <= 1) {
            return null;
        }

        if ($currentPage >= 2) {
            $link = "?page=" . ($currentPage - 1);
            return $link;
        }
    }

    public function nextLink(): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();

        if ($currentPage >= $pages) {
            return null;
        }

        return $link = "?page=" . ($currentPage + 1);
    }

    public function getCurrentPage(): int
    {
        return $currentPage = URL::getPositiveInt('page', 1);
    }

    /**
     * Get all pages
     *
     * @return int
     */
    public function getPages(): int
    {
        if ($this->count === null) {
            $query = Repository::getDb()->prepare(
                $this->queryCount
            );
            $query->execute([$this->id]);
            $query->setFetchMode(\PDO::FETCH_NUM);
            $this->count = $query->fetch()[0];
        }

        $pages = ceil($this->count / $this->perPage);

        if ($pages <= 0) {
            return $pages += 1;
        } else {
            return $pages;
        }
    }
}
