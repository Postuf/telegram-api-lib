<?php

declare(strict_types=1);

namespace TelegramOSINT\Client\BasicClient;

use TelegramOSINT\MTSerialization\AnonymousMessage;

class TracingBasicClientImpl extends BasicClientImpl
{
    /** @var float */
    private $traceStart;
    /** @var array */
    private $traceLog = [];

    public function __construct()
    {
        parent::__construct();
        $this->traceStart = microtime(true);
    }

    public function __destruct()
    {
        parent::__destruct();

        if ($this->traceLog) {
            $encoded = json_encode([$this->traceStart, $this->traceLog], JSON_PRETTY_PRINT);
            file_put_contents(md5((string) rand()).'.txt', $encoded);
        }
    }

    protected function prePollMessage(): ?AnonymousMessage
    {
        $readMessage = parent::prePollMessage();
        if ($readMessage) {
            $this->recordTrace($readMessage);
        }

        return $readMessage;
    }

    private function recordTrace(AnonymousMessage $message): void
    {
        $this->traceLog[] = [$message->getType(), bin2hex(serialize($message)), microtime(true)];
    }
}
