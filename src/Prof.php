<?php

namespace Anboo\Profiler;

/**
 * Class Prof
 */
class Prof
{
    public static function release($release) {
        ProfilerFactory::get()->setRelease($release);
    }

    public static function start($spanName) {
        ProfilerFactory::get()->start($spanName);
    }

    public static function end($spanName = null) {
        ProfilerFactory::get()->end($spanName);
    }

    public static function flush() {
        ProfilerFactory::get()->flush();
    }
}