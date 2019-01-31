<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 31.01.19
 * Time: 15:55
 */

namespace Anboo\Profiler;

use Psr\Log\LoggerInterface;

/**
 * Class Configuration
 */
class Configuration
{
    /** @var string */
    private $host = '127.0.0.1';

    /** @var integer */
    private $port = '27889';

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param string  $host
     * @param integer $port
     * @return $this
     */
    public function setConnection($host, $port)
    {
        $this->host = $host;
        $this->port = $port;

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}