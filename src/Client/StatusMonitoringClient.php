<?php

namespace TelegramOSINT\Client;

interface StatusMonitoringClient extends Client
{
    /**
     * Adds single number in monitoring book
     *
     * @param string[] $numbers
     * @param callable $onComplete function(ImportResult $result)
     */
    public function addNumbers(array $numbers, callable $onComplete): void;

    /**
     * Removes single number from monitoring book
     *
     * @param string[] $numbers
     * @param callable $onComplete function()
     */
    public function delNumbers(array $numbers, callable $onComplete): void;

    /**
     * Leaves only $numbers in monitoring book, removes any other contacts
     *
     * @param string[] $numbers
     * @param callable $onComplete function(ImportResult $result)
     */
    public function reloadNumbers(array $numbers, callable $onComplete): void;

    /**
     * Adds single username in monitoring book
     *
     * @param string   $userName
     * @param callable $onComplete function(bool)
     */
    public function addUser(string $userName, callable $onComplete): void;

    /**
     * Removes single username from monitoring book
     *
     * @param string   $userName
     * @param callable $onComplete function()
     */
    public function delUser(string $userName, callable $onComplete): void;

    /**
     * Removes all contacts from monitoring book
     *
     * @param callable $onComplete function()
     *
     * @return void
     */
    public function cleanMonitoringBook(callable $onComplete): void;
}
