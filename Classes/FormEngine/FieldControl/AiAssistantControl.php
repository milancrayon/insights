<?php
declare(strict_types=1);

namespace T3element\Insights\FormEngine\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AiAssistantControl extends AbstractNode
{
    public function render(): array
    {
        $result = $this->initializeResultArray();
        $fieldId = (string)($this->data['parameterArray']['itemFormElID'] ?? '');
        $fieldName = (string)($this->data['parameterArray']['itemFormElName'] ?? '');
        $table = (string)($this->data['tableName'] ?? '');
        $column = (string)($this->data['fieldName'] ?? '');
        $uid = (string)($this->data['databaseRow']['uid'] ?? '');

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $ajaxUrl = (string)$uriBuilder->buildUriFromRoute('insights_ai_generate');

        $result['javaScriptModules'][] = JavaScriptModuleInstruction::create('@t3element/insights/AiAssistant.js');

        $result['iconIdentifier'] = 'actions-robot';
        $result['title'] = 'AI Assistant';
        $result['linkAttributes'] = [
            'class' => 't3js-insights-ai-assistant-trigger',
            'data-field-id' => $fieldId,
            'data-field-name' => $fieldName,
            'data-table' => $table,
            'data-column' => $column,
            'data-uid' => $uid,
            'data-ajax-url' => $ajaxUrl,
        ];
        $result['html'] = '
            <button type="button"
                class="btn btn-default t3js-insights-ai-assistant-trigger"
                data-field-id="' . htmlspecialchars($fieldId) . '"
                data-field-name="' . htmlspecialchars($fieldName) . '"
                data-table="' . htmlspecialchars($table) . '"
                data-column="' . htmlspecialchars($column) . '"
                data-uid="' . htmlspecialchars($uid) . '"
                data-ajax-url="' . htmlspecialchars($ajaxUrl) . '"
                title="AI Assistant">
                AI
            </button>
        ';

        return $result;
    }
}
