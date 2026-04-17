<?php

declare(strict_types=1);

namespace T3element\Insights\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends \TYPO3\CMS\Extbase\Persistence\Repository<\T3element\Insights\Domain\Model\Likes>
 */
class LikesRepository extends Repository
{
    public function setStorage($pid = 0)
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        if ($pid) {
            $querySettings->setStoragePageIds(array($pid));
        }
        $this->setDefaultQuerySettings($querySettings);
    }
 
    public function findByUids(array $uids)
    {
        $query = $this->createQuery();
        return $query
            ->matching(
                $query->in('uid', $uids)
            )
            ->execute();
    }
}