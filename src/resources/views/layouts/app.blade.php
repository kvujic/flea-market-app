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
    @livewireStyles
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <a href="{{ route('item.index') }}" class="header-logo__link">
                <img class="header-logo" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </a>
            @if (!Request::is('register') && !Request::is('login') && !Request::is('email/verify'))
            <div class="search-form">
                <div class="search-form__form">
                    @csrf
                    <input type="text" id="keywordInput" class="search-form__input" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="header-nav">
                <ul class="header-nav__inner">
                    @if(Auth::check())
                    <li class="header-nav__item">
                        <a href="#" id="logout-link" class="header-nav__link logout">ログアウト</a>
                        <form id="logout-form" class="header-nav__logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </li>
                    @else
                    <li class="header-nav__item">
                        <a href="{{ route('login') }}" class="header-nav__link login">ログイン</a>
                    </li>
                    @endif
                    <li class="header-nav__item">
                        <a href="{{ route('profile.index') }}" class="header-nav__link mypage">マイページ</a>
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
        @yield('js')
    </main>

    @livewireScripts
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/logout.js') }}"></script>
</body>

</html>