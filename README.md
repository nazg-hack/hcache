# Nazg\HCache

[![Build Status](http://img.shields.io/travis/nazg-hack/hcache/master.svg?style=flat-square)](https://travis-ci.org/nazg-hack/hcache)

## Installation

```bash
$ hhvm -d xdebug.enable=0 -d hhvm.jit=0 -d hhvm.php7.all=1\
 -d hhvm.hack.lang.auto_typecheck=0 $(which composer) require nazg/hcache
```

## Usage

*supported apc, memcached, redis, map, void, filesystem*

### Using the CacheManager

```hack
<?hh
use Nazg\HCache\Element;
use Nazg\HCache\CacheManager;

$manager = new CacheManager();
$cache = $manager->createCache('memcached');
$mc = new \Memcached('mc');
$mc->addServers([['127.0.0.1', 11211]]);
$cache->setMemcached($mc);
$cache->createCache('map')?->save('cache', new Element('testing', 0));
```

| cache name | cache driver |
|-------------------|-------------------|
| apc | \Nazg\HCache\Driver\ApcCache |
| void | \Nazg\HCache\Driver\VoidCache |
| map | \Nazg\HCache\Driver\MapCache |
| file | \Nazg\HCache\Driver\FileSystemCache |
| memcached | \Nazg\HCache\Driver\MemcachedCache |
| redis | \Nazg\HCache\Driver\RedisCache |

### Cache Usage

#### Retrieving Item From The Cache

```hack
<?hh

  <<__Override>>
  public function fetch(string $id): mixed {

  }

```

#### Checking For Item Existence

```hack
<?hh

  <<__Override>>
  public function contains(string $id): bool {

  }

```

#### Storing Item In The Cache

```hack
<?hh

  <<__Override>>
  public function save(string $id, Element $element): bool {

  }

```

#### Removing Items From The Cache

remove item
```hack
<?hh

  <<__Override>>
  public function delete(string $id): bool {

  }

```

flush

```hack
<?hh

  <<__Override>>
  public function flushAll(): bool {
  }

```

### Adding Custom Cache Drivers

```hack
<?hh

use Nazg\HCache\CacheManager;
use Nazg\HCache\CacheProvider;

$manager = new CacheManager();
$manager->addCache('null', () ==> new NullCache());
$manager->createCache('null');

class NullCache extends CacheProvider {
  <<__Override>>
  public function fetch(string $id): mixed {
    return;
  }

  <<__Override>>
  public function contains(string $id): bool {
    return false;
  }

  <<__Override>>
  public function save(string $id, Element $element): bool {
    return true;
  }

  <<__Override>>
  public function delete(string $id): bool {
    return true;
  }

  <<__Override>>
  public function flushAll(): bool {
    return true;
  }
}

```

### Using Cache Providers
example

#### FileSystemCache

```hack
<?hh
use Nazg\HCache\Element;
use Nazg\HCache\Driver\FileSystemCache;

$cache = new FileSystemCache();
$cache->setDirectory(__DIR__ . '/../storages');
$cache->save('file', new Element('testing', 0));

$cache->fetch('file');
$cache->contains('file')
$cache->flushAll();
```

#### MapCache (memory)

```hack
<?hh
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MapCache;

$cache = new MapCache();
$cache->save('map', new Element('testing', 0));
```

#### MemcachedCache

```hack
<?hh
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MemcachedCache;

$cache = new MemcachedCache();
$mc = new \Memcached('mc');
$mc->addServers([['127.0.0.1', 11211]]);
$cache->setMemcached($mc);
$cache->save("qwerty", new Element('testing:cache', 0));
```

#### RedisCache

```hack
<?hh
use Nazg\HCache\Element;
use Nazg\HCache\Driver\RedisCache;

$cache = new RedisCache();
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$cache->setRedis($redis);
$cache->save("qwerty", new Element('testing:cache', 0));
```
