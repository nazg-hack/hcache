use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;
use type Nazg\HCache\Element;
use type Nazg\HCache\Driver\MapCache;

class MapCacheTest extends HackTest {

  public function testFetchShouldReturnNull(): void {
    $cache = new MapCache();
    expect($cache->fetch("qwerty"))->toBeNull();
  }

  public function testFetchShouldReturnNull2(): void {
    $cache = new MapCache();
    $cache->save("qwerty", new Element('testing:cache', 0));
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
