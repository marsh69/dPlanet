<?php

namespace App\Tests\Integration\Security;

use App\Entity\Developer;
use App\Entity\Like;
use App\Security\LikeVoter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * @coversDefaultClass \App\Security\LikeVoter
 * @covers ::__construct
 */
class LikeVoterTest extends TestCase
{
    /** @var AccessDecisionManagerInterface|MockObject $decisionManager */
    protected $decisionManager;
    /** @var TokenInterface|MockObject $token */
    protected $token;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->decisionManager = $this->createMock(AccessDecisionManagerInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
    }

    /**
     * @covers ::voteOnAttribute
     */
    public function testIfAdminsCanRemoveLikes(): void
    {
        $likeVoter = new LikeVoter($this->decisionManager);

        $admin = new Developer();
        $developer = new Developer();
        $like = (new Like())
            ->setDeveloper($developer);

        $this->token->expects($this->once())
            ->method('getUser')
            ->willReturn($admin);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(true);

        $decision = $likeVoter->vote($this->token, $like, [LikeVoter::DELETE]);

        $this->assertEquals(1, $decision);
    }

    /**
     * @covers ::voteOnAttribute
     */
    public function testIfUserCanRemoveTheirLike(): void
    {
        $likeVoter = new LikeVoter($this->decisionManager);

        $developer = new Developer();
        $like = (new Like())
            ->setDeveloper($developer);

        $this->token->expects($this->once())
            ->method('getUser')
            ->willReturn($developer);

        $this->decisionManager->expects($this->never())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(false);

        $decision = $likeVoter->vote($this->token, $like, [LikeVoter::DELETE]);

        $this->assertEquals(1, $decision);
    }

    /**
     * @covers ::voteOnAttribute
     */
    public function testIfUserCanRemoveNotOtherPeoplesLikes(): void
    {
        $likeVoter = new LikeVoter($this->decisionManager);

        $developer = new Developer();
        $otherDeveloper = new Developer();
        $like = (new Like())
            ->setDeveloper($otherDeveloper);

        $this->token->expects($this->once())
            ->method('getUser')
            ->willReturn($developer);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(false);

        $decision = $likeVoter->vote($this->token, $like, [LikeVoter::DELETE]);

        $this->assertEquals(-1, $decision);
    }

    /**
     * @covers ::voteOnAttribute
     */
    public function testIfAdminsCanListLikes(): void
    {
        $likeVoter = new LikeVoter($this->decisionManager);

        $developer = new Developer();
        $like = (new Like())
            ->setDeveloper($developer);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(true);

        $decision = $likeVoter->vote($this->token, $like, [LikeVoter::LIST]);

        $this->assertEquals(1, $decision);
    }

    /**
     * @covers ::voteOnAttribute
     */
    public function testIfNormalUserCanNotListLikes(): void
    {
        $likeVoter = new LikeVoter($this->decisionManager);

        $developer = new Developer();
        $like = (new Like())
            ->setDeveloper($developer);

        $this->decisionManager->expects($this->once())
            ->method('decide')
            ->with($this->token, ['ROLE_ADMIN'])
            ->willReturn(false);

        $decision = $likeVoter->vote($this->token, $like, [LikeVoter::LIST]);

        $this->assertEquals(-1, $decision);
    }
}
