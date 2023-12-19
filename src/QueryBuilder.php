<?php

namespace DofusOdyssey\DofusdbPhpWrapper;

class QueryBuilder
{
    /**
     * @var array<string, mixed>
     */
    private array $query = [];

    private ?int $id = null;

    public function __construct(
        private readonly string $endpoint,
    ) {
    }

    public function find(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * will return only the number of results you specify.
     */
    public function setLimit(int $limit): self
    {
        $this->query['$limit'] = $limit;

        return $this;
    }

    /**
     * will skip the specified number of results.
     */
    public function setSkip(int $skip): self
    {
        $this->query['$skip'] = $skip;

        return $this;
    }

    /**
     * will sort based on the object you provide.
     */
    public function addSort(string $field, string $order = 'ASC'): self
    {
        $this->query['$sort'][$field] = $order;

        return $this;
    }

    /**
     * allows to pick which fields to include in the result.
     */
    public function addSelect(string $field): self
    {
        $this->query['$select'][] = $field;

        return $this;
    }

    /**
     * @param array<array{field: string, operator: string, value: mixed}> $conditions
     */
    public function andWhere(array $conditions): self
    {
        foreach ($conditions as $condition) {
            $this->validateConditionArray($condition);
            $this->query['$and'][] = [$condition['field'] => $this->formatCondition($condition['operator'], $condition['value'])];
        }

        return $this;
    }

    /**
     * @param array<array{field: string, operator: string, value: mixed}> $conditions
     */
    public function orWhere(array $conditions): self
    {
        foreach ($conditions as $condition) {
            $this->validateConditionArray($condition);
            $this->query['$or'][] = [$condition['field'] => $this->formatCondition($condition['operator'], $condition['value'])];
        }

        return $this;
    }

    public function getQuery(): Query
    {
        return new Query($this->endpoint, $this->query, $this->id);
    }

    /**
     * @return string|array<string, string>
     */
    private function formatCondition(string $operator, string $value): string|array
    {
        return match ($operator) {
            '>' => ['$gt' => $value],
            '>=' => ['$gte' => $value],
            '<' => ['$lt' => $value],
            '<=' => ['$lte' => $value],
            '!=' => ['$ne' => $value],
            default => $value,
        };
    }

    /**
     * @param array{field: ?string, operator: ?string, value: ?string} $condition
     */
    private function validateConditionArray(array $condition): void
    {
        if (!isset($condition['field'], $condition['operator'], $condition['value'])) {
            throw new \InvalidArgumentException('A condition should contains a field, an operator and a value');
        }
    }
}
