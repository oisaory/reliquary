# Testing Information

## Testing Setup

The project uses PHPUnit for testing. Tests are located in the `tests/` directory and follow the same namespace structure as the source code, but with the `App\Tests` namespace.

The PHPUnit configuration in `phpunit.dist.xml` includes strict error handling settings that enforce high code quality:

- `failOnDeprecation="true"` - Tests fail when code uses deprecated features
- `failOnNotice="true"` - Tests fail on PHP notices
- `failOnWarning="true"` - Tests fail on PHP warnings
- `restrictNotices="true"` - Restricts what can trigger notices
- `restrictWarnings="true"` - Restricts what can trigger warnings

This strict configuration ensures that:
- Code is kept up-to-date with current PHP practices
- Potential issues are caught early in the development process
- Source code is thoroughly validated during testing

## Running Tests

To run all tests:

```bash
php bin/phpunit
```

To run a specific test file:

```bash
php bin/phpunit tests/Path/To/TestFile.php
```

To run tests with a specific filter:

```bash
php bin/phpunit --filter=testMethodName
```

## Creating Tests

### Unit Tests

Unit tests should extend `PHPUnit\Framework\TestCase` and test individual components in isolation. Example:

```php
<?php

namespace App\Tests\Entity;

use App\Entity\Relic;
use PHPUnit\Framework\TestCase;

class RelicTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $relic = new Relic();

        $location = 'Vatican City';
        $relic->setLocation($location);
        $this->assertEquals($location, $relic->getLocation());
    }
}
```

### Functional Tests

Functional tests should extend `Symfony\Bundle\FrameworkBundle\Test\WebTestCase` and test the integration of components. Example:

```php
<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelicControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/relic');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Relics');
    }
}
```

Note: Functional tests may require database setup and fixtures to work properly.

## Test Database

For tests that require a database, you should:

1. Configure a test database in your `.env.test` file
2. Create the test database schema:

```bash
docker compose exec app bin/console doctrine:database:create --env=test
docker compose exec app bin/console doctrine:migrations:migrate --env=test
```

3. Load fixtures if needed:

```bash
docker compose exec app bin/console doctrine:fixtures:load --env=test
```