@extends('front.layouts.app',['page'=>'Shop'])


@section('content')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0"><a href="{{ route('home') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                    href="{{ route('shop') }}">Shop</a> <span class="mx-2 mb-0">/</span> <strong
                    class="text-black">{{ $product->name }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-6 products">
                <div class="text-center item mt-5 mb-5 p-5">
                    @if ($product->new_price)
                    <span class="tag">Sale</span>
                    @elseif ($product->sold == $product->qty)
                    <span class="tag bg-danger">Sold</span>
                    @endif
                    <img src="{{ asset('asset').'/'.$product->category.'/single/'.$product->img }}" alt="Image"
                        class="img-fluid">
                </div>
            </div>
            <form action="{{ route('cart.add', $product->id) }}" method="post" class="col-md-6">
                @csrf
                <h2 class="text-black">{{ $product->name }}</h2>
                {!! $product->desc !!}
                <p><strong class="text-primary h4">${{ $product->price }}</strong></p>
                <div class="mb-1 d-flex">
                    @foreach ($product->tags as $color)
                    <div class="custom-control custom-radio d-flex mr-3 mb-3">
                        <input type="radio" id="customRadio{{ $color->name }}" name="color" class="custom-control-input"
                            value="{{ $color->name }}" required>
                        <label class="custom-control-label"
                            for="customRadio{{ $color->name }}">{{ $color->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="mb-5">
                    <div class="input-group mb-3" style="max-width: 120px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                        </div>
                        <input type="text" class="form-control text-center" value="1" placeholder=""
                            aria-label="Example text with button addon" aria-describedby="button-addon1" name="qty">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                        </div>
                    </div>

                </div>
                <p><input type="submit" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary"
                        value="Add To Cart">
                </p>
            </form>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">More Products</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 block-3 products-wrap">
                <div class="nonloop-block-3 owl-carousel">

                    @foreach ($random as $product)
                    <div class="product">
                        <a href="{{ route('shop.detail', $product->id) }}" class="item">
                            @if ($product->new_price)
                            <span class="tag">Sale</span>
                            @elseif ($product->sold == $product->qty)
                            <span class="tag bg-danger">Sold</span>
                            @endif
                            <img src="{{ asset('asset').'/'.$product->category.'/thumbnail/'.$product->img }}"
                                alt="Image" class="img-fluid">
                            <div class="item-info">
                                <h3>{{ $product->name }}</h3>
                                <span class="collection d-block">Summer Collection</span>
                                @if ($product->new_price)
                                <strong class="price">${{ $product->new_price }}
                                    <del>${{ $product->price }}</del></strong>
                                @else
                                <strong class="price">${{ $product->price }}</strong>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
