<?php

namespace Anboo\Profiler;

/**
 * Class Span
 */
class Span implements \JsonSerializable
{
    const TYPE_ENDPOINT = 'endpoint';
    const TYPE_SIMPLE = 'simple';

    /** @var string */
    private $id;

    /** @var string */
    private $code;

    /** @var string */
    private $type = self::TYPE_SIMPLE;

    /** @var Span */
    private $parentSpan;

    /** @var string */
    private $name;

    /** @var float */
    private $timeStart;

    /** @var float */
    private $timeEnd;

    /** @var int */
    private $memoryStart;

    /** @var int */
    private $memoryEnd;

    /** @var int */
    private $memoryUsed;

    /** @var int */
    private $memoryFree = 0;

    /** @var float */
    private $executionTime;

    /** @var string */
    private $release;

    /** @var array */
    private $stackTrace = [];

    /**
     * Span constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $parentSpan
     * @param string $release
     */
    public function __construct($name, $type, Span $parentSpan = null, $release = null)
    {
        $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $this->type = $type;
        $this->parentSpan = $parentSpan;
        $this->name = $name;
        $this->release = $release;
        $this->code = base64_encode($name);
        $this->timeStart = microtime(true);
        $this->memoryStart = memory_get_usage(true);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Span|null
     */
    public function getParentSpan()
    {
        return $this->parentSpan;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    public function end()
    {
        $this->timeEnd = microtime(true);
        $this->memoryEnd = memory_get_usage(true);
        $this->memoryUsed = $this->memoryEnd - $this->memoryStart;
        $this->executionTime = $this->timeEnd - $this->timeStart;

        if ($this->memoryUsed < 0) {
            $this->memoryFree = $this->memoryUsed * -1;
            $this->memoryUsed = 0;
        }

        $this->stackTrace = $this->getTrace();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'release' => $this->release,
            'type' => $this->type,
            'parentId' => $this->parentSpan ? $this->parentSpan->getId() : null,
            'name' => $this->name,
            'timeStart' => $this->timeStart,
            'timeEnd' => $this->timeEnd,
            'memoryStart' => $this->memoryStart,
            'memoryEnd' => $this->memoryEnd,
            'memoryUsed' => $this->memoryUsed,
            'memoryFree' => $this->memoryFree,
            'executionTime' => $this->executionTime,
            'stackTrace' => $this->stackTrace,
        ];
    }

    private function getTrace()
    {
        $exception = new \Exception();
        $stackTrace = array_reverse($exception->getTrace());

        $ignoreClasses = ['ProfilerFactory', 'Span', 'Prof'];
        foreach ($stackTrace as $k => $stack) {
            if (isset($stack['class']) && in_array($stack['class'], $ignoreClasses)) {
                unset($stackTrace[$k]);
            }
        }

        return $stackTrace;
    }
}