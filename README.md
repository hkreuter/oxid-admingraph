# hkreuter/oxid-admingraph

[![Build Status](https://img.shields.io/travis/com/hkreuter/oxid-admingraph/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.com/hkreuter/oxid-admingraph) [![PHP Version](https://img.shields.io/packagist/php-v/hkreuter/oxid-admingraph.svg?style=for-the-badge)](https://github.com/hkreuter/oxid-admingraph) [![Stable Version](https://img.shields.io/packagist/v/hkreuter/oxid-admingraph.svg?style=for-the-badge&label=latest)](https://packagist.org/packages/hkreuter/oxid-admingraph)

## Usage

This assumes you have the OXID eShop up and running and installed and activated the `oxid-esales/graphql-base` AdminOrder.

### Install

```bash
$ composer require hkreuter/oxid-admingraph
```

After requiring the module, you need to head over to the OXID eShop admin and
activate the GraphQL AdminGraph or activate via commandline

```bash
$ vendor/bin/oe-console oe:module:activate hkreuter_oxid_admingraph
```

### How to use

As admin user of (groups oxidadmin, oxidadmingraph) I want to query the count of orders
created in a specified timeframe

```graphql
type AdminOrders
{
    dateFrom: DateTime
    dateTo: DateTime
    ordersCount: int!
    orders: [Order]!
}

type Query {
    adminOrders (
        dateFrom: DateTime
        dateTo: DateTime
    ):  AdminOrders!
}
```

## Testing

### Linting, syntax, static analysis and unit tests

```bash
$ composer test
```

### Codeception tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add module to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests-codeception --group=admingraph
```

## License

GPLv3, see [LICENSE file](LICENSE).
