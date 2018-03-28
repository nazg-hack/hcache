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

use Nazg\HCache\CacheProvider;

class ApcCache extends CacheProvider {

  public function fetch(string $id): mixed {
    return \apc_fetch($id);
  }

  public function contains(string $id): bool {
    return \apc_exists($id);
  }

  public function save(string $id, mixed $data, int $lifeTime = 0): bool {
    return \apc_store($id, $data, $lifeTime);
  }

  public function delete(string $id): bool {
    return \apc_delete($id);
  }

  public function flushAll(): bool {
    return \apc_clear_cache() && \apc_clear_cache('user');
  }
}
