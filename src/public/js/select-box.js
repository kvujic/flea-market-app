document.addEventListener('DOMContentLoaded', function () {
    console.log('🔧 JS Loaded');

    const selectBoxes = document.querySelectorAll('.custom-select-box');
    console.log('📦 selectBoxes:', selectBoxes.length);


    selectBoxes.forEach(box => {
        const selected = box.querySelector('.selected');
        console.log('🎯 selected:', selected);
        const optionsContainer = box.querySelector('.options');
        const optionList = box.querySelectorAll('.option');
        const hiddenInput = box.querySelector('input[type="hidden"]');

        if (!selected || !optionsContainer || optionList.length === 0 || !hiddenInput) {
            console.warn('⛔ Missing .selected or .options element');
            return;
        }

        selected.addEventListener('click', (e) => {
            console.log('🖱️ Toggle options');
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
                    console.log('Stripe処理の準備'); // ここにStrip処理を追加する
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
