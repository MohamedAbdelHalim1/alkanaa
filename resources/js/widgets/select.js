import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

/**
 * Replaces the AIZ searchable-select widget (`aiz-selectpicker` class /
 * select2-style dropdown). Registered as an Alpine directive so it's
 * opt-in per <select>, progressively enhancing the existing element rather
 * than requiring new markup:
 *
 *   <select x-searchable-select name="verification_status">...</select>
 *   <select x-searchable-select="{ maxItems: 5 }" multiple>...</select>
 */
export function registerSearchableSelectDirective(Alpine) {
    Alpine.directive('searchable-select', (el, { expression }) => {
        const options = expression ? Alpine.evaluate(el, expression) : {};
        new TomSelect(el, {
            placeholder: window.appStrings?.nothing_selected ?? 'Nothing selected',
            render: {
                no_results: () => `<div class="no-results">${window.appStrings?.nothing_found ?? 'Nothing found'}</div>`,
            },
            ...options,
        });
    });
}
