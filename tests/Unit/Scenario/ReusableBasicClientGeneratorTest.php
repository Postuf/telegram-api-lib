<?php

declare(strict_types=1);

namespace Unit\Scenario;

use PHPUnit\Framework\TestCase;
use TelegramOSINT\Client\BasicClient\BasicClient;
use TelegramOSINT\Scenario\ReusableBasicClientGenerator;

class ReusableBasicClientGeneratorTest extends TestCase
{
    public function test_generate(): void
    {
        $client = (new ReusableBasicClientGenerator())->generate();
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(BasicClient::class, $client);
    }
}
