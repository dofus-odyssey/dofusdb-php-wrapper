<?php

use DofusOdyssey\DofusdbPhpWrapper\ApiEndpoint;
use DofusOdyssey\DofusdbPhpWrapper\QueryBuilder;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    private QueryBuilder $queryBuilder;

    protected function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder(ApiEndpoint::BREEDS);
    }

    public function testQuerySelectIdRequest(): void
    {
        $query = $this->queryBuilder
            ->find(1)
            ->addSelect('id')
            ->getQuery();

        $response = $query->execute();

        $this->assertJson($response);
        $this->assertStringEqualsFile(
            'tests/ExpectedResponses/find.json', $response
        );
    }

    public function testAndConditionRequest(): void
    {
        $query = $this->queryBuilder
            ->addSelect('id')
            ->andWhere([
                ['field' => 'shortName.fr', 'operator' => '=', 'value' => 'Féca'],
            ])
            ->getQuery();

        $response = $query->execute();

        $this->assertJson($response);
        $this->assertStringEqualsFile(
            'tests/ExpectedResponses/and_condition.json', $response
        );
    }

    public function testOrConditionRequest()
    {
        $query = $this->queryBuilder
            ->orWhere([
                ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Feca'],
                ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Enutrof'],
            ])
            ->getQuery();

        $response = $query->execute();

        $this->assertJson($response);
        $this->assertStringEqualsFile(
            'tests/ExpectedResponses/or_condition.json', $response
        );
    }
}
