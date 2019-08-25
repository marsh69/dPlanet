<?php

namespace App\Tests\Integration\Security;

use App\Security\VoteAttributeTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * @coversDefaultClass VoteAttributeTrait
 */
class VoteAttributeTraitTest extends TestCase
{
    /** @var AccessDecisionManagerInterface|MockObject $decisionManager */
    protected $decisionManager;
    /** @var $dummyClass */
    protected $dummyClass;
    /** @var TokenInterface $token */
    protected $token;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->decisionManager = $this->createMock(AccessDecisionManagerInterface::class);
        $this->token = $this->createMock(TokenInterface::class);

        $this->dummyClass = $class = new class {
            use VoteAttributeTrait;

            /**
             * @param string $attibute
             * @param $subject
             * @param TokenInterface $token
             * @return bool
             */
            public function dummyVote(string $attibute, $subject, TokenInterface $token): bool
            {
                return $this->voteOnAttribute($attibute, $subject, $token);
            }

            /**
             * @param AccessDecisionManagerInterface $decisionManager
             */
            public function setDecisionManager(AccessDecisionManagerInterface $decisionManager): void
            {
                $this->decisionManager = $decisionManager;
            }

            /**
             * @param array $permissions
             */
            public function setPermissions(array $permissions): void
            {
                $this->permissions = $permissions;
            }
        };
    }


    /**
     * @return void
     */
    public function testIfIsAdminIsCalled(): void
    {
        $this->dummyClass->setPermissions(['isAdmin' => ['list']]);
        $this->dummyClass->setDecisionManager($this->decisionManager);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(true);

        $result = $this->dummyClass->dummyVote('list', new class {
        }, $this->token);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIfIsModeratorIsCalled(): void
    {
        $this->dummyClass->setPermissions(['isModerator' => ['list']]);
        $this->dummyClass->setDecisionManager($this->decisionManager);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_MODERATOR'])
            ->willReturn(true);

        $result = $this->dummyClass->dummyVote('list', new class {
        }, $this->token);

        $this->assertTrue($result);
    }
}
