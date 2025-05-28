<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')

</head>

<body>
    <header class="header">
        <div class="header-inner">
            <a href="/" class="header-logo__link">
                <img class="header-logo" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </a>
            @if (!Request::is('register') && !Request::is('login')) {{--&& !Request::is('verification.notice')メール認証作成後に追加する--}}
            <div class="search-form">
                <form action="/" class="search-form__form" method="GET">
                    @csrf
                    <input type="text" class="search-form__input" name="keyword" placeholder="何をお探しですか？" value="{{ request('keyword') }}">
                </form>
            </div>
            <div class="header-nav">
                <ul class="header-nav__inner">
                    @if(Auth::check())
                    <li class="header-nav__item">
                        <form class="header-nav__logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="header-nav__link logout">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li class="header-nav__item">
                        <a href="{{ route('login') }}" class="header-nav__link login">ログイン</a>
                    </li>
                    @endif

                    <li class="header-nav__item">
                        <a href="{{ route('mypage') }}" class="header-nav__link mypage">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a href="{{ route('sell') }}" class="header-nav__link exhibit">出品</a>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>