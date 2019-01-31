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