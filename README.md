# DofusDB PHP Wrapper

![CI Status](https://github.com/dofus-odyssey/dofusdb-php-wrapper/actions/workflows/ci.yml/badge.svg)

## Installation

```bash
composer require dofus-odyssey/dofusdb-php-wrapper
```

## Usage

```php
use DofusOdyssey\DofusdbPhpWrapper\QueryBuilder;
use DofusOdyssey\DofusdbPhpWrapper\ApiEndpoint;

// Find all breeds
$queryBuilder = new QueryBuilder(ApiEndpoint::BREEDS);
$queryBuilder->getQuery()->execute();

// Find all breed with a name equals to "Feca"
$queryBuilder = new QueryBuilder(ApiEndpoint::BREEDS);
$queryBuilder
    ->andWhere([
        ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Feca'],
    ])
    ->getQuery()
    ->execute();

// Find all breed with a name equals to "Feca" or "Enutrof"
$queryBuilder = new QueryBuilder(ApiEndpoint::BREEDS);
$queryBuilder
    ->orWhere([
        ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Feca'],
        ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Enutrof'],
    ])
    ->getQuery()
    ->execute();

// Get the query parameters in string format
$queryBuilder = new QueryBuilder(ApiEndpoint::BREEDS);
$queryBuilder
    ->orWhere([
        ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Feca'],
        ['field' => 'shortName.en', 'operator' => '=', 'value' => 'Enutrof'],
    ])
    ->getQuery()
    ->getRawQueryString();
// $or[0][shortName.en]=Feca&$or[1][shortName.en]=Enutrof
```

## License

This project is licensed under the MIT License. For more details, see the [LICENSE](LICENSE) file.