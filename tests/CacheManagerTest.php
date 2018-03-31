<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\Element;
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
  <<__Override>>
  public function fetch(string $id): mixed {
    return;
  }

  <<__Override>>
  public function contains(string $id): bool {
    return false;
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    return true;
  }

  <<__Override>>
  public function delete(string $id): bool {
    return true;
  }

  <<__Override>>
  public function flushAll(): bool {
    return true;
  }
}