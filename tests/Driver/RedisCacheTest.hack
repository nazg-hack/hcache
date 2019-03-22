use type Redis;
use function Facebook\FBExpect\expect;
use type Facebook\HackTest\HackTest;
use type Nazg\HCache\Element;
use type Nazg\HCache\Driver\RedisCache;

class RedisCacheTest extends HackTest {

  public function testFetchShouldReturnNull(): void {
    expect(() ==> {
      $cache = new RedisCache();
      expect($cache->fetch("qwerty"))->toBeNull();
    })->toThrow(\HH\InvariantException::class);
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new RedisCache();
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $cache->setRedis($redis);
    $cache->save("qwerty", new Element('testing:cache', 0));
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    $cache->delete("qwerty");
    expect($cache->fetch("qwerty"))->toBeNull();
    $cache->save("qwerty", new Element('testing:cache', 5));
    expect($cache->fetch("qwerty"))->toBeSame('testing:cache');
    sleep(7);
    expect($cache->fetch("qwerty"))->toBeNull();

    $cache->save("qwerty", new Element('testing:cache', 0));
    $cache->flushAll();
    expect($cache->fetch("qwerty"))->toBeNull();
  }
}
