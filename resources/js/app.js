import '@fontsource/poppins/latin-400.css';
import '@fontsource/poppins/latin-500.css';
import '@fontsource/poppins/latin-600.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import $ from 'jquery';
import select2Factory from 'select2';
import 'select2/dist/css/select2.min.css';

window.$ = window.jQuery = $;

if (!$.fn.select2 && typeof select2Factory === 'function') {
    select2Factory(window, $);
}

const initializeSelect2 = () => {
    $('[data-enhanced-select]').each(function () {
        const select = $(this);

        if (select.data('select2')) {
            return;
        }

        const isAdminSelect = select.closest('.admin-page').length > 0;

        select.select2({
            width: '100%',
            minimumResultsForSearch: 6,
            placeholder: select.find('option[value=""]').text() || 'Pilih salah satu',
            dropdownCssClass: isAdminSelect ? 'admin-select2-dropdown' : '',
        });
    });
};

const setConditionalState = (target) => {
    const fieldName = target.dataset.otherTarget;
    const selectedValue = target.dataset.otherValue ?? 'Lainnya';
    const select = document.querySelector(`[data-other-select="${fieldName}"]`);
    const checkboxes = document.querySelectorAll(`[data-other-checkbox="${fieldName}"]`);
    const input = target.querySelector('input, textarea, select');

    const isVisible = select
        ? select.value === selectedValue
        : Array.from(checkboxes).some((checkbox) => checkbox.checked);

    target.hidden = !isVisible;
    target.setAttribute('aria-hidden', String(!isVisible));

    if (input) {
        input.required = isVisible;
        if (!isVisible) {
            input.value = '';
        }
    }
};

const syncConditionalFields = () => {
    document.querySelectorAll('[data-other-target]').forEach(setConditionalState);
};

const setCheckboxValidation = (form) => {
    const goalCheckboxes = form.querySelectorAll('input[name="website_goals[]"]');
    const firstGoal = goalCheckboxes[0];

    if (!firstGoal) {
        return;
    }

    const hasGoal = Array.from(goalCheckboxes).some((checkbox) => checkbox.checked);
    firstGoal.setCustomValidity(hasGoal ? '' : 'Pilih minimal satu tujuan website.');
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-submission-form]');

    initializeSelect2();
    syncConditionalFields();

    document.addEventListener('change', (event) => {
        if (
            event.target.matches('[data-other-select]') ||
            event.target.matches('[data-other-checkbox]') ||
            event.target.matches('input[name="website_goals[]"]')
        ) {
            syncConditionalFields();
        }

        if (form) {
            setCheckboxValidation(form);
        }
    });

    if (!form) {
        return;
    }

    setCheckboxValidation(form);

    form.addEventListener('submit', (event) => {
        setCheckboxValidation(form);

        if (!form.checkValidity()) {
            return;
        }

        const submitButton = form.querySelector('[data-submit-button]');

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('is-loading');
        }
    });
});
