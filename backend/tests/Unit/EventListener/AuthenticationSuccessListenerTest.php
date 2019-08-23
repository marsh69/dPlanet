<?php

namespace App\Tests\Unit\EventListener;

use App\Entity\Developer;
use App\Entity\Image;
use App\EventListener\AuthenticationSuccessListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\EventListener\AuthenticationSuccessListener
 */
class AuthenticationSuccessListenerTest extends TestCase
{
    /**
     * @covers ::onAuthenticationSuccessResponse
     */
    public function testIfAuthenticationListenerReturnsUserProperly(): void
    {
        $event = $this->createMock(AuthenticationSuccessEvent::class);

        $image = new Image();

        $developer = new Developer();
        $developer->setUsername('username');
        $developer->setFirstName('John');
        $developer->setLastName('Doe');
        $developer->setRoles(['ROLE_TEST']);
        $developer->setProfileImage($image);

        $event->expects($this->once())
            ->method('getData')
            ->willReturn(['token' => 'testToken']);

        $event->expects($this->once())
            ->method('getUser')
            ->willReturn($developer);

        $event->expects($this->once())
            ->method('setData')
            ->with([
                'token' => 'testToken',
                'user' => [
                    'id' => null,
                    'username' => 'username',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'roles' => ['ROLE_TEST', 'ROLE_USER'],
                    'profileImage' => $image,
                    'amountOfNotifications' => 0
                ]
            ]);

        $listener = new AuthenticationSuccessListener();
        $listener->onAuthenticationSuccessResponse($event);
    }
}
