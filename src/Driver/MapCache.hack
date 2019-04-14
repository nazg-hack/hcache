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
 * Copyright (c) 2017-2018 Yuuki Takezawa
 *
 */
namespace Nazg\HCache\Driver;

use type Nazg\HCache\Element;
use type Nazg\HCache\CacheProvider;

class MapCache extends CacheProvider {

  protected Map<string, Element> $map = Map{};

  <<__Override>>
  public function fetch(string $id): mixed {
    if($this->contains($id)) {
      $element = $this->map->get($id);
      if($element instanceof Element) {
        return $element->getData();
      }
    }
    return null;
  }

  <<__Override>>
  public function contains(string $id): bool {
    $contains = $this->map->containsKey($id);
    if ($contains) {
      $element = $this->map->get($id);
      if($element instanceof Element) {
        $expiration = $element->getLifetime();
        if ($expiration && $expiration < \time()) {
          $this->delete($id);
          return false;
        }
        return true;
      }
    }
    return $contains;
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    $lifeTime = $element->getLifetime() ? \time() + $element->getLifetime() : 0;
    $this->map->add(Pair{$id, new Element($element->getData(), $lifeTime)});
    return true;
  }

  <<__Override>>
  public function delete(string $id): bool {
    $this->map->remove($id);
    return true;
  }

  <<__Override>>
  public function flushAll(): bool {
    $this->map->clear();
    return true;
  }
}
