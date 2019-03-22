use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;
use type Nazg\HCache\Element;
use type Nazg\HCache\Driver\FileSystemCache;

class FileSystemCacheTest extends HackTest {

  public function testFetchShouldReturnNull(): void {
    $cache = new FileSystemCache();
    expect($cache->fetch("qwerty"))->toBeNull();
  }

  public function testShouldReturnContent(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 0));
    expect($cache->fetch('file'))->toBeSame('testing');
    $cache->delete('file');
    expect($cache->fetch('file'))->toBeNull();
  }

  public function testShouldFlushCache(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 0));
    expect($cache->fetch('file'))->toBeSame('testing');
    $cache->flushAll();
    expect($cache->fetch('file'))->toBeNull();
  }

  public function testShouldContainsCache(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 2));
    expect($cache->contains('file'))->toBeTrue();
    expect($cache->fetch('file'))->toBeSame('testing');
    sleep(3);
    expect($cache->fetch('file'))->toBeNull();
  }
}
