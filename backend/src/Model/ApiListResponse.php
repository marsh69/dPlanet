<?php

namespace App\Model;

class ApiListResponse
{
    /** @var object[] $data */
    protected $data = [];
    /** @var int|null $offset */
    protected $offset = 0;
    /** @var int $total */
    protected $total = 0;
    /** @var int|null $limit */
    protected $limit = 0;

    /**
     * ApiListResponse constructor.
     * @param array $data
     * @param int $limit
     * @param int $offset
     * @param int $total
     */
    public function __construct(array $data, ?int $limit, ?int $offset, int $total)
    {
        $this->data = $data;
        $this->offset = $offset;
        $this->total = $total;
        $this->limit = $limit;
    }

    /**
     * @return object[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }


    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->data);
    }
}
