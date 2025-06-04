document.addEventListener('DOMContentLoaded', function () {
    const selectBoxes = document.querySelectorAll('.custom-select-box');

    selectBoxes.forEach(box => {
        const selected = box.querySelector('.selected');
        const optionsContainer = box.querySelector('.options');
        const optionList = box.querySelectorAll('.option');
        const hiddenInput = box.querySelector('input[type="hidden"]');
        const selectedMethodDisplay = document.getElementById('selected_method');

        if (!selected || !optionsContainer || optionList.length === 0) return;

        selected.addEventListener('click', () => {
            optionsContainer.classList.toggle('active');
        });

        optionList.forEach(option => {
            option.addEventListener('click', () => {
                optionList.forEach(o => o.classList.remove('selected'));
                option.classList.add('selected');
                selected.textContent = option.textContent;

                if (hiddenInput) hiddenInput.value = option.textContent;

                if (selectedMethodDisplay && hiddenInput.name === 'payment_method') {
                    selectedMethodDisplay.textContent = option.textContent;
                }

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
                optionsContainer.classList.remove('active');
            }
        });

    });

});
