<?php

namespace Anboo\Profiler;

/**
 * Class ProfilerFactory
 */
class ProfilerFactory
{
    /** @var self */
    private static $instance;

    /** @var string */
    private $release;

    /** @var Span */
    private $lastSpan;

    /** @var Span[] */
    private $spans;

    /**
     * @return self
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $release
     */
    public function setRelease($release)
    {
        $this->release = $release;
    }

    public function start($spanName, $type = Span::TYPE_SIMPLE)
    {
        $span = new Span($spanName, $type, $this->lastSpan, $this->release);
        $this->spans[] = $span;
        $this->lastSpan = $span;
    }

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

    public function flush()
    {
        echo json_encode($this->spans);
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
}