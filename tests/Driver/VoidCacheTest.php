<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\HCache\Driver\VoidCache;

class VoidCacheTest extends TestCase {

  public function testFetchShouldReturnNull(): void {
    $cache = new VoidCache();
    $this->assertNull($cache->fetch("qwerty"));
  }
}
