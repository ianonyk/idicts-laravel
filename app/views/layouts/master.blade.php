<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

    <head>
        <meta charset="utf-8" />
        <meta name="description" content="Free Online Vietnamese Dictionary - Hệ thống từ điển iDicts mở Anh, Việt Pháp, Nhật ">
        <title>
        @section('title')
        Từ điển iDicts
        @show
        </title>
        <!-- CSS are placed here-->
        {{ HTML::style('css/bootstrap.min.css')}}
        {{ HTML::style('css/font-awesome.min.css')}}
        {{ HTML::style('css/main.css')}}</head>

    <body>
        <div id="wrapper">
            <a href="http://www.idicts.com"><img id="banner" src="http://www.idicts.com/img/logo.png" alt="Từ điển iDicts"></a>
            <div id="jplayer"></div>
            <form class="form-search" id="index_form">{{ Form::select("dict-selects", array("anh-viet" => "Anh - Việt", "viet-anh"
                => "Việt - Anh"), $selectDict, array( "id" => "dict-selects","class"=>"dropdown") ) }}
                <span>
                    <input type="text" placeholder="Nhập từ cần tra" class="input-large" id="search_word"
                    autofocus="autofocus" speech x-webkit-speech>
                </span>
                <button type="submit" id="search-btn" class="btn btn-sm btn-primary">Tra từ</button>
            </form>
            <div class="well">
                @yield('content')
            </div>

        <div class="footer">
            <div class="menu-bottom">
                <a href="#">Giới thiệu</a>
                <a href="#">Liên hệ</a>
                <a href="#">Điều khoản sử dụng</a>
                <a href="#">Chính sách bảo mật</a>
            </div>
            <p class="copyright">&copy; 2013 by
                <a href="http://www.idicts.com/">iDicts.com</a>
            </p>
        </div>
        </div>

        <!-- Scripts are placed here -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        {{ HTML::script('js/bootstrap.min.js') }}
        {{ HTML::script('js/jquery.jplayer.min.js')}}
        {{ HTML::script('js/idicts.js') }}
        <!-- End Scripts -->
    </body>

</html>