<?php

declare(strict_types=1);

namespace Unit\TLMessage\TLMessage\ServerMessages;

use JsonException;
use TelegramOSINT\Exception\TGException;
use TelegramOSINT\MTSerialization\AnonymousMessage;
use TelegramOSINT\MTSerialization\OwnImplementation\OwnAnonymousMessage;

class AnonymousMessageMock implements AnonymousMessage
{
    /**
     * @var AnonymousMessage
     */
    private $impl;

    /**
     * @param array $message
     */
    public function __construct(array $message)
    {
        $this->impl = new OwnAnonymousMessage($message);
    }

    /**
     * Return named node from current object
     *
     * @param string $name
     *
     * @throws TGException
     *
     * @return AnonymousMessage
     */
    public function getNode(string $name): AnonymousMessage
    {
        return $this->impl->getNode($name);
    }

    /**
     * Return array of nodes under the $name from current object
     *
     * @param string $name
     *
     * @throws TGException
     *
     * @return AnonymousMessage[]
     */
    public function getNodes(string $name): array
    {
        return $this->impl->getNodes($name);
    }

    /**
     * Return array of scalars under the $name from current object
     *
     * @param string $name
     *
     * @throws TGException
     *
     * @return array
     */
    public function getScalars(string $name): array
    {
        return $this->impl->getScalars($name);
    }

    /**
     * Get message name
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->impl->getType();
    }

    /**
     * Get value of named field from current object
     *
     * @param string $name
     *
     * @throws TGException
     *
     * @return int|string|array
     */
    public function getValue(string $name)
    {
        return $this->impl->getValue($name);
    }

    /**
     * @throws JsonException
     *
     * @return string
     */
    public function getPrintable(): string
    {
        return $this->impl->getPrintable();
    }

    /**
     * @return string
     */
    public function getDebugPrintable(): string
    {
        return $this->impl->getDebugPrintable();
    }

    public function hasNode(string $name): bool
    {
        return true;
    }
}
