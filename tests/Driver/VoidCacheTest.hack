use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;
use type Nazg\HCache\Driver\VoidCache;

final class VoidCacheTest extends HackTest {

  public function testFetchShouldReturnNull(): void {
    $cache = new VoidCache();
    expect($cache->fetch("qwerty"))->toBeNull();
  }
}
