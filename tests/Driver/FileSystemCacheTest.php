<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\HCache\Element;
use type Nazg\HCache\Driver\FileSystemCache;

class FileSystemCacheTest extends TestCase {

  public function testFetchShouldReturnNull(): void {
    $cache = new FileSystemCache();
    $this->assertNull($cache->fetch("qwerty"));
  }

  public function testShouldReturnContent(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 0));
    $this->assertSame('testing', $cache->fetch('file'));
    $cache->delete('file');
    $this->assertNull($cache->fetch('file'));
  }

  public function testShouldFlushCache(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 0));
    $this->assertSame('testing', $cache->fetch('file'));
    $cache->flushAll();
    $this->assertNull($cache->fetch('file'));
  }

  public function testShouldContainsCache(): void {
    $cache = new FileSystemCache();
    $cache->setDirectory(__DIR__ . '/../storages');
    $cache->save('file', new Element('testing', 2));
    $this->assertTrue($cache->contains('file'));
    $this->assertSame('testing', $cache->fetch('file'));
    sleep(3);
    $this->assertNull($cache->fetch('file'));
  }
}
