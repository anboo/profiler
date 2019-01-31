**Usage example**
```php
use \Anboo\Profiler\Prof;

Prof::release(date('YmdHis'));
Prof::start('request');
    Prof::start('nested 1');
        Prof::start('Array 2000000');
            $arr = [];
            for ($i = 0; $i < 2000000; $i++) {
                $arr[] = $i;
            }
        Prof::end('Array 2000000');

        Prof::start('Unset array (free memory)');
            unset($arr);
        Prof::end();

        Prof::start('SplFixedArray');
            $splFix = new \SplFixedArray(2000000);
            for ($i = 0; $i < 2000000; $i++) {
                $splFix[$i] = $i;
            }
        Prof::end('SplFixedArray');
    Prof::end();
Prof::end();
Prof::flush();
```

**Custom configuration**
```php
$logger = new \Monolog\Logger('app', [new \Monolog\Handler\StreamHandler('./log.txt')]);

$configuration = new \Anboo\Profiler\Configuration();
$configuration->setLogger($logger); //Report about problems
$configuration->setConnection('127.0.0.1', 27889);

Prof::configuration($configuration);
```