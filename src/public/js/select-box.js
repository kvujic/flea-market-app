document.addEventListener('DOMContentLoaded', function () {

    const selectBoxes = document.querySelectorAll('.custom-select-box');

    selectBoxes.forEach(box => {
        const selected = box.querySelector('.selected');
        const optionsContainer = box.querySelector('.options');
        const optionList = box.querySelectorAll('.option');
        const hiddenInput = box.querySelector('input[type="hidden"]');

        if (!selected || !optionsContainer || optionList.length === 0 || !hiddenInput) {
            return;
        }

        selected.addEventListener('click', (e) => {
            e.stopPropagation();
            optionsContainer.classList.toggle('active');
        });

        optionList.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();

                optionList.forEach(o => o.classList.remove('selected'));
                option.classList.add('selected');

                const selectedValue = option.dataset.id;
                const selectedLabel = option.textContent;

                selected.textContent = selectedLabel;
                hiddenInput.value = selectedValue;

                optionsContainer.classList.remove('active');

                if (hiddenInput.name === 'payment_method' && option.textContent === 'カード支払い') {
                    // ここにStrip処理を追加する
                    // stripeボタンを表示・submitを有効化など
                }
            });
        });
        // click outside and close
        document.addEventListener('click', function (e) {
            if (!box.contains(e.target)) {
                console.log('📤 Clicked outside – closing');
                optionsContainer.classList.remove('active');
            }
        });
    });
});
