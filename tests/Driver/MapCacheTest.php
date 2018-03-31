<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MapCache;

class MapCacheTest extends TestCase {

  public function testFetchShouldReturnNull(): void {
    $cache = new MapCache();
    $this->assertNull($cache->fetch("qwerty"));
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new MapCache();
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
