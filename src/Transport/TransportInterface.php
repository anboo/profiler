<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 01.03.19
 * Time: 22:08
 */

namespace Anboo\Profiler\Transport;

use Anboo\Profiler\DTO\PreparedRequestData;

/**
 * Interface TransportInterface
 */
interface TransportInterface
{
    public function handle(PreparedRequestData $requestData);
}