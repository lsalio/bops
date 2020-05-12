wjiec/bops
----------

An phalcon-based secondary development framework.

checks bops example: [bops-example](https://github.com/wjiec/php-bops-example)


### Installation

```bash
composer require wjiec/bops
```


### Quick Start
```php
<?php

use Bops\Bootstrap;
use Bops\Navigator\Adapter\Standard;


echo (new Bootstrap(new Standard(dirname(__DIR__))))->run();
```

#### TODO

- [ ] Correct status code(400/404/500)
