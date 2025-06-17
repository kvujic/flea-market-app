document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('keywordInput');
    if (!input) return;

    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const keyword = input.value.trim();
            const pathname = window.location.pathname;
            const searchParams = new URLSearchParams(window.location.search);

            let redirectUrl;

            if (pathname === '/' && searchParams.get('tab') === 'mylist') {
                redirectUrl = `/?tab=mylist&keyword=${encodeURIComponent(keyword)}`;
            } else if (pathname.startsWith('/mypage') && searchParams.get('tab') === 'sell') {
                redirectUrl = `/mypage?tab=sell&keyword=${encodeURIComponent(keyword)}`;
            } else if (pathname.startsWith('/mypage') && searchParams.get('tab') === 'buy') {
                redirectUrl = `/mypage?tab=buy&keyword=${encodeURIComponent(keyword)}`;
            } else {
                redirectUrl = `/?keyword=${encodeURIComponent(keyword)}`;
            }

            window.location.href = redirectUrl;
        }
    });
});