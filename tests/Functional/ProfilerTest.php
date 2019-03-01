<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 01.03.19
 * Time: 22:01
 */

class ProfilerTest extends \PHPUnit\Framework\TestCase
{
    public function testStartSpan()
    {
        $checkFunction = function(\Anboo\Profiler\DTO\PreparedRequestData $preparedRequestData) {
            $this->assertEquals('127.0.0.1', $preparedRequestData->getConfiguration()->getHost());

            $spansNames = array_map(function(\Anboo\Profiler\Span $span) {
                return $span->getName();
            }, $preparedRequestData->getSpans());

            $this->assertContains('start', $spansNames);
            $this->assertContains('start1', $spansNames);
            $this->assertContains('start2', $spansNames);
            $this->assertCount(3, $spansNames);

            return true;
        };

        $handler = $this->createMock(\Anboo\Profiler\Transport\TransportInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback($checkFunction))
        ;

        $configuration = new \Anboo\Profiler\Configuration(
            '127.0.0.1',
            27889,
            $handler,
            $this->createMock(\Psr\Log\LoggerInterface::class)
        );

        $profiler = \Anboo\Profiler\Profiler::get();
        $profiler->setConfiguration($configuration);

        $profiler->start('start');
            $profiler->start('start1');
                $profiler->start('start2');
                $profiler->end();
            $profiler->end('start1');
        $profiler->end();

        $profiler->flush();
    }
}