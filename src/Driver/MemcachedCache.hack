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

use type Memcached;
use type Nazg\HCache\Element;
use type Nazg\HCache\CacheProvider;
use function time;

class MemcachedCache extends CacheProvider {

  protected ?Memcached $memcached;

  public function setMemcached(Memcached $memcached): void {
    $this->memcached = $memcached;
  }

  public function getMemcached(): Memcached  {
    invariant(
      $this->memcached instanceof Memcached,
      "Type mismatch"
    );
    return $this->memcached;
  }

  <<__Override>>
  public function fetch(string $id): mixed {
    $element = $this->getMemcached()->get($id);
    if($element instanceof Element) {
      return $element->getData();
    }
    return;
  }

  <<__Override>>
  public function contains(string $id): bool {
    $memcached = $this->getMemcached();
    $memcached->get($id);
    return $memcached->getResultCode() === Memcached::RES_SUCCESS;
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    $lifeTime = $element->getLifetime();
    if ($element->getLifetime() > 30 * 24 * 3600) {
      $lifeTime = time() + $element->getLifetime();
    }
    return $this->getMemcached()->set($id, $element, (int) $lifeTime);
  }

  <<__Override>>
  public function delete(string $id): bool {
    $memcached = $this->getMemcached();
    return $memcached->delete($id)
      || $memcached->getResultCode() === Memcached::RES_NOTFOUND;
  }

  <<__Override>>
  public function flushAll(): bool {
    return $this->getMemcached()->flush();
  }
}
