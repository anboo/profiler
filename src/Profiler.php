<?php

namespace Anboo\Profiler;

/**
 * Class ProfilerFactory
 */
class Profiler
{
    /** @var self */
    private static $instance;

    /** @var string */
    private $release;

    /** @var Configuration */
    private $configuration;

    /** @var Span */
    private $lastSpan;

    /** @var Span[] */
    private $spans;

    private function __construct()
    {
    }

    /**
     * @return self
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        if (!self::$instance->configuration) {
            self::$instance->configuration = new Configuration();
        }

        return self::$instance;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $release
     */
    public function setRelease($release)
    {
        $this->release = $release;
    }

    /**
     * @param string $spanName
     * @param string $type
     */
    public function start($spanName, $type = Span::TYPE_SIMPLE)
    {
        $span = new Span($spanName, $type, $this->lastSpan, $this->release);
        $this->spans[] = $span;
        $this->lastSpan = $span;
    }

    /**
     * @param string|null $spanName
     */
    public function end($spanName = null)
    {
        $span = $spanName ? $this->getSpan($spanName) : $this->lastSpan;
        if (!$span) {
            throw new \RuntimeException(sprintf('Span %s not started', $spanName));
        }

        $span->end();

        if ($this->lastSpan && $this->lastSpan->getParentSpan()) {
            $this->lastSpan = $this->lastSpan->getParentSpan();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $payload = json_encode($this->spans);
        echo $payload;
        $timeout = 100;

        $resource = @fsockopen('127.0.0.1', 27889, $errno, $errStr, $timeout);
        if (!$resource) {
            $this->reportProblem($errStr);
            return;
        }

        @stream_set_blocking($resource, 0);
        @fwrite($resource, $payload);
        @fclose($resource);

        if ($error = error_get_last()) {
            $errorMsg = sprintf('Type: %s Message: %s, File: %s, Line: %s', $error['type'], $error['message'], $error['file'], $error['line']);
            $this->reportProblem($errorMsg);
        }
    }

    /**
     * @param $spanName
     * @return Span|null
     */
    private function getSpan($spanName)
    {
        foreach ($this->spans as $span) {
            if ($span->getCode() == base64_encode($spanName)) {
                return $span;
            }
        }

        return null;
    }

    /**
     * @param string $msg
     */
    private function reportProblem($msg)
    {
        if ($this->configuration->getLogger()) {
            $this->configuration->getLogger()->error($msg);
        } else {
            error_log($msg);
        }
    }
}