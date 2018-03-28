<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\HCache\Driver\VoidCache;

class VoidCacheTest extends TestCase {
  
  public function testFetchShouldReturnNull(): void {
    $cache = new VoidCache();
    $this->assertNull($cache->fetch("qwerty"));
  }
}
