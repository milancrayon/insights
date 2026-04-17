import Modal from '@typo3/backend/modal.js';

export default class AiAssistant {
    constructor() {
        this.initialize();
    }

    initialize() {

        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('.t3js-insights-ai-assistant-trigger');
            if (trigger) {
                e.preventDefault();
                this.handleTriggerClick(trigger);
            }
        });
    }

    async handleTriggerClick(trigger) {
        const fieldId = trigger.dataset.fieldId;
        const fieldName = trigger.dataset.fieldName;
        const ajaxUrl = trigger.dataset.ajaxUrl;

        // Metadata for TBE_EDITOR.fieldChanged
        const metadata = {
            table: trigger.dataset.table,
            column: trigger.dataset.column,
            uid: trigger.dataset.uid
        };



        const escapeSelector = (str) => str.replace(/([\[\]\(\)])/g, '\\$1');

        let field = document.getElementById(fieldId);

        // Fallback 1: Look for elements with data-formengine-input-name (the visible input in TYPO3)
        if (!field && fieldName) {
            field = document.querySelector(`[data-formengine-input-name="${escapeSelector(fieldName)}"]`);
        }

        // Fallback 2: Try to find by name attribute (often the hidden field)
        if (!field && fieldName) {
            field = document.querySelector(`[name="${escapeSelector(fieldName)}"]`);
        }

        // Fallback 3: Find relative to the trigger, but avoid hidden fields if possible
        if (!field) {
            const container = trigger.closest('.form-group') || trigger.closest('.t3js-formengine-field-item') || trigger.parentElement;
            if (container) {
                // Try visible input/textarea first
                field = container.querySelector('input:not([type="hidden"]), textarea');
                // If not found, take any input/textarea
                if (!field) {
                    field = container.querySelector('input, textarea');
                }
            }
        }

        // Final check: if we found a hidden field but there's a visible companion with the same name, use that
        if (field && field.type === 'hidden' && fieldName) {
            const visibleCompanion = document.querySelector(`[data-formengine-input-name="${escapeSelector(fieldName)}"]`);
            if (visibleCompanion) {
                field = visibleCompanion;
            }
        }

        if (field) {
            // Field found
        } else {
            return;
        }

        if (!ajaxUrl) {
            return;
        }

        const content = document.createElement('div');
        content.innerHTML = this.getModalContent();

        const promptInput = content.querySelector('#ai-prompt-input');
        const generateBtn = content.querySelector('#ai-generate-btn');
        const resultsContainer = content.querySelector('#ai-results-container');

        const modal = Modal.advanced({
            title: 'AI Assistant',
            content: content,
            size: Modal.sizes.medium,
            // buttons: [
            //     {
            //         text: 'Close',
            //         active: true,
            //         btnClass: 'btn-default',
            //         name: 'close',
            //         trigger: () => modal.hideModal()
            //     }
            // ]
        });

        generateBtn.addEventListener('click', async () => {
            const promptValue = promptInput.value.trim();
            if (!promptValue) return;

            generateBtn.disabled = true;
            generateBtn.innerHTML = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Generating...';
            resultsContainer.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Thinking...</p></div>';

            try {
                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ prompt: promptValue })
                });

                const data = await response.json();
                if (data.results && data.results.length > 0) {
                    this.showResults(data.results, resultsContainer, field, modal, metadata);
                } else {
                    resultsContainer.innerHTML = `<div class="alert alert-danger">Error: ${data.error || 'No results generated. Please check your API key and prompt.'}</div>`;
                }
            } catch (error) {
                resultsContainer.innerHTML = '<div class="alert alert-danger">Failed to connect to AI service. Please try again.</div>';
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = ` <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M12 4L14 10L20 12L14 14L12 20L10 14L4 12L10 10L12 4Z" stroke="#E2E8F0" stroke-width="2" stroke-linejoin="round"/>
  <path d="M18 4L19 6L21 7L19 8L18 10L17 8L15 7L17 6L18 4Z" fill="#A78BFA"/>
  <circle cx="6" cy="18" r="1.5" fill="#A78BFA"/>
</svg>  Generate`;
            }
        });
    }

    getModalContent() {
        return `
            <div class="ai-assistant-modal p-3">
                <div class="form-group mb-3">
                    <label for="ai-prompt-input" class="form-label fw-bold">Prompt</label>
                    <textarea id="ai-prompt-input" class="form-control" rows="3" placeholder="e.g., Summarize this post into a catchy teaser..."></textarea> 
                </div>
                <div class="text-end mb-4">
                <div class="dt-buttons btn-group flex-wrap"><div class="btn-group"><button id="ai-generate-btn" class="btn btn-secondary buttons-collection " tabindex="0" aria-controls="be_table_posts" type="button"  style="--typo3-btn-line-height: unset !important;
    padding: 4px 10px !important;
    background: #2b2b2b !important;
    color: #fff;
    height: 2rem;
    padding-inline-start: .75rem;
    padding-inline-end: .75rem;
    font-weight: 500;
    font-size: .78rem;
    gap: .275rem;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    line-height: 1;
    border-radius: .375rem;
    border: 1px solid transparent;
    outline: none;
"><span> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M12 4L14 10L20 12L14 14L12 20L10 14L4 12L10 10L12 4Z" stroke="#E2E8F0" stroke-width="2" stroke-linejoin="round"/>
  <path d="M18 4L19 6L21 7L19 8L18 10L17 8L15 7L17 6L18 4Z" fill="#A78BFA"/>
  <circle cx="6" cy="18" r="1.5" fill="#A78BFA"/>
</svg> Generate</span></button></div> </div>
                   
                </div>
                <div id="ai-results-container" class="ai-results-list border rounded bg-light overflow-auto" style="max-height: 350px;">
                    <div class="text-center text-muted p-5">
                        <i class="ki-duotone ki-messages fs-1 opacity-25"></i>
                        <p class="mt-2">Generated options will appear here</p>
                    </div>
                </div>
            </div>
        `;
    }

    showResults(results, container, field, modal, metadata) {
        container.innerHTML = '';
        results.forEach((res, index) => {
            const item = document.createElement('div');
            item.className = 'ai-result-item border-bottom p-3 bg-white position-relative';
            item.style.cursor = 'pointer';
            item.innerHTML = `
                <div class="ai-result-content text-dark mb-2">${this.escapeHtml(res)}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge border text-muted fw-normal">Option ${index + 1}</span>
                    <button class="btn btn-sm btn-outline-success ai-use-btn px-3 rounded-pill" data-index="${index}">
                        
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect x="4" y="4" width="16" height="16" rx="2" stroke="#E2E8F0" stroke-width="2"/>
  <rect x="9" y="9" width="6" height="6" rx="1" stroke="#64748B" stroke-width="1.5"/>
  <circle cx="18" cy="18" r="4" fill="#A78BFA" fill-opacity="0.2">
    <animate attributeName="r" values="3;5;3" dur="1.5s" repeatCount="indefinite" />
  </circle>
  <circle cx="18" cy="18" r="3" fill="#1A1B2E" stroke="#A78BFA" stroke-width="1.5"/>
  <circle cx="18" cy="18" r="1" fill="#FFFFFF"/>
</svg>
Select
                    </button>
                </div>
            `;

            const useBtn = item.querySelector('.ai-use-btn');
            useBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                await this.insertText(res.replace(/^["'](.*)["']$/sg, '$1'), field, metadata);
                modal.hideModal();
            });

            item.addEventListener('mouseenter', () => {
                item.classList.add('bg-light');
            });
            item.addEventListener('mouseleave', () => {
                item.classList.remove('bg-light');
            });
            item.addEventListener('click', async () => {
                await this.insertText(res.replace(/^["'](.*)["']$/sg, '$1'), field, metadata);
                modal.hideModal();
            });

            container.appendChild(item);
        });
    }

    async insertText(text, field, metadata) {
        if (!field) {
            // Alert user so they know why nothing happened
            if (typeof TYPO3 !== 'undefined' && TYPO3.Notification) {
                TYPO3.Notification.error('AI Assistant', 'The target field could not be found. Please check implementation.', 5);
            }
            return;
        }



        // Check if field has classList (double safety)
        if (!field.classList) {
            return;
        }

        // Handle RTE
        const rteCustomElement = field.closest('typo3-rte-ckeditor-ckeditor5');
        const isRte = field.classList.contains('t3js-rte') || rteCustomElement;

        let contentToInsert = text;

        if (isRte) {
            // Convert plain text newlines to HTML paragraphs for proper RTE display
            // 1. Normalize line endings
            contentToInsert = text.replace(/\r\n/g, '\n').trim();
            // 2. Wrap segments separated by double newlines in <p> tags
            contentToInsert = '<p>' + contentToInsert.split(/\n\n+/).join('</p><p>') + '</p>';
            // 3. Convert single newlines to <br>
            contentToInsert = contentToInsert.replace(/\n/g, '<br>');

            let editor = null;

            // Strategy 1: Dynamic module import (Modern TYPO3 ES modules)
            try {
                const ckModule = await import('@typo3/rte-ckeditor/ckeditor5.js');
                if (ckModule) {
                    const registry = ckModule.readyInstances || ckModule.default?.readyInstances;
                    if (registry) {
                        // Try lookup by element (some versions use the element as key)
                        // Then by various potential IDs
                        editor = registry.get(field) ||
                            registry.get(field.id) ||
                            (rteCustomElement && (registry.get(rteCustomElement) || registry.get(rteCustomElement.id)));

                        if (editor) {
                            // Editor found
                        } else {
                            // No match
                        }
                    }
                }
            } catch (e) {
                // Silently fail modular import
            }

            // Strategy 2: Look for editor instance on the custom element properties
            if (!editor && rteCustomElement) {
                // Try common property names (including potential private/non-enumerable ones)
                const candidates = [
                    rteCustomElement.editor,
                    rteCustomElement._editor,
                    rteCustomElement.instance,
                    rteCustomElement.ckeditorInstance,
                    field.ckeditorInstance
                ];
                editor = candidates.find(c => c && typeof c.setData === 'function');

                if (!editor && typeof rteCustomElement.getEditor === 'function') {
                    editor = rteCustomElement.getEditor();
                }

                if (!editor) {
                    // Find all potential candidates in both Light DOM and Shadow DOM
                    const candidatesElements = [
                        ...rteCustomElement.querySelectorAll('.ck-editor__editable'),
                        ...(rteCustomElement.shadowRoot ? rteCustomElement.shadowRoot.querySelectorAll('.ck-editor__editable') : []),
                        ...rteCustomElement.querySelectorAll('*'),
                        ...(rteCustomElement.shadowRoot ? rteCustomElement.shadowRoot.querySelectorAll('*') : [])
                    ];

                    for (const el of candidatesElements) {
                        try {
                            if (el.ckeditorInstance && typeof el.ckeditorInstance.setData === 'function') {
                                editor = el.ckeditorInstance;
                                console.log('AI Assistant: Found editor via element discovery (ckeditorInstance)');
                                break;
                            }
                            if (el.editor && typeof el.editor.setData === 'function' && el !== rteCustomElement) {
                                editor = el.editor;
                                console.log('AI Assistant: Found editor via element discovery (editor property)');
                                break;
                            }
                        } catch (e) { }
                    }
                }

                if (!editor) {
                    // Diagnostic: look for ANY property that might be the editor
                    const allProps = [
                        ...Object.getOwnPropertyNames(rteCustomElement),
                        ...Object.getOwnPropertyNames(Object.getPrototypeOf(rteCustomElement))
                    ];

                    for (const prop of allProps) {
                        try {
                            const val = rteCustomElement[prop];
                            if (val && typeof val.setData === 'function' && val !== rteCustomElement) {
                                editor = val;
                                break;
                            }
                        } catch (e) { }
                    }
                }
            }

            // Strategy 3: Check global or modular registries
            if (!editor) {
                const registries = [
                    window.CKEditor5?.readyInstances,
                    window.TYPO3?.CKEditor5?.readyInstances,
                    window.TYPO3?.RTE?.Instances
                ];

                for (const registry of registries) {
                    if (registry && typeof registry.get === 'function') {
                        editor = registry.get(field.id) || (rteCustomElement && registry.get(rteCustomElement.id));
                        if (editor) {
                            break;
                        }
                    } else if (registry && typeof registry === 'object') {
                        editor = registry[field.id] || (rteCustomElement && registry[rteCustomElement.id]);
                        if (editor) {
                            break;
                        }
                    }
                }
            }

            if (editor && typeof editor.setData === 'function') {
                editor.setData(contentToInsert);
                return;
            }
        }

        // Strategy 3: Fallback for older TYPO3 versions (CKEditor 4)
        if (field.classList.contains('t3js-rte') && window.CKEDITOR && window.CKEDITOR.instances[field.id]) {
            window.CKEDITOR.instances[field.id].setData(contentToInsert);
            return;
        }

        // Standard input/textarea
        field.value = text;
        field.dispatchEvent(new Event('change'));
        field.dispatchEvent(new Event('input'));

        // Trigger TYPO3 Backend FormEngine validation
        if (typeof TBE_EDITOR !== 'undefined' && metadata.table && metadata.column) {
            TBE_EDITOR.fieldChanged(metadata.table, metadata.uid, metadata.column, field.id || field.name || '');
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize the assistant
new AiAssistant();
