<?php

declare(strict_types=1);

namespace TelegramOSINT\TLMessage\TLMessage\ServerMessages\Contact;

use TelegramOSINT\Exception\TGException;
use TelegramOSINT\MTSerialization\AnonymousMessage;
use TelegramOSINT\TLMessage\TLMessage\TLServerMessage;

class CurrentContacts extends TLServerMessage
{
    /**
     * @param AnonymousMessage $tlMessage
     *
     * @return bool
     */
    public static function isIt(AnonymousMessage $tlMessage): bool
    {
        return self::checkType($tlMessage, 'contacts.contacts');
    }

    /**
     * @throws TGException
     *
     * @return ContactUser[]
     */
    public function getUsers(): array
    {
        $users = $this->getTlMessage()->getNodes('users');
        $userObjects = [];
        foreach ($users as $user) {
            $userObjects[] = new ContactUser($user);
        }

        return $userObjects;
    }
}
