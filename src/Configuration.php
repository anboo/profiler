<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 31.01.19
 * Time: 15:55
 */

namespace Anboo\Profiler;

use Anboo\Profiler\Transport\AsyncCurlBatchTransport;
use Anboo\Profiler\Transport\TransportInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Configuration
 */
class Configuration
{
    /** @var string */
    private $host;

    /** @var string */
    private $projectId;

    /** @var integer */
    private $port;

    /** @var LoggerInterface */
    private $logger;

    /** @var TransportInterface */
    private $transportHandler;

    /**
     * Configuration constructor.
     *
     * @param string $host
     * @param int $port
     * @param TransportInterface $transportHandler
     * @param LoggerInterface $logger
     */
    public function __construct($host = '127.0.0.1', $port = 27889, TransportInterface $transportHandler = null, LoggerInterface $logger = null)
    {
        if ($transportHandler === null) {
            $this->transportHandler = new AsyncCurlBatchTransport();
        } else {
            $this->transportHandler = $transportHandler;
        }

        $this->host = $host;
        $this->port = $port;
        $this->logger = $logger;
    }

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

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param string $projectId
     */
    public function setProjectId(string $projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return TransportInterface
     */
    public function getTransportHandler(): TransportInterface
    {
        return $this->transportHandler;
    }
}