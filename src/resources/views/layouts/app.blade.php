<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | COACHTECHフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="l-container">
            <div class="header__inner">
                <a class="header__logo" href="{{ route('items.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="COACHTECH">
                </a>

                @auth
                    <nav class="header_nav">
                        <form class="header__search-form"
                            action="{{ route('items.index', ['tab' => request()->query('tab')]) }}" method="GET">

                            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" class="header__search-input"
                                placeholder="なにかをお探しですか？">
                        </form>

                        <ul class="header__menu">
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="header_logout-btn">ログアウト</button>
                                </form>
                            </li>
                            <li><a class="header__link" href="{{ route('mypage.index') }}">マイページ</a></li>
                            <li><a class="header__sell-btn" href="{{ route('items.create') }}">出品</a></li>
                        </ul>
                    </nav>
                @endauth

                @guest
                    <nav class="header_nav">
                        <form class="header__search-form"
                            action="{{ route('items.index', ['tab' => request()->query('tab')]) }}" method="GET">
                            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" class="header__search-input"
                                placeholder="なにかをお探しですか？">
                        </form>

                        <ul class="header__menu">
                            <li>
                                <a class="header__link" href="{{ route('login') }}">ログイン</a>
                            </li>
                            <li><a class="header__link" href="{{ route('mypage.index') }}">マイページ</a></li>
                            <li><a class="header__sell-btn" href="{{ route('items.create') }}">出品</a></li>
                        </ul>
                    </nav>
                @endguest

            </div>
        </div>
    </header>

    <main class="l-container">
        @yield('content')
    </main>
</body>

</html>