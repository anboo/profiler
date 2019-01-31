<?php

namespace Anboo\Profiler;

/**
 * Class Prof
 */
class Prof
{
    public static function release($release) {
        Profiler::get()->setRelease($release);
    }

    public static function start($spanName) {
        Profiler::get()->start($spanName);
    }

    public static function end($spanName = null) {
        Profiler::get()->end($spanName);
    }

    public static function flush() {
        Profiler::get()->flush();
    }
}