<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 01.03.19
 * Time: 22:11
 */

namespace Anboo\Profiler\DTO;

use Anboo\Profiler\Configuration;
use Anboo\Profiler\Span;

/**
 * Class PreparedRequestData
 */
class PreparedRequestData
{
    /** @var Configuration */
    private $configuration;

    /** @var Span[] */
    private $spans = [];

    /**
     * PreparedRequestData constructor.
     * @param Configuration $configuration
     * @param Span[] $spans
     */
    public function __construct(Configuration $configuration, array $spans = [])
    {
        foreach ($this->spans as $span) {
            if (!$span instanceof $span) {
                throw new \RuntimeException('Span must be contain '.Span::class);
            }
        }

        $this->configuration = $configuration;
        $this->spans = $spans;
    }

    /**
     * @return Span[]
     */
    public function getSpans(): array
    {
        return $this->spans;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }
}