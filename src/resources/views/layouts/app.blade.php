<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css')">
    @yield('css')

</head>
<body>
    <header class="header">
        <a href="/" class="header-logo__link">
        <!--素材が手に入ったらimageに置き換える -->
            <h1 class=" header-logo">COACHTECH</h1>
        </a>
        <div class="search-form">
            <form action="/" class="search-form__form" method="GET">
                <input type="text" class="search-form__input" name="keyword" placeholder="何をお探しですか？" value="{{ request('keyword') }}">
            </form>
        </div>
        <div class="header-nav">
            <a href="/" class="header-nav__logout">ログアウト</a>
            <a href="/login" class="header-nav__login">ログイン</a>
            <a href="/mypage" class="header-nav__mypage">マイページ</a>
            <a href="/sell" class="header-nav__exhibit">出品</a>
        </div>
    </header>


    </body>

</html>