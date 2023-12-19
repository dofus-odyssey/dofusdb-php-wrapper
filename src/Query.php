<?php

namespace DofusOdyssey\DofusdbPhpWrapper;

class Query
{
    private DofusDb $dofusDb;

    /**
     * @param array<string, mixed> $query
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $query,
        private readonly ?int $id = null,
    ) {
        $this->dofusDb = new DofusDb();
    }

    public function execute(): string
    {
        return $this->dofusDb->executeQuery($this->endpoint, $this->buildQueryString(), $this->id);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->query;
    }

    public function getRawQueryString(): string
    {
        return implode('&', $this->flattenQueryArray($this->query, '', false));
    }

    public function getEncodedQueryString(): string
    {
        return implode('&', $this->flattenQueryArray($this->query));
    }

    /**
     * @param array<string, mixed> $query
     */
    public function buildQuery(array $query): string
    {
        return implode('&', $this->flattenQueryArray($query));
    }

    private function buildQueryString(): string
    {
        return $this->buildQuery($this->query);
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return string[]
     */
    private function flattenQueryArray(array $query, string $prefix = '', bool $encode = true): array
    {
        $result = [];

        foreach ($query as $key => $value) {
            if ('$select' === $key && \is_array($value)) {
                foreach ($value as $selectValue) {
                    $selectKey = $encode ? urlencode($key).'[]' : $key.'[]';
                    $result[] = "{$selectKey}=".($encode ? urlencode($selectValue) : $selectValue);
                }
                continue;
            }

            $formattedKey = $this->formatKey($prefix, $key);
            if (\is_array($value)) {
                $result = array_merge($result, $this->flattenQueryArray($value, $formattedKey, $encode));
                continue;
            }

            $encodedKey = $encode ? urlencode($formattedKey) : $formattedKey;
            $result[] = "{$encodedKey}=".($encode ? urlencode($value) : $value);
        }

        return $result;
    }

    private function formatKey(string $prefix, string $key): string
    {
        if ('' === $prefix) {
            return $key;
        }

        return $prefix.'['.$key.']';
    }
}
