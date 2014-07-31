@extends(Helper::layout())

@section('style')
    {{ HTML::style('theme/css/sumoselect.css') }}
@stop

@section('content')
@include('production/views/accepts/product-menu')
<section class="accessories sect-wrapper">
    <header><h1>{{ $product->meta->first()->title }}: Аксессуары</h1></header>
    @if(!is_null($product->accessories) && count($product->accessories))
    <dl class="acc-dl">
        @foreach($product->accessories as $accessories_category_title => $accessories )
        <dt class="acc-dt"><h2>{{ $accessories_category_title }}</h2></dt>
            @foreach($accessories as $accessory )
            <dd class="acc-dd">
                <ul class="acc-ul">
                    <li class="acc-li clearfix">
                        @if(File::exists(public_path('uploads/galleries/thumbs/'.$accessory->images->name)))
                            <img class="acc-left" src="{{ asset('uploads/galleries/thumbs/'.$accessory->images->name) }}" alt="">
                        @endif
                        <div class="acc-right">
                            <h2>{{ $accessory->title }}</h2>
                            <div class="desc">
                                {{ $accessory->description }}
                            </div>
                            @if(!empty($accessory->price))
                            <div class="price">
                                {{ number_format($accessory->price,0,' ',' ') }} руб.
                            </div>
                            @endif
                            <div class="availability">
                                {{ $accessory->accessibility->title }}
                            </div>
                        </div>
                    </li>
                </ul>
                @endforeach
            </dd>
        @endforeach
    </dl>
    @endif
</section>
@stop
@section('scripts')
{{ HTML::script('theme/js/vendor/jquery.sumoselect.min.js') }}
<script>
    $('.customSelect.selectModel').SumoSelect({placeholder: 'Модель'});
    $('.customSelect.selectYear').SumoSelect({placeholder: 'Год'});
</script>
@stop