<?php

namespace TelegramOSINT\TLMessage\TLMessage\ClientMessages\Shared;

use TelegramOSINT\TLMessage\TLMessage\Packer;
use TelegramOSINT\TLMessage\TLMessage\TLClientMessage;

/**
 * @see https://core.telegram.org/constructor/inputFileLocation
 */
class input_file_location implements TLClientMessage
{
    const CONSTRUCTOR = -539317279; // 0xDFDAABE1

    /**
     * @var int
     */
    private $volumeId;
    /**
     * @var int
     */
    private $localId;
    /**
     * @var int
     */
    private $secret;
    /**
     * @var string
     */
    private $reference;

    /**
     * @param int    $volumeId
     * @param int    $localId
     * @param int    $secret
     * @param string $reference
     */
    public function __construct($volumeId, $localId, $secret, $reference)
    {
        $this->volumeId = $volumeId;
        $this->localId = $localId;
        $this->secret = $secret;
        $this->reference = $reference;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'input_file_location';
    }

    /**
     * @return string
     */
    public function toBinary()
    {
        return
            Packer::packConstructor(self::CONSTRUCTOR).
            Packer::packLong($this->volumeId).
            Packer::packInt($this->localId).
            Packer::packLong($this->secret).
            Packer::packString($this->reference);
    }
}
