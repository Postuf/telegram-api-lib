<?php

declare(strict_types=1);

namespace Unit\TLMessage\TLMessage\ServerMessages;

use PHPUnit\Framework\TestCase;
use TelegramOSINT\Exception\TGException;
use TelegramOSINT\TLMessage\TLMessage\ServerMessages\Contact\ContactUser;

class ContactUserTest extends TestCase
{
    private function getObjectOnLine(): AnonymousMessageMock
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        return new AnonymousMessageMock([
            '_'           => 'user',
            'id'          => 438562352,
            'access_hash' => 2811936216873835544,
            'first_name'  => 'name_89169904863',
            'last_name'   => 'l_f4d6bed238',
            'username'    => 'AseN_17',
            'phone'       => '79169904855',
            'photo'       => [
                '_'           => 'userProfilePhoto',
                'photo_id'    => 806194743786710955,
                'photo_small' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141372,
                    'secret'    => 4952891847968332097,
                ],

                'photo_big' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141374,
                    'secret'    => -5785720690880313215,
                ],

            ],

            'status' => [
                '_'       => 'userStatusOnline',
                'expires' => 1533377307,
            ],
        ]);
    }

    private function getObjectOffLine(): AnonymousMessageMock
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        return new AnonymousMessageMock([
            '_'           => 'user',
            'id'          => 438562352,
            'access_hash' => 2811936216873835544,
            'first_name'  => 'name_89169904863',
            'last_name'   => 'l_f4d6bed238',
            'username'    => 'AseN_17',
            'phone'       => 79169904855,
            'photo'       => [
                '_'           => 'userProfilePhoto',
                'photo_id'    => 806194743786710955,
                'photo_small' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141372,
                    'secret'    => 4952891847968332097,
                ],

                'photo_big' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141374,
                    'secret'    => -5785720690880313215,
                ],

            ],

            'status' => [
                '_'          => 'userStatusOffline',
                'was_online' => 1533377309,
            ],
        ]);
    }

    private function getObjectHidden(): AnonymousMessageMock
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        return new AnonymousMessageMock([
            '_'           => 'user',
            'id'          => 438562352,
            'access_hash' => 2811936216873835544,
            'first_name'  => 'name_89169904863',
            'last_name'   => 'l_f4d6bed238',
            'username'    => 'AseN_17',
            'phone'       => 79169904855,
            'photo'       => [
                '_'           => 'userProfilePhoto',
                'photo_id'    => 806194743786710955,
                'photo_small' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141372,
                    'secret'    => 4952891847968332097,
                ],

                'photo_big' => [
                    '_'         => 'fileLocation',
                    'dc_id'     => 2,
                    'volume_id' => 225517222,
                    'local_id'  => 141374,
                    'secret'    => -5785720690880313215,
                ],

            ],

            'status' => [
                '_' => 'userStatusEmpty',
            ],
        ]);
    }

    /**
     * @throws TGException
     */
    public function test_correct_field_mapping(): void
    {
        $asAnonymous = $this->getObjectOnLine();
        $userContact = new ContactUser($asAnonymous);

        self::assertEquals('AseN_17', $userContact->getUsername());
        self::assertEquals('79169904855', $userContact->getPhone());
        self::assertEquals(438562352, $userContact->getUserId());
        self::assertEquals(2811936216873835544, $userContact->getAccessHash());

    }

    /**
     * @throws TGException
     */
    public function test_user_online(): void
    {
        $asAnonymous = $this->getObjectOnLine();
        $userContact = new ContactUser($asAnonymous);

        self::assertTrue($userContact->getStatus()->isOnline());
        self::assertFalse($userContact->getStatus()->isOffline());
        self::assertFalse($userContact->getStatus()->isHidden());
        self::assertEquals(1533377307, $userContact->getStatus()->getExpires());

    }

    /**
     * @throws TGException
     */
    public function test_user_offline(): void
    {
        $asAnonymous = $this->getObjectOffLine();
        $userContact = new ContactUser($asAnonymous);

        self::assertFalse($userContact->getStatus()->isOnline());
        self::assertTrue($userContact->getStatus()->isOffline());
        self::assertFalse($userContact->getStatus()->isHidden());
        self::assertEquals(1533377309, $userContact->getStatus()->getWasOnline());

    }

    /**
     * @throws TGException
     */
    public function test_user_empty(): void
    {
        $asAnonymous = $this->getObjectHidden();
        $userContact = new ContactUser($asAnonymous);

        self::assertFalse($userContact->getStatus()->isOnline());
        self::assertFalse($userContact->getStatus()->isOffline());
        self::assertTrue($userContact->getStatus()->isHidden());

    }
}
