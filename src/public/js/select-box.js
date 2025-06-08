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
        const selectedMethodDisplay = document.getElementById('selected_method');

        if (!selected || !optionsContainer || optionList.length === 0) return;

        selected.addEventListener('click', (e) => {
            console.log('🖱️ clicked');
            e.stopPropagation();
            optionsContainer.classList.toggle('active');
        });

        optionList.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();

                optionList.forEach(o => o.classList.remove('selected'));
                option.classList.add('selected');

                selected.textContent = option.textContent;
                if (hiddenInput) hiddenInput.value = option.dataset.id;

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
