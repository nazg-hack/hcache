<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\CacheManager;
use Nazg\HCache\CacheProvider;

class CacheManagerTest extends TestCase {
  
  public function testShouldReturnNullCacheInstance(): void {
    $manager = new CacheManager();
    $manager->addCache('null', () ==> new NullCache());
    $this->assertInstanceOf(NullCache::class, $manager->createCache('null'));
  }
}

class NullCache extends CacheProvider {
  public function fetch(string $id): mixed {
    return;
  }

  public function contains(string $id): bool {
    return false;
  }

  public function save(string $id, mixed $data, int $lifeTime = 0): bool {
    return true;
  }

  public function delete(string $id): bool {
    return true;
  }

  public function flushAll(): bool {
    return true;
  }
}