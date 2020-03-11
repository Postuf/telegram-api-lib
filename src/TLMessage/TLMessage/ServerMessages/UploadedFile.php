<?php

declare(strict_types=1);

namespace TelegramOSINT\TLMessage\TLMessage\ServerMessages;

use TelegramOSINT\MTSerialization\AnonymousMessage;
use TelegramOSINT\TLMessage\TLMessage\TLServerMessage;

class UploadedFile extends TLServerMessage
{
    const FORMAT_JPEG = 'jpg';
    const FORMAT_UNKNOWN = 'unknown';

    /**
     * @param AnonymousMessage $tlMessage
     *
     * @return bool
     */
    public static function isIt(AnonymousMessage $tlMessage): bool
    {
        return self::checkType($tlMessage, 'upload.file');
    }

    public function getBytes(): string
    {
        return $this->getTlMessage()->getValue('bytes');
    }

    public function getModificationTs(): int
    {
        return (int) $this->getTlMessage()->getValue('mtime');
    }

    public function isJpeg(): bool
    {
        return $this->getTlMessage()->getNode('type')->getType() === 'storage.fileJpeg';
    }

    public function getFormatName(): string
    {
        switch($this->getTlMessage()->getNode('type')->getType())
        {
            case 'storage.fileJpeg':
                return self::FORMAT_JPEG;
            default:
                return self::FORMAT_UNKNOWN;

        }
    }
}
