<?php

namespace App\Tests\Unit\Filter;

use App\Entity\IsDeletedTrait;
use App\Filter\IsDeletedFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Filter\IsDeletedFilter
 * @covers ::__construct
 */
class IsDeletedFilterTest extends TestCase
{
    /** @var EntityManagerInterface|MockObject $entityManager */
    protected $entityManager;
    /** @var ClassMetadata|MockObject $classMetaData */
    protected $classMetaData;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->classMetaData = $this->createMock(ClassMetadata::class);
    }

    /**
     * @covers ::addFilterConstraint
     */
    public function testIfFilterIsAppliedToExistingEntities(): void
    {
        $this->classMetaData->expects($this->once())
            ->method('hasField')
            ->with('isDeleted')
            ->willReturn(true);

        $targetTableAlias = 'ac';

        $isDeletedFilter = new IsDeletedFilter($this->entityManager);

        $result = $isDeletedFilter->addFilterConstraint($this->classMetaData, $targetTableAlias);

        $this->assertEquals('ac.is_deleted = false', $result);
    }

    /**
     * @covers ::addFilterConstraint
     */
    public function testIfFilterIsNotAppliedToNonExistingEntities(): void
    {
        $this->classMetaData->expects($this->once())
            ->method('hasField')
            ->with('isDeleted')
            ->willReturn(false);

        $targetTableAlias = 'ac';

        $isDeletedFilter = new IsDeletedFilter($this->entityManager);

        $result = $isDeletedFilter->addFilterConstraint($this->classMetaData, $targetTableAlias);

        $this->assertEquals('', $result);
    }
}
