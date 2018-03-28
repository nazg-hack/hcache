<?hh // strict

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

use Nazg\HCache\Element;
use Nazg\HCache\CacheProvider;

class MapCache extends CacheProvider {

  protected Map<string, Element> $map = Map{};

  public function fetch(string $id): mixed {
    if($this->contains($id)) {
      $element = $this->map->get($id);
      return $element?->getData();
    }
    return;
  }

  public function contains(string $id): bool {
    return $this->map->containsKey($id);
  }

  public function save(string $id, mixed $data, int $lifeTime = 0): bool {
    $this->map->add(Pair{$id, new Element($data, $lifeTime)});
    return true;
  }

  public function delete(string $id): bool {
    $this->map->remove($id);
    return true;
  }

  public function flushAll(): bool {
    $this->map->clear();
    return true;
  }
}
