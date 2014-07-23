<header class="main-header{{ Request::is('/') ? '' : ' static-header' }}">
    @if(!Request::is('/'))
    <a href="{{ link::to() }}" class="logo"></a>
    @else
    <div class="logo"></div>
    @endif
    <div class="header-cont">
        <nav class="main-nav">
            <ul class="list-unstyled">
                <li class="option"><a href="{{ link::to('about') }}">О компании</a>
                <li class="option"><a href="{{ link::to('auto') }}">Автомобили</a>
                <li class="option"><a href="{{ link::to('accessories') }}">Сервисы и запчасти</a>
                <li class="option"><a href="{{ link::to('offers') }}">Спецпредложения</a>
                <li class="option"><a href="{{ link::to('services') }}">Услуги</a>
            </ul>
        </nav>
        <div class="head-models">
            <div class="model">
                <div class="title">Седаны и купе</div>
                <div class="items">
                    <a href="{{ link::to('model') }}">Q50</a><a href="{{ link::to('model') }}">Q60</a><a href="{{ link::to('model') }}">M</a><a href="{{ link::to('model') }}">Q70</a>
                </div>
            </div>
            <div class="model">
                <div class="title">Кроссоверы и внедорожники</div>
                <div class="items">
                    <a href="{{ link::to('model') }}">QX50</a><a href="{{ link::to('model') }}">JX</a><a href="{{ link::to('model') }}">QX60</a><a href="{{ link::to('model') }}">QX70</a><a href="{{ link::to('model') }}">QX80</a>
                </div>
            </div>
            <div class="model">
                <div class="title">&nbsp;</div>
                <div class="items">
                    <a href="javascript:void(0);">Все модели</a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-right">
        <div class="search">
            {{ Form::open(array('url'=>link::to('search/request'),'method'=>'post')) }}
            <input type="text" placeholder="Поиск" name="search_request" class="search-input" value="{{ Input::get('query') }}">
            <button type="submit" class="search-btn"></button>
            {{ Form::close() }}
        </div>
        <a href="javascript:void(0);" class="gedon-link"></a>
    </div>
</header>