use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;
use type Nazg\HCache\Element;
use type Nazg\HCache\CacheManager;
use type Nazg\HCache\CacheProvider;
use type Nazg\HCache\Driver\{MapCache, FileSystemCache, ApcCache, MemcachedCache, RedisCache, VoidCache};

final class CacheManagerTest extends HackTest {

  public function testShouldReturnNullCacheInstance(): void {
    $manager = new CacheManager();
    $manager->addCache('null', () ==> new NullCache());
    expect($manager->createCache('null'))->toBeInstanceOf(NullCache::class);
    // override cache driver
    $manager->addCache('map', () ==> new NullCache());
    expect($manager->createCache('map'))->toBeInstanceOf(NullCache::class);
  }

  public function testShouldReturnCacheDrivers(): void {
    $manager = new CacheManager();
    expect($manager->createCache('map'))->toBeInstanceOf(MapCache::class);
    expect($manager->createCache('file'))->toBeInstanceOf(FileSystemCache::class);
    expect($manager->createCache('apc'))->toBeInstanceOf(ApcCache::class);
    expect($manager->createCache('memcached'))->toBeInstanceOf(MemcachedCache::class);
    expect($manager->createCache('void'))->toBeInstanceOf(VoidCache::class);
  }

  public function testShouldThrowException(): void {
    expect(() ==> {
      $manager = new CacheManager();
      $manager->createCache('test');
    })->toThrow(\Nazg\HCache\Exception\CacheProviderNameExistsException::class);
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
