<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-image: url("{{asset('images/main.jpg')}}");
                background-size: cover;

                color: #000;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #000000;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    {!!App\SBlog\Core\BlogApp::get_instance()->getProperty('shop_name')!!}
                </div>

                <div class="links">


                @auth

                    @if(Auth::user()->isDisabled())
                        <a href="{{ url('/') }}">Главная</a>
                    @elseif(Auth::user()->isUser())
                        <a href="{{ url('/user/index') }}" >Кабинет</a>
                        <a href="{{ url('/') }}" >Главная</a>
                    @elseif(Auth::user()->isVisitor())
                        <a href="{{ url('/') }}">Главная</a>
                    @elseif(Auth::user()->isAdministrator())
                       <a href="{{ url('/admin/index') }}">Панель Администратора</a>
                        <a href="{{ url('/') }}">Главная</a>
                    @endif


                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            Выйти
                        </a>


                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @else

                        <a href="{{ route('login') }}">Войти</a>


                    @if (Route::has('register'))

                            <a href="{{ route('register') }}" >Регистрация</a>

                    @endif
                @endauth
            <br>
                    <a href="https://zabairachnyi.com" target="blog">Blog</a>
                    <a href="https://zev-s.com" target="studio">Studio</a>
                    <a href="https://github.com/evgeniizab" target="git">GitHub</a>
                </div>

                <p>
                    a@a.ru 12345678 - to login as Administrator<br>
                    u@u.ru 12345678 - to login as User
                </p>
                @php
                //$p = App\SBlog\Core\BlogApp::get_instance()->getProperty('shop_name');
                //dd($p);
                @endphp
            </div>
        </div>
    </body>
</html>
