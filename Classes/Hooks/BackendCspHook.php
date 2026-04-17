<?php
namespace T3element\Insights\Hooks;

use TYPO3\CMS\Core\Page\PageRenderer;

class BackendCspHook
{
    /**
     * @param array $csp The current CSP directives
     */
    public function __invoke(array &$csp): void
    {
        // Add blob: to frame-src without breaking other sources
        if (isset($csp['frame-src'])) {
            if (strpos($csp['frame-src'], 'blob:') === false) {
                $csp['frame-src'] .= ' blob:';
            }
        } else {
            $csp['frame-src'] = 'blob:';
        }
    }
}
