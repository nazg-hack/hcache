use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;
use type Nazg\HCache\Element;
use type Nazg\HCache\Driver\MemcachedCache;

class MemcachedCacheTest extends HackTest {

  public function testFetchShouldReturnNull(): void {
    expect(() ==> {
      $cache = new MemcachedCache();
      $mc = new Memcached('mc');
      $mc->addServers(vec(
        array('memcached', 11211)
      ));
      expect($cache->fetch("qwerty"))->toBeNull();
    })->toThrow(InvariantException::class);
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new MemcachedCache();
    $mc = new Memcached('mc');
    $mc->addServers(vec(
      array('memcached', 11211)
    ));
    $cache->setMemcached($mc);
    expect($cache->fetch("qwerty"))->toBeNull();
    $cache->save("qwerty", new Element('testing:cache', 5));
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    $cache->delete("qwerty");
    expect($cache->fetch("qwerty"))->toBeNull();
    $cache->save("qwerty", new Element('testing:cache', 5));
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    sleep(7);
    expect($cache->fetch("qwerty"))->toBeNull();
  }
}
