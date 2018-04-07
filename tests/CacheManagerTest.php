<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\Element;
use Nazg\HCache\CacheManager;
use Nazg\HCache\CacheProvider;
use Nazg\HCache\Driver\{MapCache, FileSystemCache, ApcCache, MemcachedCache, RedisCache, VoidCache};

class CacheManagerTest extends TestCase {

  public function testShouldReturnNullCacheInstance(): void {
    $manager = new CacheManager();
    $manager->addCache('null', () ==> new NullCache());
    $this->assertInstanceOf(NullCache::class, $manager->createCache('null'));
    // override cache driver
    $manager->addCache('map', () ==> new NullCache());
    $this->assertInstanceOf(NullCache::class, $manager->createCache('map'));
  }

  public function testShouldReturnCacheDrivers(): void {
    $manager = new CacheManager();
    $this->assertInstanceOf(MapCache::class, $manager->createCache('map'));
    $this->assertInstanceOf(FileSystemCache::class, $manager->createCache('file'));
    $this->assertInstanceOf(ApcCache::class, $manager->createCache('apc'));
    $this->assertInstanceOf(MemcachedCache::class, $manager->createCache('memcached'));
    $this->assertInstanceOf(RedisCache::class, $manager->createCache('redis'));
    $this->assertInstanceOf(VoidCache::class, $manager->createCache('void'));
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
