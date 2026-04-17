<?php

namespace T3element\Insights\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @extends \TYPO3\CMS\Extbase\Persistence\Repository<\T3element\Insights\Domain\Model\Category>
 */
class CategoryRepository extends Repository
{
    public function initializeObject()
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function setStorage($pid = 0)
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        if ($pid) {
            $querySettings->setStoragePageIds(array($pid));
        }
        $querySettings->setRespectSysLanguage(true); 
        $this->setDefaultQuerySettings($querySettings);
    }

    public function getCategoryTree(int $parent = 0): array
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('parentcategory', $parent)
        );
        $query->setOrderings(['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);

        $categories = $query->execute()->toArray();

        foreach ($categories as &$category) {
            $category->childs = $this->getCategoryTree($category->getUid());
        }

        return $categories;

    }

    public function getChildCategory($cat)
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('sys_category');
        $_cat = $queryBuilder->select("*")->from('sys_category')->where(
            $queryBuilder->expr()->eq(
                'parent',
                $queryBuilder->createNamedParameter($cat['uid'], Connection::PARAM_INT)
            )
        )->executeQuery()->fetchAllAssociative();
        if (sizeof($_cat)) {
            $_final = [];
            foreach ($_cat as $child) {
                $_childs = $this->getChildCategory($child);
                $child['childs'] = $_childs;
                $_final[] = $child;
            }
            return $_final;
        } else {
            return $_cat;
        }
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

    public function getAllChildUids(\T3element\Insights\Domain\Model\Category $category): array
    {
        $uids = [(int)$category->getUid()];
        
        // Ensure we find children regardless of storage folder
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('sys_category');
        
        $rows = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where($queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter($category->getUid(), Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            // Use a broad search to find the child category object
            $this->setStorage(0);
            $childCat = $this->findByUid((int)$row['uid']);
            if ($childCat) {
                $uids = array_merge($uids, $this->getAllChildUids($childCat));
            }
        }
        return array_unique($uids);
    }
}
