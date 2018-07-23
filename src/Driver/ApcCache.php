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

use type Nazg\HCache\Element;
use type Nazg\HCache\CacheProvider;

class ApcCache extends CacheProvider {

  <<__Override>>
  public function fetch(string $id): mixed {
    return \apc_fetch($id);
  }

  <<__Override>>
  public function contains(string $id): bool {
    return \apc_exists($id);
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    return \apc_store($id, $element->getData(), $element->getLifetime());
  }

  <<__Override>>
  public function delete(string $id): bool {
    return \apc_delete($id);
  }

  <<__Override>>
  public function flushAll(): bool {
    return \apc_clear_cache() && \apc_clear_cache('user');
  }
}
