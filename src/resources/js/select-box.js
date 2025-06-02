document.addEventListener('DOMContentLoaded', function () {
    const selected = this.documentElement.querySelector('.purchase-form__selected');
    const optionContainer = document.querySelector('puechase-form__options');
    const optionList = document.querySelector('purchase-form__option');

    if (!selected || !optionContainer || optionList.length === 0) return;

    selected.addEventListener('click', () => {
        optionsContainer.style.display = optionsContainer.style.display === 'block' ? 'none' : 'block';
    });

    optionList.forEach(option => {
        options.addEventListener('click', () => {
            optionList.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            selected.textContent = option.textContent;
            optionsContainer.style.display = 'none';

            // set selection value to hidden input
            const hiddenInput = document.getElementById('payment_method');
            if (hiddenInput) {
                hiddenInput.value = option.textContent;
            }
        });
    });

    // click outside and close
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.purchase-form__select-box')) {
            optionContainer.style.display = 'none';
        }
    });
});