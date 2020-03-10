<?php

declare(strict_types=1);

namespace TelegramOSINT\Scenario;

use TelegramOSINT\Exception\TGException;
use TelegramOSINT\Logger\Logger;
use TelegramOSINT\Scenario\Models\GroupId;
use TelegramOSINT\Scenario\Models\GroupRequest;

class SearchUserScenario extends InfoClientScenario
{
    /** @var string[] */
    private $groupnames;
    /** @var string[] */
    private $groupnamesById = [];
    /** @var string */
    private $username;
    /** @var int[] */
    private $resolvedIds = [];
    /** @var int[] */
    private $resolvedHashes = [];
    /** @var int */
    private $userId;
    /** @var callable|null function(int, string) */
    private $handler;

    /**
     * @param ClientGeneratorInterface $clientGenerator
     * @param string[]                 $groupnames
     * @param string                   $username
     * @param callable|null            $handler         function(int $groupId, string $title)
     */
    public function __construct(
        ClientGeneratorInterface $clientGenerator,
        array $groupnames,
        string $username,
        ?callable $handler = null
    ) {
        parent::__construct($clientGenerator);
        $this->groupnames = $groupnames;
        $this->username = $username;
        $this->handler = $handler;
    }

    /**
     * @param bool $pollAndTerminate
     *
     * @throws TGException
     */
    public function startActions(bool $pollAndTerminate = true): void
    {
        if (!$this->groupnames) {
            return;
        }
        $onUserResolve = function (?int $userId) {
            if (!$userId) {
                Logger::log(__CLASS__, "could not resolve username {$this->username}");

                return;
            }

            $this->userId = $userId;

            foreach ($this->resolvedIds as $key => $id) {
                $accessHash = $this->resolvedHashes[$key];
                $handler = function (array $users) use ($id) {
                    foreach ($users as $user) {
                        if ($user->id == $this->userId) {
                            Logger::log(__CLASS__, "found user in group $id ({$this->groupnamesById[$id]})");
                            $handler = $this->handler;
                            if ($handler) {
                                $handler($id, $this->groupnamesById[$id]);
                            }
                        }
                    }
                };
                $messagesScenario = new GroupMembersScenario(
                    new GroupId($id, $accessHash),
                    $handler,
                    $this->getGenerator()
                );
                $messagesScenario->startActions(false);
            }
        };
        /** @var callable|null $callback */
        $callback = function () use ($onUserResolve) {

            $userResolver = new UserResolveScenario(
                $this->username,
                $onUserResolve,
                $this->getGenerator()
            );
            $userResolver->startActions(false);
        };
        /** @var GroupResolverScenario[] $resolvers */
        $resolvers = [];
        foreach ($this->groupnames as $groupName) {
            $request = GroupRequest::ofUserName($groupName);
            $handler = function ($groupId, $accessHash = null) use ($callback, $groupName) {
                if (!$groupId || !$accessHash) {
                    return;
                }
                Logger::log(__CLASS__, "got group $groupId for $groupName");
                $this->resolvedIds[] = $groupId;
                $this->groupnamesById[$groupId] = $groupName;
                $this->resolvedHashes[] = $accessHash;
                if ($callback) {
                    $callback();
                }
            };
            $resolvers[] = new GroupResolverScenario(
                $request,
                $this->getGenerator(),
                $handler
            );
            $callback = $handler;
        }
        end($resolvers);
        $resolver = current($resolvers);
        $resolver->startActions($pollAndTerminate);
    }
}
