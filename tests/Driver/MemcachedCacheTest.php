<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MemcachedCache;

class MemcachedCacheTest extends TestCase {

  /**
   * @expectedException \HH\InvariantException
   */
  public function testFetchShouldReturnNull(): void {
    $cache = new MemcachedCache();
    $this->assertNull($cache->fetch("qwerty"));
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new MemcachedCache();
    $mc = new Memcached('mc');
    $mc->addServers(array(
      ['192.168.11.32', 11211],
    ));
    $cache->setMemcached($mc);
    $cache->save("qwerty", new Element('testing:cache', 0));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    $cache->delete("qwerty");
    $this->assertNull($cache->fetch("qwerty"));
    $cache->save("qwerty", new Element('testing:cache', 5));
    $this->assertSame('testing:cache', $cache->fetch("qwerty"));
    sleep(7);
    $this->assertNull($cache->fetch("qwerty"));
  }
}
