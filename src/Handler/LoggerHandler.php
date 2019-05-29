<?php


namespace App\Handler;


use Psr\Log\LoggerInterface;

class LoggerHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function error($exception)
    {
        $details = [
            'code' => $exception->getCode(),
            'occurred' => $exception->getFile().' line: '.$exception->getLine()
//            'trace' => $exception->getTrace(),
        ];
        $this->logger->error($exception->getMessage(), $details);
    }
}