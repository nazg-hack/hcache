<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\HCache\Element;
use type Nazg\HCache\CacheManager;
use type Nazg\HCache\CacheProvider;
use type Nazg\HCache\Driver\{MapCache, FileSystemCache, ApcCache, MemcachedCache, RedisCache, VoidCache};

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

  /**
   * @expectedException \Nazg\HCache\Exception\CacheProviderNameExistsException
   */
  public function testShouldThrowException(): void {
    $manager = new CacheManager();
    $manager->createCache('test');
  }
}

class NullCache extends CacheProvider {
  <<__Override>>
  public function fetch(string $_id): mixed {
    return;
  }

  <<__Override>>
  public function contains(string $_id): bool {
    return false;
  }

  <<__Override>>
  public function save(string $_id, Element $_element): bool {
    return true;
  }

  <<__Override>>
  public function delete(string $_id): bool {
    return true;
  }

  <<__Override>>
  public function flushAll(): bool {
    return true;
  }
}
