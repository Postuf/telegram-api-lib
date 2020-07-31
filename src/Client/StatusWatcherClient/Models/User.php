<?php

namespace TelegramOSINT\Client\StatusWatcherClient\Models;

class User
{
    /** @var string|null */
    private ?string $phone;
    /** @var string|null */
    private ?string $username;
    /** @var int|null */
    private ?int $userId;

    public function __construct(?string $phone, ?string $username, ?int $userId = null)
    {
        $this->phone = $phone;
        $this->username = $username;
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
