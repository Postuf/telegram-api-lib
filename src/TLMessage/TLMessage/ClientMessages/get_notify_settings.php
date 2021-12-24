<?php

declare(strict_types=1);

namespace TelegramOSINT\TLMessage\TLMessage\ClientMessages;

use TelegramOSINT\TLMessage\TLMessage\Packer;
use TelegramOSINT\TLMessage\TLMessage\TLClientMessage;

/** @see https://core.telegram.org/method/account.getNotifySettings */
class get_notify_settings implements TLClientMessage
{
    public const CONSTRUCTOR = 313765169;

    private TLClientMessage $peer;

    public function __construct(TLClientMessage $peer)
    {
        $this->peer = $peer;
    }

    public function getName(): string
    {
        return 'get_notify_settings';
    }

    public function toBinary(): string
    {
        return
            Packer::packConstructor(self::CONSTRUCTOR).
            Packer::packBytes($this->peer->toBinary());
    }
}
