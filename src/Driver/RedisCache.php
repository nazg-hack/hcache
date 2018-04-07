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

use Redis;
use Nazg\HCache\Element;
use Nazg\HCache\CacheProvider;

class RedisCache extends CacheProvider {

  protected ?Redis $redis;

  public function setRedis(Redis $redis): void {
    $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
    $this->redis = $redis;
  }

  public function getRedis(): Redis {
    invariant(
      $this->redis instanceof Redis,
      "Type mismatch"
    );
    return $this->redis;
  }

  <<__Override>>
  public function fetch(string $id): mixed {
    $element = $this->getRedis()->get($id);
    if($element instanceof Element) {
      return $element->getData();
    }
    return;
  }

  <<__Override>>
  public function contains(string $id): bool {
    return $this->getRedis()->exists($id);
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    $lifeTime = $element->getLifetime();
    if ($lifeTime > 0) {
      return $this->getRedis()->setex($id, $lifeTime, $element);
    }
    return $this->getRedis()->set($id, $element);
  }

  <<__Override>>
  public function delete(string $id): bool {
    return $this->getRedis()->delete($id) >= 0;
  }

  <<__Override>>
  public function flushAll(): bool {
    return $this->getRedis()->flushDB();
  }
}
