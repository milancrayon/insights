<?php
namespace T3element\Insights\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler; 

class ArchiveDateValidationHook
{
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        int|string $id,
        DataHandler $dataHandler
    ): void {
        if ($table !== 'tx_insights_domain_model_post') {
            return;
        }

        $publishDate = $incomingFieldArray['publishdate'] ?? null;
        $archiveDate = $incomingFieldArray['archivedate'] ?? null;

        if ($publishDate && $archiveDate && $archiveDate < $publishDate) {
            $dataHandler->log(
                $table,
                $id,
                0,
                null,
                1,
                'Archive date must not be earlier than publish date'
            );

            throw new \TYPO3\CMS\Core\Exception(
                'Archive date must not be earlier than publish date',
                1700000000
            );
        }
    }
}
