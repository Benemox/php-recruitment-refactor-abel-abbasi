<?php

namespace App\Shared\Infrastructure\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

readonly class LoggerFactory
{
    public function __construct(
        private string $logDir = __DIR__ . '/../../../../var/log',
        private int    $level = Logger::DEBUG
    ) {}

    public function create(string $channel): LoggerInterface
    {
        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler(sprintf('%s/%s.log', $this->logDir, $channel), $this->level));

        return $logger;
    }
}
