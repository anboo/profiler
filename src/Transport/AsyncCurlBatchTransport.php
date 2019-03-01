<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 01.03.19
 * Time: 22:13
 */

namespace Anboo\Profiler\Transport;

use Anboo\Profiler\DTO\PreparedRequestData;

/**
 * Class AsyncCurlBatchTransport
 */
class AsyncCurlBatchTransport implements TransportInterface
{
    public function handle(PreparedRequestData $preparedRequestData)
    {
        $spans = [];
        foreach ($preparedRequestData->getSpans() as $span) {
            $spans[] = [
                'size' => strlen(json_encode($span)),
                'span' => $span,
            ];
        }

        $chunkSpans = [];
        $k = $index = 0;
        while (!empty($spans)) {
            $size = 0;
            while ($size < 2048 && !empty($spans)) {
                if (!isset($chunkSpans[$index])) {
                    $chunkSpans[$index] = [];
                }
                $chunkSpans[$index][] = $spans[$k]['span'];
                $size += $spans[$k]['size'];
                unset($spans[$k]);
                $k += 1;
            }
            $index += 1;
        }

        foreach ($chunkSpans as $spans) {
            $payload = json_encode([
                'projectId' => $preparedRequestData->getConfiguration()->getProjectId(),
                'spans' => $spans,
            ]);

            $headers = [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($payload),
            ];

            $endpoint = $preparedRequestData->getConfiguration()->getHost() . '/api/v1.0/span/';

            $cmd = 'curl -X POST ';
            foreach ($headers as $key => $value) {
                $cmd .= '-H ' . escapeshellarg($key.': '.$value). ' ';
            }
            $cmd .= '-d ' . escapeshellarg($payload) . ' ';
            $cmd .= escapeshellarg($endpoint) . ' ';
            $cmd .= '-m 5 ';
            $cmd .= '> /dev/null 2>&1 &';

            exec($cmd, $return, $status);
        }
    }
}