@extends('layouts.app')

@section('content')
<link href="{{ asset('css/svgplaces.css') }}" rel="stylesheet">
<div id="content" class="container">
    <ul id="contentul">
        <li class="svgplace">
            <img src={{asset('images/audio.svg')}} width="40" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/rings.svg')}} width="60" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/grid.svg')}} width="40" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/hearts.svg')}} width="80" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/oval.svg')}} width="50" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/three-dots.svg')}} width="60" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/spinning-circles.svg')}} width="50" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/puff.svg')}} width="50" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/circles.svg')}} width="50" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/tail-spin.svg')}} width="50" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/bars.svg')}} width="40" alt="">
        </li>
        <li class="svgplace">
            <img src={{asset('images/ball-triangle.svg')}} width="50" alt="">
        </li>
        <div style="clear: both"></div>
    </ul>
</div>
@endsection
