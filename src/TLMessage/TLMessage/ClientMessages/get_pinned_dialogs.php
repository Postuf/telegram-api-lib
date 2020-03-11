<?php

declare(strict_types=1);

namespace TelegramOSINT\TLMessage\TLMessage\ClientMessages;

use TelegramOSINT\TLMessage\TLMessage\Packer;
use TelegramOSINT\TLMessage\TLMessage\TLClientMessage;

/**
 * @see https://core.telegram.org/method/messages.getPinnedDialogs
 */
class get_pinned_dialogs implements TLClientMessage
{
    const CONSTRUCTOR = -692498958; // 0xD6B94DF2

    public function getName(): string
    {
        return 'get_pinned_dialogs';
    }

    public function toBinary(): string
    {
        return
            Packer::packConstructor(self::CONSTRUCTOR).
            Packer::packInt(0);
    }
}
