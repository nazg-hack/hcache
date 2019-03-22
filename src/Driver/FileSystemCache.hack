/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2017-2019 Yuuki Takezawa
 *
 */
namespace Nazg\HCache\Driver;

use type Nazg\HCache\Element;
use type Nazg\HCache\CacheProvider;
use type FilesystemIterator;
use type RecursiveIteratorIterator;
use type RecursiveDirectoryIterator;
use function time;

class FileSystemCache extends CacheProvider {

  protected string $directory = '';
  protected string $extension = '.cached.php';
  protected int $umask = 0002;

  public function setDirectory(string $directory): void {
    $this->directory = $directory;
  }

  public function setUnmask(int $umask): void {
    $this->umask = $umask;
  }

  <<__Override>>
  public function fetch(string $id): mixed {
    $resource = $this->fileContains($id);
    if($resource === false) {
      return;
    }
    if ($resource is string) {
      $element = $this->unserializeElement($resource);
      $expiration = $element->getLifetime();
      if ($expiration && $expiration < time()) {
        $this->delete($id);
        return;
      }
      return $element->getData();
    }
    return;
  }

  <<__Override>>
  public function contains(string $id): bool {
    $resource = $this->fileContains($id);
    if($resource === false) {
      return false;
    }
    if ($resource is string) {
      $element = $this->unserializeElement($resource);
      $expiration = $element->getLifetime();
      if ($expiration && $expiration < time()) {
        $this->delete($id);
        return false;
      }
      return true;
    }
    return false;
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    $lifeTime = $element->getLifetime();
    if ($element->getLifetime() > 0) {
      $lifeTime = time() + $element->getLifetime();
    }
    $data = \serialize(new Element($element->getData(), $lifeTime));
    $filename = $this->getFilename($id);
    return $this->writeFile($filename, $data);
  }

  protected function writeFile(string $filename, string $content) : bool {
    $filepath = \pathinfo($filename, \PATHINFO_DIRNAME);
    if (! $this->createPathIfNeeded($filepath)) {
      return false;
    }
    if (!\is_writable($filepath)) {
      return false;
    }
    $tmpFile = \tempnam($filepath, 'swap');
    @\chmod($tmpFile, 0666 & (~$this->umask));
    if (\file_put_contents($tmpFile, $content) !== false) {
      @\chmod($tmpFile, 0666 & (~$this->umask));
      if (@\rename($tmpFile, $filename)) {
        return true;
      }
      @\unlink($tmpFile);
    }
    return false;
  }

  <<__Override>>
  public function delete(string $id): bool {
    $filename = $this->getFilename($id);
    return @\unlink($filename) || ! \file_exists($filename);
  }

  <<__Override>>
  public function flushAll(): bool {
    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($this->directory, FilesystemIterator::SKIP_DOTS),
      RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach($iterator as $file) {
      $filePath = $file->getRealPath();
      if($file->isDir()) {
        @\rmdir($filePath);
      } elseif($this->isFilenameEndingWithExtension($filePath)) {
        @\unlink($filePath);
      }
    }
    return true;
  }

  protected function getFilename(string $id): string {
    $hash = \hash('sha256', $id);
    $filename = \bin2hex($id);
    if ($id === ''
      || ((\strlen($id) * 2 + $this->extensionStringLength()) > 255)
    ) {
      $filename = '_' . $hash;
    }
    return $this->directory
      . \DIRECTORY_SEPARATOR
      . \substr($hash, 0, 2)
      . \DIRECTORY_SEPARATOR
      . $filename
      . $this->extension;
  }

  private function isFilenameEndingWithExtension(string $name) : bool {
    return $this->extension === ''
     || \strrpos($name, $this->extension) === (\strlen($name) - $this->extensionStringLength());
  }

  private function createPathIfNeeded(string $path) : bool {
    if (!\is_dir($path)) {
      if (@\mkdir($path, 0777 & (~$this->umask), true) === false && ! \is_dir($path)) {
        return false;
      }
    }
    return true;
  }

  <<__Memoize>>
  protected function extensionStringLength(): int{
    return \strlen($this->extension);
  }

  <<__Memoize>>
  protected function unserializeElement(string $resource): Element {
    $element = \unserialize($resource);
    if($element instanceof Element) {
      return $element;
    }
    return new Element('', -1);
  }

  protected function fileContains(string $id): mixed {
    $filename = $this->getFilename($id);
    if (!\is_file($filename)) {
      return false;
    }
    $resource = \file_get_contents($filename);
    if ($resource === false) {
      return false;
    }
    return $resource;
  }
}
