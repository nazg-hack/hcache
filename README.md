# Nazg\HCache

[![Build Status](http://img.shields.io/travis/nazg-hack/hcache/master.svg?style=flat-square)](https://travis-ci.org/nazg-hack/hcache)

## Installation

```bash
$ hhvm -d xdebug.enable=0 -d hhvm.jit=0 -d hhvm.php7.all=1\
 -d hhvm.hack.lang.auto_typecheck=0 $(which composer) require nazg/hcache
```

## Usage

*supported apc. memcached, map, void*

### FileSystemCache

```hack
use Nazg\HCache\Element;
use Nazg\HCache\Driver\FileSystemCache;

$cache = new FileSystemCache();
$cache->setDirectory(__DIR__ . '/../storages');
$cache->save('file', new Element('testing', 0));

$cache->fetch('file');
$cache->contains('file')
$cache->flushAll();
```

### MapCache (memory)

```hack
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MapCache;

$cache = new MapCache();
$cache->save('map', new Element('testing', 0));
```

### MemcachedCache

```hack
use Nazg\HCache\Element;
use Nazg\HCache\Driver\MemcachedCache;

$cache = new MemcachedCache();
$mc = new \Memcached('mc');
$mc->addServers([['127.0.0.1', 11211]]);
$cache->setMemcached($mc);
$cache->save("qwerty", new Element('testing:cache', 0));
```

