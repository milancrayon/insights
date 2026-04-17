<?php

declare(strict_types=1);

namespace T3element\Insights\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Context\Context;
use T3element\Insights\Domain\Model\Category;

/**
 * @extends \TYPO3\CMS\Extbase\Persistence\Repository<\T3element\Insights\Domain\Model\Post>
 */
class PostRepository extends Repository
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

    /**
     * @param string $slug
     * @return \T3element\Insights\Domain\Model\Post|null
     */
    public function findOneBySlug(string $slug): ?\T3element\Insights\Domain\Model\Post
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->matching($query->equals('slug', $slug))->execute()->getFirst();
    }

    public function findByCategoryAndTags(
        int $categoryUid = null,
        array $tagUids = [],
        string $search = null,
        string $orderBy = 'crdate',
        string $orderDirection = QueryInterface::ORDER_DESCENDING
    ) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $languageUid = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Context::class)
            ->getPropertyFromAspect('language', 'id');

        $constraints = [];
        $constraints[] = $query->equals('poststatus', 1);

        if ($categoryUid) {
            $constraints[] = $query->contains('category', $categoryUid);
        }
        if (!empty($tagUids)) {
            $constraints[] = $query->in('tags.uid', $tagUids);
        }

        if ($search) {
            $searchConstraint = $query->logicalOr(
                $query->like('title', '%' . $search . '%'),
                $query->like('description', '%' . $search . '%'),
                $query->like('metakeyword', '%' . $search . '%'),
                $query->like('metadescription', '%' . $search . '%'),
                $query->like('teaser', '%' . $search . '%'),
                $query->like('alternativetitle', '%' . $search . '%')
            );
            $constraints[] = $searchConstraint;
        }

        $query->matching(
            $query->logicalAnd(...$constraints)
        );
        $orderDirection = strtolower($orderDirection);

        $direction = ($orderDirection === 'asc')
            ? QueryInterface::ORDER_ASCENDING
            : QueryInterface::ORDER_DESCENDING;

        $query->setOrderings([
            $orderBy => $direction,
        ]);
        return $query->execute();
    }

    public function findByCategory(Category $category, int $limit = 0)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->contains('category', $category),
                $query->equals('poststatus', 1)
            )
        );

        $query->setOrderings([
            'uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
        ]);

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    public function fetchpopular()
    {

        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $query->matching(
            $query->equals('poststatus', 1)
        );
        $query->setOrderings([
            'viewers' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
        ]);
        $query->setLimit(3);
        return $query->execute();
    }
    /**
     * @param array $categoryUids
     * @param string $orderBy
     * @param string $orderDirection
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByCategories(
        array $categoryUids,
        string $orderBy = 'uid',
        string $orderDirection = QueryInterface::ORDER_DESCENDING
    ) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        // 1. Constraints
        $constraints = [];
        foreach ($categoryUids as $uid) {
            $constraints[] = $query->contains('category', (int)$uid);
        }

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd(
                    $query->logicalOr(...$constraints),
                    $query->equals('poststatus', 1)
                )
            );
        } else {
             $query->matching($query->equals('poststatus', 1));
        }

        // 2. Ordering
        $query->setOrderings([
            $orderBy => $orderDirection
        ]);

        return $query->execute();
    }
}