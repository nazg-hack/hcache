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
namespace Nazg\HCache;

use Nazg\Exception\CacheProviderNameExistsException;

class CacheManager {
  
  protected string $namespace = 'NazgHCacheNamespace[%s]';

  protected Map<string, classname<CacheProvider>> $cache = Map {
    'apc' => \Nazg\HCache\Driver\ApcCache::class,
    'void' => \Nazg\HCache\Driver\VoidCache::class,
    'map' => \Nazg\HCache\Driver\MapCache::class,
  };
  
  protected Map<string, (function():CacheProvider)> $userCache = Map{};

  public function createCache(string $namedCache): ?CacheProvider {
    if($this->cache->contains($namedCache)) {
      $cache = $this->cache->at($namedCache);
      return new $cache();
    }
    if($this->userCache->contains($namedCache)) {
      $cache = $this->userCache->at($namedCache);
      return $cache();
    }
    return null;
  }

  public function addCache(
    string $name, 
    (function():CacheProvider) $cache
  ): void {
    if($this->cache->contains($name)) {
      throw new CacheProviderNameExistsException();
    }
    $this->userCache->add(Pair{$name, $cache});
  }
}
