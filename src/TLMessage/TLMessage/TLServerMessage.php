<?php

namespace TelegramOSINT\TLMessage\TLMessage;

use TelegramOSINT\Exception\TGException;
use TelegramOSINT\MTSerialization\AnonymousMessage;

abstract class TLServerMessage
{
    /**
     * @var AnonymousMessage
     */
    private $tlMessage;

    /**
     * TLServerMessage constructor.
     *
     * @param AnonymousMessage $tlMessage
     *
     * @throws TGException
     */
    public function __construct(AnonymousMessage $tlMessage)
    {
        $this->throwIfIncorrectType($tlMessage);
        $this->tlMessage = $tlMessage;
    }

    /**
     * @param AnonymousMessage $tlMessage
     *
     * @return bool
     */
    abstract public static function isIt(AnonymousMessage $tlMessage);

    /**
     * @param AnonymousMessage $anonymousMessage
     * @param string           $type
     *
     * @return bool
     */
    protected static function checkType(AnonymousMessage $anonymousMessage, string $type)
    {
        return $anonymousMessage->getType() == $type;
    }

    /**
     * @param AnonymousMessage $anonymousMessage
     *
     * @throws TGException
     */
    protected function throwIfIncorrectType(AnonymousMessage $anonymousMessage)
    {
        if(!static::isIt($anonymousMessage)) {
            $msg = $anonymousMessage->getType().' instead of '.get_called_class().' class';

            throw new TGException(TGException::ERR_TL_MESSAGE_UNEXPECTED_OBJECT, $msg);
        }
    }

    /**
     * @return AnonymousMessage
     */
    protected function getTlMessage(): AnonymousMessage
    {
        return $this->tlMessage;
    }
}
