<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class IsDeletedFilter extends SQLFilter
{
    /**
     * @param ClassMetadata $targetEntity
     * @param string $targetTableAlias
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->hasField('isDeleted')) {
            return '';
        }

        return "$targetTableAlias.is_deleted = false";
    }
}
