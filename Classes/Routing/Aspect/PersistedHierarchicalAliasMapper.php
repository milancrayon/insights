<?php
declare(strict_types=1);

namespace T3element\Insights\Routing\Aspect;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\Aspect\PersistedMappableAspectInterface;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Routing\Aspect\AspectTrait;
use TYPO3\CMS\Core\Site\SiteLanguageAwareInterface;
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Custom Aspect for hierarchical slugs in sys_category or other tree structures.
 */
class PersistedHierarchicalAliasMapper implements PersistedMappableAspectInterface, StaticMappableAspectInterface, SiteLanguageAwareInterface
{
    use AspectTrait;
    use SiteLanguageAwareTrait;

    protected array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Build the hierarchical path from a UID.
     * e.g. UID 5 (sub-cat) -> "main-cat/sub-cat"
     */
    public function generate(string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $tableName = $this->settings['tableName'] ?? '';
        $routeFieldName = $this->settings['routeFieldName'] ?? '';
        $parentFieldName = $this->settings['parentFieldName'] ?? '';

        if (empty($tableName) || empty($routeFieldName) || empty($parentFieldName)) {
            return $value;
        }

        $path = [];
        $currentUid = (int) $value;
        /** @extensionScannerIgnoreLine */
        $languageUid = (int) $this->siteLanguage->getLanguageId();

        while ($currentUid > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $queryBuilder
                ->select($routeFieldName, $parentFieldName)
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($currentUid))
                );

            // Only add language constraint if language is 0 or positive
            if ($languageUid >= 0) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageUid))
                );
            }

            $record = $queryBuilder->executeQuery()->fetchAssociative();

            if (!$record || empty($record[$routeFieldName])) {
                break;
            }

            // Clean slug (some might have leading/trailing slashes)
            $slug = trim($record[$routeFieldName], '/');
            array_unshift($path, $slug);
            $currentUid = (int) ($record[$parentFieldName] ?? 0);
        }

        return !empty($path) ? implode('/', $path) : $value;
    }

    /**
     * Resolve the hierarchical path back to a UID.
     * e.g. "main-cat/sub-cat" -> UID 5
     */
    public function resolve(string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $tableName = $this->settings['tableName'] ?? '';
        $routeFieldName = $this->settings['routeFieldName'] ?? '';
        $parentFieldName = $this->settings['parentFieldName'] ?? '';

        if (empty($tableName) || empty($routeFieldName) || empty($parentFieldName)) {
            return $value;
        }

        $segments = explode('/', trim($value, '/'));
        $currentParentUid = 0;
        $finalUid = null;
        /** @extensionScannerIgnoreLine */
        $languageUid = (int) $this->siteLanguage->getLanguageId();

        foreach ($segments as $segment) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $queryBuilder
                ->select('uid')
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->eq($routeFieldName, $queryBuilder->createNamedParameter($segment)),
                    $queryBuilder->expr()->eq($parentFieldName, $queryBuilder->createNamedParameter($currentParentUid))
                );

            if ($languageUid >= 0) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageUid))
                );
            }

            $record = $queryBuilder->executeQuery()->fetchAssociative();

            if (!$record) {
                return null;
            }

            $currentParentUid = (int) $record['uid'];
            $finalUid = (string) $currentParentUid;
        }
        return $finalUid;
    }
}
