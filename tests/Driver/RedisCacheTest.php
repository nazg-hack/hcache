<?hh // strict

use Redis;
use PHPUnit\Framework\TestCase;
use Nazg\HCache\Element;
use Nazg\HCache\Driver\RedisCache;

class RedisCacheTest extends TestCase {

  /**
   * @expectedException \HH\InvariantException
   */
  public function testFetchShouldReturnNull(): void {
    $cache = new RedisCache();
    $this->assertNull($cache->fetch("qwerty"));
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new RedisCache();
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $cache->setRedis($redis);
    $cache->save("qwerty", new Element('testing:cache', 0));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    $cache->delete("qwerty");
    $this->assertNull($cache->fetch("qwerty"));
    $cache->save("qwerty", new Element('testing:cache', 5));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    sleep(7);
    $this->assertNull($cache->fetch("qwerty"));

    $cache->save("qwerty", new Element('testing:cache', 0));
    $cache->flushAll();
    $this->assertNull($cache->fetch("qwerty"));
  }
}
