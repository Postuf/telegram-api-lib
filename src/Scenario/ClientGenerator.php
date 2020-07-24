<?php

declare(strict_types=1);

namespace TelegramOSINT\Scenario;

use TelegramOSINT\Client\InfoObtainingClient\InfoClient;
use TelegramOSINT\Client\StatusWatcherClient\StatusWatcherCallbacks;
use TelegramOSINT\Client\StatusWatcherClient\StatusWatcherClient;
use TelegramOSINT\Exception\TGException;
use TelegramOSINT\LibConfig;
use TelegramOSINT\Logger\ClientDebugLogger;
use TelegramOSINT\Logger\DefaultLogger;
use TelegramOSINT\Tools\Proxy;

class ClientGenerator implements ClientGeneratorInterface
{
    /** @var string */
    private string $envName;
    /** @var Proxy|null */
    protected ?Proxy $proxy;
    /** @var ClientDebugLogger|null */
    protected $logger;

    public function __construct(string $envName = LibConfig::ENV_AUTHKEY, ?Proxy $proxy = null, ?ClientDebugLogger $logger = null)
    {
        $this->envName = $envName;
        $this->proxy = $proxy;
        $this->logger = $logger ?? new DefaultLogger();
    }

    public function getInfoClient(): InfoClient
    {
        return new InfoClient(new BasicClientGenerator($this->proxy, $this->logger));
    }

    public function getStatusWatcherClient(StatusWatcherCallbacks $callbacks): StatusWatcherClient
    {
        $clientGenerator = new BasicClientGenerator($this->proxy, $this->logger);

        return new StatusWatcherClient($callbacks, $this->logger, [], null, $clientGenerator->generate());
    }

    /**
     * @throws TGException
     *
     * @return string
     */
    public function getAuthKey(): string
    {
        $envPath = getenv($this->envName);
        if (!$envPath) {
            throw new TGException(0, "Please set {$this->envName} env var to valid key or @filename");
        }

        if (strpos($envPath, '@') === 0) {
            $envPath = substr($envPath, 1);
            if (!is_file($envPath)) {
                throw new TGException(0, "Please set {$this->envName} env var to valid key or @filename");
            }

            return trim(file_get_contents($envPath));
        }

        return trim($envPath);
    }

    public function getProxy(): ?Proxy
    {
        return $this->proxy;
    }
}
