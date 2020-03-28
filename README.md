wjiec/bops
----------

An phalcon-based secondary development framework.


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
