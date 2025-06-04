document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('keywordInput');
    if (!input) return;

    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const keyword = input.value.trim();
            const currentUrl = new URL(window.location.href);
            const params = new URLSearchParams(currentUrl.search);

            if (keyword) {
                params.set('keyword', keyword);
            } else {
                params.delete('keyword');
            }

            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        }
    });
});