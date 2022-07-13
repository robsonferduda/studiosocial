@extends('layouts.relatorio-postagens')
@section('content')
    <style>

        body {
            color: #66615b;
            font-family: Montserrat, "Helvetica Neue", Arial, sans-serif;            
        }

        a {          
            text-decoration: none;
            background-color: transparent;
        }

        a, a:focus, a:hover {
            color: #51cbce;
        }

        .op-2{
            opacity: 0.3;
        }

        .text-danger, a.text-danger:focus, a.text-danger:hover {
            color: #ef8157!important;
        }

        .text-info, a.text-info:focus, a.text-info:hover {
            color: #51bcda!important;
        }

        .text-facebook {
            color: #3f51b5;
        }

        .text-pink {
            color: #e91ea1;
        }

        .mb-2 {
            margin-bottom: .5rem!important;
        }

        .float-right {
            float: right!important;
        }

        .badge-primary {
            border-color: #51cbce;
            background-color: #51cbce;
        }

        .badge-primary {
            color: #fff;
            background-color: #007bff;
        }

        .text-primary, a.text-primary:focus, a.text-primary:hover {
            color: #51cbce!important;
        }

        .text-white {
            color: #fff!important;
        }
        .badge-warning {
            color: #212529;
            background-color: #ffc107;
        }

        .badge-pill {
            padding-right: .6em;
            padding-left: .6em;
            border-radius: 10rem;
        }

        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        span.emoji-sizer {
            line-height: 1.013em;
            font-size: 1.375em;
            margin: -0.05em 0;
        }

        span.emoji-outer {
            display: -moz-inline-box;
            display: inline-block;
            *display: inline;
            height: 1em;
            width: 1em;
        }

        span.emoji-inner {
            background: url("{{ URL::asset('img/icon/emoji.png') }}");
            display: -moz-inline-box;
            display: inline-block;
            text-indent: -9999px;
            width: 100%;
            height: 100%;
            vertical-align: baseline;
            *vertical-align: auto;
            *zoom: 1;
        }
        span.emoji-inner { background-size: 4100%; }
        .emojia9 { background-position: 0% 0% !important; }
        .emojiae { background-position: 0% 2.5% !important; }
        .emoji203c { background-position: 0% 5% !important; }
        .emoji2049 { background-position: 0% 7.5% !important; }
        .emoji2122 { background-position: 0% 10% !important; }
        .emoji2139 { background-position: 0% 12.5% !important; }
        .emoji2194 { background-position: 0% 15% !important; }
        .emoji2195 { background-position: 0% 17.5% !important; }
        .emoji2196 { background-position: 0% 20% !important; }
        .emoji2197 { background-position: 0% 22.5% !important; }
        .emoji2198 { background-position: 0% 25% !important; }
        .emoji2199 { background-position: 0% 27.5% !important; }
        .emoji21a9 { background-position: 0% 30% !important; }
        .emoji21aa { background-position: 0% 32.5% !important; }
        .emoji231a { background-position: 0% 35% !important; }
        .emoji231b { background-position: 0% 37.5% !important; }
        .emoji2328 { background-position: 0% 40% !important; }
        .emoji23e9 { background-position: 0% 42.5% !important; }
        .emoji23ea { background-position: 0% 45% !important; }
        .emoji23eb { background-position: 0% 47.5% !important; }
        .emoji23ec { background-position: 0% 50% !important; }
        .emoji23ed { background-position: 0% 52.5% !important; }
        .emoji23ee { background-position: 0% 55% !important; }
        .emoji23ef { background-position: 0% 57.5% !important; }
        .emoji23f0 { background-position: 0% 60% !important; }
        .emoji23f1 { background-position: 0% 62.5% !important; }
        .emoji23f2 { background-position: 0% 65% !important; }
        .emoji23f3 { background-position: 0% 67.5% !important; }
        .emoji23f8 { background-position: 0% 70% !important; }
        .emoji23f9 { background-position: 0% 72.5% !important; }
        .emoji23fa { background-position: 0% 75% !important; }
        .emoji24c2 { background-position: 0% 77.5% !important; }
        .emoji25aa { background-position: 0% 80% !important; }
        .emoji25ab { background-position: 0% 82.5% !important; }
        .emoji25b6 { background-position: 0% 85% !important; }
        .emoji25c0 { background-position: 0% 87.5% !important; }
        .emoji25fb { background-position: 0% 90% !important; }
        .emoji25fc { background-position: 0% 92.5% !important; }
        .emoji25fd { background-position: 0% 95% !important; }
        .emoji25fe { background-position: 0% 97.5% !important; }
        .emoji2600 { background-position: 0% 100% !important; }
        .emoji2601 { background-position: 2.5% 0% !important; }
        .emoji2602 { background-position: 2.5% 2.5% !important; }
        .emoji2603 { background-position: 2.5% 5% !important; }
        .emoji2604 { background-position: 2.5% 7.5% !important; }
        .emoji260e { background-position: 2.5% 10% !important; }
        .emoji2611 { background-position: 2.5% 12.5% !important; }
        .emoji2614 { background-position: 2.5% 15% !important; }
        .emoji2615 { background-position: 2.5% 17.5% !important; }
        .emoji2618 { background-position: 2.5% 20% !important; }
        .emoji261d { background-position: 2.5% 22.5% !important; }
        .emoji2620 { background-position: 2.5% 37.5% !important; }
        .emoji2622 { background-position: 2.5% 40% !important; }
        .emoji2623 { background-position: 2.5% 42.5% !important; }
        .emoji2626 { background-position: 2.5% 45% !important; }
        .emoji262a { background-position: 2.5% 47.5% !important; }
        .emoji262e { background-position: 2.5% 50% !important; }
        .emoji262f { background-position: 2.5% 52.5% !important; }
        .emoji2638 { background-position: 2.5% 55% !important; }
        .emoji2639 { background-position: 2.5% 57.5% !important; }
        .emoji263a { background-position: 2.5% 60% !important; }
        .emoji2648 { background-position: 2.5% 62.5% !important; }
        .emoji2649 { background-position: 2.5% 65% !important; }
        .emoji264a { background-position: 2.5% 67.5% !important; }
        .emoji264b { background-position: 2.5% 70% !important; }
        .emoji264c { background-position: 2.5% 72.5% !important; }
        .emoji264d { background-position: 2.5% 75% !important; }
        .emoji264e { background-position: 2.5% 77.5% !important; }
        .emoji264f { background-position: 2.5% 80% !important; }
        .emoji2650 { background-position: 2.5% 82.5% !important; }
        .emoji2651 { background-position: 2.5% 85% !important; }
        .emoji2652 { background-position: 2.5% 87.5% !important; }
        .emoji2653 { background-position: 2.5% 90% !important; }
        .emoji2660 { background-position: 2.5% 92.5% !important; }
        .emoji2663 { background-position: 2.5% 95% !important; }
        .emoji2665 { background-position: 2.5% 97.5% !important; }
        .emoji2666 { background-position: 2.5% 100% !important; }
        .emoji2668 { background-position: 5% 0% !important; }
        .emoji267b { background-position: 5% 2.5% !important; }
        .emoji267f { background-position: 5% 5% !important; }
        .emoji2692 { background-position: 5% 7.5% !important; }
        .emoji2693 { background-position: 5% 10% !important; }
        .emoji2694 { background-position: 5% 12.5% !important; }
        .emoji2696 { background-position: 5% 15% !important; }
        .emoji2697 { background-position: 5% 17.5% !important; }
        .emoji2699 { background-position: 5% 20% !important; }
        .emoji269b { background-position: 5% 22.5% !important; }
        .emoji269c { background-position: 5% 25% !important; }
        .emoji26a0 { background-position: 5% 27.5% !important; }
        .emoji26a1 { background-position: 5% 30% !important; }
        .emoji26aa { background-position: 5% 32.5% !important; }
        .emoji26ab { background-position: 5% 35% !important; }
        .emoji26b0 { background-position: 5% 37.5% !important; }
        .emoji26b1 { background-position: 5% 40% !important; }
        .emoji26bd { background-position: 5% 42.5% !important; }
        .emoji26be { background-position: 5% 45% !important; }
        .emoji26c4 { background-position: 5% 47.5% !important; }
        .emoji26c5 { background-position: 5% 50% !important; }
        .emoji26c8 { background-position: 5% 52.5% !important; }
        .emoji26ce { background-position: 5% 55% !important; }
        .emoji26cf { background-position: 5% 57.5% !important; }
        .emoji26d1 { background-position: 5% 60% !important; }
        .emoji26d3 { background-position: 5% 62.5% !important; }
        .emoji26d4 { background-position: 5% 65% !important; }
        .emoji26e9 { background-position: 5% 67.5% !important; }
        .emoji26ea { background-position: 5% 70% !important; }
        .emoji26f0 { background-position: 5% 72.5% !important; }
        .emoji26f1 { background-position: 5% 75% !important; }
        .emoji26f2 { background-position: 5% 77.5% !important; }
        .emoji26f3 { background-position: 5% 80% !important; }
        .emoji26f4 { background-position: 5% 82.5% !important; }
        .emoji26f5 { background-position: 5% 85% !important; }
        .emoji26f7 { background-position: 5% 87.5% !important; }
        .emoji26f8 { background-position: 5% 90% !important; }
        .emoji26f9 { background-position: 5% 92.5% !important; }
        .emoji26fa { background-position: 7.5% 5% !important; }
        .emoji26fd { background-position: 7.5% 7.5% !important; }
        .emoji2702 { background-position: 7.5% 10% !important; }
        .emoji2705 { background-position: 7.5% 12.5% !important; }
        .emoji2708 { background-position: 7.5% 15% !important; }
        .emoji2709 { background-position: 7.5% 17.5% !important; }
        .emoji270a { background-position: 7.5% 20% !important; }
        .emoji270b { background-position: 7.5% 35% !important; }
        .emoji270c { background-position: 7.5% 50% !important; }
        .emoji270d { background-position: 7.5% 65% !important; }
        .emoji270f { background-position: 7.5% 80% !important; }
        .emoji2712 { background-position: 7.5% 82.5% !important; }
        .emoji2714 { background-position: 7.5% 85% !important; }
        .emoji2716 { background-position: 7.5% 87.5% !important; }
        .emoji271d { background-position: 7.5% 90% !important; }
        .emoji2721 { background-position: 7.5% 92.5% !important; }
        .emoji2728 { background-position: 7.5% 95% !important; }
        .emoji2733 { background-position: 7.5% 97.5% !important; }
        .emoji2734 { background-position: 7.5% 100% !important; }
        .emoji2744 { background-position: 10% 0% !important; }
        .emoji2747 { background-position: 10% 2.5% !important; }
        .emoji274c { background-position: 10% 5% !important; }
        .emoji274e { background-position: 10% 7.5% !important; }
        .emoji2753 { background-position: 10% 10% !important; }
        .emoji2754 { background-position: 10% 12.5% !important; }
        .emoji2755 { background-position: 10% 15% !important; }
        .emoji2757 { background-position: 10% 17.5% !important; }
        .emoji2763 { background-position: 10% 20% !important; }
        .emoji2764 { background-position: 10% 22.5% !important; }
        .emoji2795 { background-position: 10% 25% !important; }
        .emoji2796 { background-position: 10% 27.5% !important; }
        .emoji2797 { background-position: 10% 30% !important; }
        .emoji27a1 { background-position: 10% 32.5% !important; }
        .emoji27b0 { background-position: 10% 35% !important; }
        .emoji27bf { background-position: 10% 37.5% !important; }
        .emoji2934 { background-position: 10% 40% !important; }
        .emoji2935 { background-position: 10% 42.5% !important; }
        .emoji2b05 { background-position: 10% 45% !important; }
        .emoji2b06 { background-position: 10% 47.5% !important; }
        .emoji2b07 { background-position: 10% 50% !important; }
        .emoji2b1b { background-position: 10% 52.5% !important; }
        .emoji2b1c { background-position: 10% 55% !important; }
        .emoji2b50 { background-position: 10% 57.5% !important; }
        .emoji2b55 { background-position: 10% 60% !important; }
        .emoji3030 { background-position: 10% 62.5% !important; }
        .emoji303d { background-position: 10% 65% !important; }
        .emoji3297 { background-position: 10% 67.5% !important; }
        .emoji3299 { background-position: 10% 70% !important; }
        .emoji1f004 { background-position: 10% 72.5% !important; }
        .emoji1f0cf { background-position: 10% 75% !important; }
        .emoji1f170 { background-position: 10% 77.5% !important; }
        .emoji1f171 { background-position: 10% 80% !important; }
        .emoji1f17e { background-position: 10% 82.5% !important; }
        .emoji1f17f { background-position: 10% 85% !important; }
        .emoji1f18e { background-position: 10% 87.5% !important; }
        .emoji1f191 { background-position: 10% 90% !important; }
        .emoji1f192 { background-position: 10% 92.5% !important; }
        .emoji1f193 { background-position: 10% 95% !important; }
        .emoji1f194 { background-position: 10% 97.5% !important; }
        .emoji1f195 { background-position: 10% 100% !important; }
        .emoji1f196 { background-position: 12.5% 0% !important; }
        .emoji1f197 { background-position: 12.5% 2.5% !important; }
        .emoji1f198 { background-position: 12.5% 5% !important; }
        .emoji1f199 { background-position: 12.5% 7.5% !important; }
        .emoji1f19a { background-position: 12.5% 10% !important; }
        .emoji1f201 { background-position: 12.5% 12.5% !important; }
        .emoji1f202 { background-position: 12.5% 15% !important; }
        .emoji1f21a { background-position: 12.5% 17.5% !important; }
        .emoji1f22f { background-position: 12.5% 20% !important; }
        .emoji1f232 { background-position: 12.5% 22.5% !important; }
        .emoji1f233 { background-position: 12.5% 25% !important; }
        .emoji1f234 { background-position: 12.5% 27.5% !important; }
        .emoji1f235 { background-position: 12.5% 30% !important; }
        .emoji1f236 { background-position: 12.5% 32.5% !important; }
        .emoji1f237 { background-position: 12.5% 35% !important; }
        .emoji1f238 { background-position: 12.5% 37.5% !important; }
        .emoji1f239 { background-position: 12.5% 40% !important; }
        .emoji1f23a { background-position: 12.5% 42.5% !important; }
        .emoji1f250 { background-position: 12.5% 45% !important; }
        .emoji1f251 { background-position: 12.5% 47.5% !important; }
        .emoji1f300 { background-position: 12.5% 50% !important; }
        .emoji1f301 { background-position: 12.5% 52.5% !important; }
        .emoji1f302 { background-position: 12.5% 55% !important; }
        .emoji1f303 { background-position: 12.5% 57.5% !important; }
        .emoji1f304 { background-position: 12.5% 60% !important; }
        .emoji1f305 { background-position: 12.5% 62.5% !important; }
        .emoji1f306 { background-position: 12.5% 65% !important; }
        .emoji1f307 { background-position: 12.5% 67.5% !important; }
        .emoji1f308 { background-position: 12.5% 70% !important; }
        .emoji1f309 { background-position: 12.5% 72.5% !important; }
        .emoji1f30a { background-position: 12.5% 75% !important; }
        .emoji1f30b { background-position: 12.5% 77.5% !important; }
        .emoji1f30c { background-position: 12.5% 80% !important; }
        .emoji1f30d { background-position: 12.5% 82.5% !important; }
        .emoji1f30e { background-position: 12.5% 85% !important; }
        .emoji1f30f { background-position: 12.5% 87.5% !important; }
        .emoji1f310 { background-position: 12.5% 90% !important; }
        .emoji1f311 { background-position: 12.5% 92.5% !important; }
        .emoji1f312 { background-position: 12.5% 95% !important; }
        .emoji1f313 { background-position: 12.5% 97.5% !important; }
        .emoji1f314 { background-position: 12.5% 100% !important; }
        .emoji1f315 { background-position: 15% 0% !important; }
        .emoji1f316 { background-position: 15% 2.5% !important; }
        .emoji1f317 { background-position: 15% 5% !important; }
        .emoji1f318 { background-position: 15% 7.5% !important; }
        .emoji1f319 { background-position: 15% 10% !important; }
        .emoji1f31a { background-position: 15% 12.5% !important; }
        .emoji1f31b { background-position: 15% 15% !important; }
        .emoji1f31c { background-position: 15% 17.5% !important; }
        .emoji1f31d { background-position: 15% 20% !important; }
        .emoji1f31e { background-position: 15% 22.5% !important; }
        .emoji1f31f { background-position: 15% 25% !important; }
        .emoji1f320 { background-position: 15% 27.5% !important; }
        .emoji1f321 { background-position: 15% 30% !important; }
        .emoji1f324 { background-position: 15% 32.5% !important; }
        .emoji1f325 { background-position: 15% 35% !important; }
        .emoji1f326 { background-position: 15% 37.5% !important; }
        .emoji1f327 { background-position: 15% 40% !important; }
        .emoji1f328 { background-position: 15% 42.5% !important; }
        .emoji1f329 { background-position: 15% 45% !important; }
        .emoji1f32a { background-position: 15% 47.5% !important; }
        .emoji1f32b { background-position: 15% 50% !important; }
        .emoji1f32c { background-position: 15% 52.5% !important; }
        .emoji1f32d { background-position: 15% 55% !important; }
        .emoji1f32e { background-position: 15% 57.5% !important; }
        .emoji1f32f { background-position: 15% 60% !important; }
        .emoji1f330 { background-position: 15% 62.5% !important; }
        .emoji1f331 { background-position: 15% 65% !important; }
        .emoji1f332 { background-position: 15% 67.5% !important; }
        .emoji1f333 { background-position: 15% 70% !important; }
        .emoji1f334 { background-position: 15% 72.5% !important; }
        .emoji1f335 { background-position: 15% 75% !important; }
        .emoji1f336 { background-position: 15% 77.5% !important; }
        .emoji1f337 { background-position: 15% 80% !important; }
        .emoji1f338 { background-position: 15% 82.5% !important; }
        .emoji1f339 { background-position: 15% 85% !important; }
        .emoji1f33a { background-position: 15% 87.5% !important; }
        .emoji1f33b { background-position: 15% 90% !important; }
        .emoji1f33c { background-position: 15% 92.5% !important; }
        .emoji1f33d { background-position: 15% 95% !important; }
        .emoji1f33e { background-position: 15% 97.5% !important; }
        .emoji1f33f { background-position: 15% 100% !important; }
        .emoji1f340 { background-position: 17.5% 0% !important; }
        .emoji1f341 { background-position: 17.5% 2.5% !important; }
        .emoji1f342 { background-position: 17.5% 5% !important; }
        .emoji1f343 { background-position: 17.5% 7.5% !important; }
        .emoji1f344 { background-position: 17.5% 10% !important; }
        .emoji1f345 { background-position: 17.5% 12.5% !important; }
        .emoji1f346 { background-position: 17.5% 15% !important; }
        .emoji1f347 { background-position: 17.5% 17.5% !important; }
        .emoji1f348 { background-position: 17.5% 20% !important; }
        .emoji1f349 { background-position: 17.5% 22.5% !important; }
        .emoji1f34a { background-position: 17.5% 25% !important; }
        .emoji1f34b { background-position: 17.5% 27.5% !important; }
        .emoji1f34c { background-position: 17.5% 30% !important; }
        .emoji1f34d { background-position: 17.5% 32.5% !important; }
        .emoji1f34e { background-position: 17.5% 35% !important; }
        .emoji1f34f { background-position: 17.5% 37.5% !important; }
        .emoji1f350 { background-position: 17.5% 40% !important; }
        .emoji1f351 { background-position: 17.5% 42.5% !important; }
        .emoji1f352 { background-position: 17.5% 45% !important; }
        .emoji1f353 { background-position: 17.5% 47.5% !important; }
        .emoji1f354 { background-position: 17.5% 50% !important; }
        .emoji1f355 { background-position: 17.5% 52.5% !important; }
        .emoji1f356 { background-position: 17.5% 55% !important; }
        .emoji1f357 { background-position: 17.5% 57.5% !important; }
        .emoji1f358 { background-position: 17.5% 60% !important; }
        .emoji1f359 { background-position: 17.5% 62.5% !important; }
        .emoji1f35a { background-position: 17.5% 65% !important; }
        .emoji1f35b { background-position: 17.5% 67.5% !important; }
        .emoji1f35c { background-position: 17.5% 70% !important; }
        .emoji1f35d { background-position: 17.5% 72.5% !important; }
        .emoji1f35e { background-position: 17.5% 75% !important; }
        .emoji1f35f { background-position: 17.5% 77.5% !important; }
        .emoji1f360 { background-position: 17.5% 80% !important; }
        .emoji1f361 { background-position: 17.5% 82.5% !important; }
        .emoji1f362 { background-position: 17.5% 85% !important; }
        .emoji1f363 { background-position: 17.5% 87.5% !important; }
        .emoji1f364 { background-position: 17.5% 90% !important; }
        .emoji1f365 { background-position: 17.5% 92.5% !important; }
        .emoji1f366 { background-position: 17.5% 95% !important; }
        .emoji1f367 { background-position: 17.5% 97.5% !important; }
        .emoji1f368 { background-position: 17.5% 100% !important; }
        .emoji1f369 { background-position: 20% 0% !important; }
        .emoji1f36a { background-position: 20% 2.5% !important; }
        .emoji1f36b { background-position: 20% 5% !important; }
        .emoji1f36c { background-position: 20% 7.5% !important; }
        .emoji1f36d { background-position: 20% 10% !important; }
        .emoji1f36e { background-position: 20% 12.5% !important; }
        .emoji1f36f { background-position: 20% 15% !important; }
        .emoji1f370 { background-position: 20% 17.5% !important; }
        .emoji1f371 { background-position: 20% 20% !important; }
        .emoji1f372 { background-position: 20% 22.5% !important; }
        .emoji1f373 { background-position: 20% 25% !important; }
        .emoji1f374 { background-position: 20% 27.5% !important; }
        .emoji1f375 { background-position: 20% 30% !important; }
        .emoji1f376 { background-position: 20% 32.5% !important; }
        .emoji1f377 { background-position: 20% 35% !important; }
        .emoji1f378 { background-position: 20% 37.5% !important; }
        .emoji1f379 { background-position: 20% 40% !important; }
        .emoji1f37a { background-position: 20% 42.5% !important; }
        .emoji1f37b { background-position: 20% 45% !important; }
        .emoji1f37c { background-position: 20% 47.5% !important; }
        .emoji1f37d { background-position: 20% 50% !important; }
        .emoji1f37e { background-position: 20% 52.5% !important; }
        .emoji1f37f { background-position: 20% 55% !important; }
        .emoji1f380 { background-position: 20% 57.5% !important; }
        .emoji1f381 { background-position: 20% 60% !important; }
        .emoji1f382 { background-position: 20% 62.5% !important; }
        .emoji1f383 { background-position: 20% 65% !important; }
        .emoji1f384 { background-position: 20% 67.5% !important; }
        .emoji1f385 { background-position: 20% 70% !important; }
        .emoji1f386 { background-position: 20% 85% !important; }
        .emoji1f387 { background-position: 20% 87.5% !important; }
        .emoji1f388 { background-position: 20% 90% !important; }
        .emoji1f389 { background-position: 20% 92.5% !important; }
        .emoji1f38a { background-position: 20% 95% !important; }
        .emoji1f38b { background-position: 20% 97.5% !important; }
        .emoji1f38c { background-position: 20% 100% !important; }
        .emoji1f38d { background-position: 22.5% 0% !important; }
        .emoji1f38e { background-position: 22.5% 2.5% !important; }
        .emoji1f38f { background-position: 22.5% 5% !important; }
        .emoji1f390 { background-position: 22.5% 7.5% !important; }
        .emoji1f391 { background-position: 22.5% 10% !important; }
        .emoji1f392 { background-position: 22.5% 12.5% !important; }
        .emoji1f393 { background-position: 22.5% 15% !important; }
        .emoji1f396 { background-position: 22.5% 17.5% !important; }
        .emoji1f397 { background-position: 22.5% 20% !important; }
        .emoji1f399 { background-position: 22.5% 22.5% !important; }
        .emoji1f39a { background-position: 22.5% 25% !important; }
        .emoji1f39b { background-position: 22.5% 27.5% !important; }
        .emoji1f39e { background-position: 22.5% 30% !important; }
        .emoji1f39f { background-position: 22.5% 32.5% !important; }
        .emoji1f3a0 { background-position: 22.5% 35% !important; }
        .emoji1f3a1 { background-position: 22.5% 37.5% !important; }
        .emoji1f3a2 { background-position: 22.5% 40% !important; }
        .emoji1f3a3 { background-position: 22.5% 42.5% !important; }
        .emoji1f3a4 { background-position: 22.5% 45% !important; }
        .emoji1f3a5 { background-position: 22.5% 47.5% !important; }
        .emoji1f3a6 { background-position: 22.5% 50% !important; }
        .emoji1f3a7 { background-position: 22.5% 52.5% !important; }
        .emoji1f3a8 { background-position: 22.5% 55% !important; }
        .emoji1f3a9 { background-position: 22.5% 57.5% !important; }
        .emoji1f3aa { background-position: 22.5% 60% !important; }
        .emoji1f3ab { background-position: 22.5% 62.5% !important; }
        .emoji1f3ac { background-position: 22.5% 65% !important; }
        .emoji1f3ad { background-position: 22.5% 67.5% !important; }
        .emoji1f3ae { background-position: 22.5% 70% !important; }
        .emoji1f3af { background-position: 22.5% 72.5% !important; }
        .emoji1f3b0 { background-position: 22.5% 75% !important; }
        .emoji1f3b1 { background-position: 22.5% 77.5% !important; }
        .emoji1f3b2 { background-position: 22.5% 80% !important; }
        .emoji1f3b3 { background-position: 22.5% 82.5% !important; }
        .emoji1f3b4 { background-position: 22.5% 85% !important; }
        .emoji1f3b5 { background-position: 22.5% 87.5% !important; }
        .emoji1f3b6 { background-position: 22.5% 90% !important; }
        .emoji1f3b7 { background-position: 22.5% 92.5% !important; }
        .emoji1f3b8 { background-position: 22.5% 95% !important; }
        .emoji1f3b9 { background-position: 22.5% 97.5% !important; }
        .emoji1f3ba { background-position: 22.5% 100% !important; }
        .emoji1f3bb { background-position: 25% 0% !important; }
        .emoji1f3bc { background-position: 25% 2.5% !important; }
        .emoji1f3bd { background-position: 25% 5% !important; }
        .emoji1f3be { background-position: 25% 7.5% !important; }
        .emoji1f3bf { background-position: 25% 10% !important; }
        .emoji1f3c0 { background-position: 25% 12.5% !important; }
        .emoji1f3c1 { background-position: 25% 15% !important; }
        .emoji1f3c2 { background-position: 25% 17.5% !important; }
        .emoji1f3c3 { background-position: 25% 20% !important; }
        .emoji1f3c4 { background-position: 25% 35% !important; }
        .emoji1f3c5 { background-position: 25% 50% !important; }
        .emoji1f3c6 { background-position: 25% 52.5% !important; }
        .emoji1f3c7 { background-position: 25% 55% !important; }
        .emoji1f3c8 { background-position: 25% 70% !important; }
        .emoji1f3c9 { background-position: 25% 72.5% !important; }
        .emoji1f3ca { background-position: 25% 75% !important; }
        .emoji1f3cb { background-position: 25% 90% !important; }
        .emoji1f3cc { background-position: 27.5% 2.5% !important; }
        .emoji1f3cd { background-position: 27.5% 5% !important; }
        .emoji1f3ce { background-position: 27.5% 7.5% !important; }
        .emoji1f3cf { background-position: 27.5% 10% !important; }
        .emoji1f3d0 { background-position: 27.5% 12.5% !important; }
        .emoji1f3d1 { background-position: 27.5% 15% !important; }
        .emoji1f3d2 { background-position: 27.5% 17.5% !important; }
        .emoji1f3d3 { background-position: 27.5% 20% !important; }
        .emoji1f3d4 { background-position: 27.5% 22.5% !important; }
        .emoji1f3d5 { background-position: 27.5% 25% !important; }
        .emoji1f3d6 { background-position: 27.5% 27.5% !important; }
        .emoji1f3d7 { background-position: 27.5% 30% !important; }
        .emoji1f3d8 { background-position: 27.5% 32.5% !important; }
        .emoji1f3d9 { background-position: 27.5% 35% !important; }
        .emoji1f3da { background-position: 27.5% 37.5% !important; }
        .emoji1f3db { background-position: 27.5% 40% !important; }
        .emoji1f3dc { background-position: 27.5% 42.5% !important; }
        .emoji1f3dd { background-position: 27.5% 45% !important; }
        .emoji1f3de { background-position: 27.5% 47.5% !important; }
        .emoji1f3df { background-position: 27.5% 50% !important; }
        .emoji1f3e0 { background-position: 27.5% 52.5% !important; }
        .emoji1f3e1 { background-position: 27.5% 55% !important; }
        .emoji1f3e2 { background-position: 27.5% 57.5% !important; }
        .emoji1f3e3 { background-position: 27.5% 60% !important; }
        .emoji1f3e4 { background-position: 27.5% 62.5% !important; }
        .emoji1f3e5 { background-position: 27.5% 65% !important; }
        .emoji1f3e6 { background-position: 27.5% 67.5% !important; }
        .emoji1f3e7 { background-position: 27.5% 70% !important; }
        .emoji1f3e8 { background-position: 27.5% 72.5% !important; }
        .emoji1f3e9 { background-position: 27.5% 75% !important; }
        .emoji1f3ea { background-position: 27.5% 77.5% !important; }
        .emoji1f3eb { background-position: 27.5% 80% !important; }
        .emoji1f3ec { background-position: 27.5% 82.5% !important; }
        .emoji1f3ed { background-position: 27.5% 85% !important; }
        .emoji1f3ee { background-position: 27.5% 87.5% !important; }
        .emoji1f3ef { background-position: 27.5% 90% !important; }
        .emoji1f3f0 { background-position: 27.5% 92.5% !important; }
        .emoji1f3f3 { background-position: 27.5% 95% !important; }
        .emoji1f3f4 { background-position: 27.5% 97.5% !important; }
        .emoji1f3f5 { background-position: 27.5% 100% !important; }
        .emoji1f3f7 { background-position: 30% 0% !important; }
        .emoji1f3f8 { background-position: 30% 2.5% !important; }
        .emoji1f3f9 { background-position: 30% 5% !important; }
        .emoji1f3fa { background-position: 30% 7.5% !important; }
        .emoji1f3fb { background-position: 30% 10% !important; }
        .emoji1f3fc { background-position: 30% 12.5% !important; }
        .emoji1f3fd { background-position: 30% 15% !important; }
        .emoji1f3fe { background-position: 30% 17.5% !important; }
        .emoji1f3ff { background-position: 30% 20% !important; }
        .emoji1f400 { background-position: 30% 22.5% !important; }
        .emoji1f401 { background-position: 30% 25% !important; }
        .emoji1f402 { background-position: 30% 27.5% !important; }
        .emoji1f403 { background-position: 30% 30% !important; }
        .emoji1f404 { background-position: 30% 32.5% !important; }
        .emoji1f405 { background-position: 30% 35% !important; }
        .emoji1f406 { background-position: 30% 37.5% !important; }
        .emoji1f407 { background-position: 30% 40% !important; }
        .emoji1f408 { background-position: 30% 42.5% !important; }
        .emoji1f409 { background-position: 30% 45% !important; }
        .emoji1f40a { background-position: 30% 47.5% !important; }
        .emoji1f40b { background-position: 30% 50% !important; }
        .emoji1f40c { background-position: 30% 52.5% !important; }
        .emoji1f40d { background-position: 30% 55% !important; }
        .emoji1f40e { background-position: 30% 57.5% !important; }
        .emoji1f40f { background-position: 30% 60% !important; }
        .emoji1f410 { background-position: 30% 62.5% !important; }
        .emoji1f411 { background-position: 30% 65% !important; }
        .emoji1f412 { background-position: 30% 67.5% !important; }
        .emoji1f413 { background-position: 30% 70% !important; }
        .emoji1f414 { background-position: 30% 72.5% !important; }
        .emoji1f415 { background-position: 30% 75% !important; }
        .emoji1f416 { background-position: 30% 77.5% !important; }
        .emoji1f417 { background-position: 30% 80% !important; }
        .emoji1f418 { background-position: 30% 82.5% !important; }
        .emoji1f419 { background-position: 30% 85% !important; }
        .emoji1f41a { background-position: 30% 87.5% !important; }
        .emoji1f41b { background-position: 30% 90% !important; }
        .emoji1f41c { background-position: 30% 92.5% !important; }
        .emoji1f41d { background-position: 30% 95% !important; }
        .emoji1f41e { background-position: 30% 97.5% !important; }
        .emoji1f41f { background-position: 30% 100% !important; }
        .emoji1f420 { background-position: 32.5% 0% !important; }
        .emoji1f421 { background-position: 32.5% 2.5% !important; }
        .emoji1f422 { background-position: 32.5% 5% !important; }
        .emoji1f423 { background-position: 32.5% 7.5% !important; }
        .emoji1f424 { background-position: 32.5% 10% !important; }
        .emoji1f425 { background-position: 32.5% 12.5% !important; }
        .emoji1f426 { background-position: 32.5% 15% !important; }
        .emoji1f427 { background-position: 32.5% 17.5% !important; }
        .emoji1f428 { background-position: 32.5% 20% !important; }
        .emoji1f429 { background-position: 32.5% 22.5% !important; }
        .emoji1f42a { background-position: 32.5% 25% !important; }
        .emoji1f42b { background-position: 32.5% 27.5% !important; }
        .emoji1f42c { background-position: 32.5% 30% !important; }
        .emoji1f42d { background-position: 32.5% 32.5% !important; }
        .emoji1f42e { background-position: 32.5% 35% !important; }
        .emoji1f42f { background-position: 32.5% 37.5% !important; }
        .emoji1f430 { background-position: 32.5% 40% !important; }
        .emoji1f431 { background-position: 32.5% 42.5% !important; }
        .emoji1f432 { background-position: 32.5% 45% !important; }
        .emoji1f433 { background-position: 32.5% 47.5% !important; }
        .emoji1f434 { background-position: 32.5% 50% !important; }
        .emoji1f435 { background-position: 32.5% 52.5% !important; }
        .emoji1f436 { background-position: 32.5% 55% !important; }
        .emoji1f437 { background-position: 32.5% 57.5% !important; }
        .emoji1f438 { background-position: 32.5% 60% !important; }
        .emoji1f439 { background-position: 32.5% 62.5% !important; }
        .emoji1f43a { background-position: 32.5% 65% !important; }
        .emoji1f43b { background-position: 32.5% 67.5% !important; }
        .emoji1f43c { background-position: 32.5% 70% !important; }
        .emoji1f43d { background-position: 32.5% 72.5% !important; }
        .emoji1f43e { background-position: 32.5% 75% !important; }
        .emoji1f43f { background-position: 32.5% 77.5% !important; }
        .emoji1f440 { background-position: 32.5% 80% !important; }
        .emoji1f441 { background-position: 32.5% 82.5% !important; }
        .emoji1f442 { background-position: 32.5% 85% !important; }
        .emoji1f443 { background-position: 32.5% 100% !important; }
        .emoji1f444 { background-position: 35% 12.5% !important; }
        .emoji1f445 { background-position: 35% 15% !important; }
        .emoji1f446 { background-position: 35% 17.5% !important; }
        .emoji1f447 { background-position: 35% 32.5% !important; }
        .emoji1f448 { background-position: 35% 47.5% !important; }
        .emoji1f449 { background-position: 35% 62.5% !important; }
        .emoji1f44a { background-position: 35% 77.5% !important; }
        .emoji1f44b { background-position: 35% 92.5% !important; }
        .emoji1f44c { background-position: 37.5% 5% !important; }
        .emoji1f44d { background-position: 37.5% 20% !important; }
        .emoji1f44e { background-position: 37.5% 35% !important; }
        .emoji1f44f { background-position: 37.5% 50% !important; }
        .emoji1f450 { background-position: 37.5% 65% !important; }
        .emoji1f451 { background-position: 37.5% 80% !important; }
        .emoji1f452 { background-position: 37.5% 82.5% !important; }
        .emoji1f453 { background-position: 37.5% 85% !important; }
        .emoji1f454 { background-position: 37.5% 87.5% !important; }
        .emoji1f455 { background-position: 37.5% 90% !important; }
        .emoji1f456 { background-position: 37.5% 92.5% !important; }
        .emoji1f457 { background-position: 37.5% 95% !important; }
        .emoji1f458 { background-position: 37.5% 97.5% !important; }
        .emoji1f459 { background-position: 37.5% 100% !important; }
        .emoji1f45a { background-position: 40% 0% !important; }
        .emoji1f45b { background-position: 40% 2.5% !important; }
        .emoji1f45c { background-position: 40% 5% !important; }
        .emoji1f45d { background-position: 40% 7.5% !important; }
        .emoji1f45e { background-position: 40% 10% !important; }
        .emoji1f45f { background-position: 40% 12.5% !important; }
        .emoji1f460 { background-position: 40% 15% !important; }
        .emoji1f461 { background-position: 40% 17.5% !important; }
        .emoji1f462 { background-position: 40% 20% !important; }
        .emoji1f463 { background-position: 40% 22.5% !important; }
        .emoji1f464 { background-position: 40% 25% !important; }
        .emoji1f465 { background-position: 40% 27.5% !important; }
        .emoji1f466 { background-position: 40% 30% !important; }
        .emoji1f467 { background-position: 40% 45% !important; }
        .emoji1f468 { background-position: 40% 60% !important; }
        .emoji1f469 { background-position: 40% 75% !important; }
        .emoji1f46a { background-position: 40% 90% !important; }
        .emoji1f46b { background-position: 40% 92.5% !important; }
        .emoji1f46c { background-position: 40% 95% !important; }
        .emoji1f46d { background-position: 40% 97.5% !important; }
        .emoji1f46e { background-position: 40% 100% !important; }
        .emoji1f46f { background-position: 42.5% 12.5% !important; }
        .emoji1f470 { background-position: 42.5% 15% !important; }
        .emoji1f471 { background-position: 42.5% 30% !important; }
        .emoji1f472 { background-position: 42.5% 45% !important; }
        .emoji1f473 { background-position: 42.5% 60% !important; }
        .emoji1f474 { background-position: 42.5% 75% !important; }
        .emoji1f475 { background-position: 42.5% 90% !important; }
        .emoji1f476 { background-position: 45% 2.5% !important; }
        .emoji1f477 { background-position: 45% 17.5% !important; }
        .emoji1f478 { background-position: 45% 32.5% !important; }
        .emoji1f479 { background-position: 45% 47.5% !important; }
        .emoji1f47a { background-position: 45% 50% !important; }
        .emoji1f47b { background-position: 45% 52.5% !important; }
        .emoji1f47c { background-position: 45% 55% !important; }
        .emoji1f47d { background-position: 45% 70% !important; }
        .emoji1f47e { background-position: 45% 72.5% !important; }
        .emoji1f47f { background-position: 45% 75% !important; }
        .emoji1f480 { background-position: 45% 77.5% !important; }
        .emoji1f481 { background-position: 45% 80% !important; }
        .emoji1f482 { background-position: 45% 95% !important; }
        .emoji1f483 { background-position: 47.5% 7.5% !important; }
        .emoji1f484 { background-position: 47.5% 22.5% !important; }
        .emoji1f485 { background-position: 47.5% 25% !important; }
        .emoji1f486 { background-position: 47.5% 40% !important; }
        .emoji1f487 { background-position: 47.5% 55% !important; }
        .emoji1f488 { background-position: 47.5% 70% !important; }
        .emoji1f489 { background-position: 47.5% 72.5% !important; }
        .emoji1f48a { background-position: 47.5% 75% !important; }
        .emoji1f48b { background-position: 47.5% 77.5% !important; }
        .emoji1f48c { background-position: 47.5% 80% !important; }
        .emoji1f48d { background-position: 47.5% 82.5% !important; }
        .emoji1f48e { background-position: 47.5% 85% !important; }
        .emoji1f48f { background-position: 47.5% 87.5% !important; }
        .emoji1f490 { background-position: 47.5% 90% !important; }
        .emoji1f491 { background-position: 47.5% 92.5% !important; }
        .emoji1f492 { background-position: 47.5% 95% !important; }
        .emoji1f493 { background-position: 47.5% 97.5% !important; }
        .emoji1f494 { background-position: 47.5% 100% !important; }
        .emoji1f495 { background-position: 50% 0% !important; }
        .emoji1f496 { background-position: 50% 2.5% !important; }
        .emoji1f497 { background-position: 50% 5% !important; }
        .emoji1f498 { background-position: 50% 7.5% !important; }
        .emoji1f499 { background-position: 50% 10% !important; }
        .emoji1f49a { background-position: 50% 12.5% !important; }
        .emoji1f49b { background-position: 50% 15% !important; }
        .emoji1f49c { background-position: 50% 17.5% !important; }
        .emoji1f49d { background-position: 50% 20% !important; }
        .emoji1f49e { background-position: 50% 22.5% !important; }
        .emoji1f49f { background-position: 50% 25% !important; }
        .emoji1f4a0 { background-position: 50% 27.5% !important; }
        .emoji1f4a1 { background-position: 50% 30% !important; }
        .emoji1f4a2 { background-position: 50% 32.5% !important; }
        .emoji1f4a3 { background-position: 50% 35% !important; }
        .emoji1f4a4 { background-position: 50% 37.5% !important; }
        .emoji1f4a5 { background-position: 50% 40% !important; }
        .emoji1f4a6 { background-position: 50% 42.5% !important; }
        .emoji1f4a7 { background-position: 50% 45% !important; }
        .emoji1f4a8 { background-position: 50% 47.5% !important; }
        .emoji1f4a9 { background-position: 50% 50% !important; }
        .emoji1f4aa { background-position: 50% 52.5% !important; }
        .emoji1f4ab { background-position: 50% 67.5% !important; }
        .emoji1f4ac { background-position: 50% 70% !important; }
        .emoji1f4ad { background-position: 50% 72.5% !important; }
        .emoji1f4ae { background-position: 50% 75% !important; }
        .emoji1f4af { background-position: 50% 77.5% !important; }
        .emoji1f4b0 { background-position: 50% 80% !important; }
        .emoji1f4b1 { background-position: 50% 82.5% !important; }
        .emoji1f4b2 { background-position: 50% 85% !important; }
        .emoji1f4b3 { background-position: 50% 87.5% !important; }
        .emoji1f4b4 { background-position: 50% 90% !important; }
        .emoji1f4b5 { background-position: 50% 92.5% !important; }
        .emoji1f4b6 { background-position: 50% 95% !important; }
        .emoji1f4b7 { background-position: 50% 97.5% !important; }
        .emoji1f4b8 { background-position: 50% 100% !important; }
        .emoji1f4b9 { background-position: 52.5% 0% !important; }
        .emoji1f4ba { background-position: 52.5% 2.5% !important; }
        .emoji1f4bb { background-position: 52.5% 5% !important; }
        .emoji1f4bc { background-position: 52.5% 7.5% !important; }
        .emoji1f4bd { background-position: 52.5% 10% !important; }
        .emoji1f4be { background-position: 52.5% 12.5% !important; }
        .emoji1f4bf { background-position: 52.5% 15% !important; }
        .emoji1f4c0 { background-position: 52.5% 17.5% !important; }
        .emoji1f4c1 { background-position: 52.5% 20% !important; }
        .emoji1f4c2 { background-position: 52.5% 22.5% !important; }
        .emoji1f4c3 { background-position: 52.5% 25% !important; }
        .emoji1f4c4 { background-position: 52.5% 27.5% !important; }
        .emoji1f4c5 { background-position: 52.5% 30% !important; }
        .emoji1f4c6 { background-position: 52.5% 32.5% !important; }
        .emoji1f4c7 { background-position: 52.5% 35% !important; }
        .emoji1f4c8 { background-position: 52.5% 37.5% !important; }
        .emoji1f4c9 { background-position: 52.5% 40% !important; }
        .emoji1f4ca { background-position: 52.5% 42.5% !important; }
        .emoji1f4cb { background-position: 52.5% 45% !important; }
        .emoji1f4cc { background-position: 52.5% 47.5% !important; }
        .emoji1f4cd { background-position: 52.5% 50% !important; }
        .emoji1f4ce { background-position: 52.5% 52.5% !important; }
        .emoji1f4cf { background-position: 52.5% 55% !important; }
        .emoji1f4d0 { background-position: 52.5% 57.5% !important; }
        .emoji1f4d1 { background-position: 52.5% 60% !important; }
        .emoji1f4d2 { background-position: 52.5% 62.5% !important; }
        .emoji1f4d3 { background-position: 52.5% 65% !important; }
        .emoji1f4d4 { background-position: 52.5% 67.5% !important; }
        .emoji1f4d5 { background-position: 52.5% 70% !important; }
        .emoji1f4d6 { background-position: 52.5% 72.5% !important; }
        .emoji1f4d7 { background-position: 52.5% 75% !important; }
        .emoji1f4d8 { background-position: 52.5% 77.5% !important; }
        .emoji1f4d9 { background-position: 52.5% 80% !important; }
        .emoji1f4da { background-position: 52.5% 82.5% !important; }
        .emoji1f4db { background-position: 52.5% 85% !important; }
        .emoji1f4dc { background-position: 52.5% 87.5% !important; }
        .emoji1f4dd { background-position: 52.5% 90% !important; }
        .emoji1f4de { background-position: 52.5% 92.5% !important; }
        .emoji1f4df { background-position: 52.5% 95% !important; }
        .emoji1f4e0 { background-position: 52.5% 97.5% !important; }
        .emoji1f4e1 { background-position: 52.5% 100% !important; }
        .emoji1f4e2 { background-position: 55% 0% !important; }
        .emoji1f4e3 { background-position: 55% 2.5% !important; }
        .emoji1f4e4 { background-position: 55% 5% !important; }
        .emoji1f4e5 { background-position: 55% 7.5% !important; }
        .emoji1f4e6 { background-position: 55% 10% !important; }
        .emoji1f4e7 { background-position: 55% 12.5% !important; }
        .emoji1f4e8 { background-position: 55% 15% !important; }
        .emoji1f4e9 { background-position: 55% 17.5% !important; }
        .emoji1f4ea { background-position: 55% 20% !important; }
        .emoji1f4eb { background-position: 55% 22.5% !important; }
        .emoji1f4ec { background-position: 55% 25% !important; }
        .emoji1f4ed { background-position: 55% 27.5% !important; }
        .emoji1f4ee { background-position: 55% 30% !important; }
        .emoji1f4ef { background-position: 55% 32.5% !important; }
        .emoji1f4f0 { background-position: 55% 35% !important; }
        .emoji1f4f1 { background-position: 55% 37.5% !important; }
        .emoji1f4f2 { background-position: 55% 40% !important; }
        .emoji1f4f3 { background-position: 55% 42.5% !important; }
        .emoji1f4f4 { background-position: 55% 45% !important; }
        .emoji1f4f5 { background-position: 55% 47.5% !important; }
        .emoji1f4f6 { background-position: 55% 50% !important; }
        .emoji1f4f7 { background-position: 55% 52.5% !important; }
        .emoji1f4f8 { background-position: 55% 55% !important; }
        .emoji1f4f9 { background-position: 55% 57.5% !important; }
        .emoji1f4fa { background-position: 55% 60% !important; }
        .emoji1f4fb { background-position: 55% 62.5% !important; }
        .emoji1f4fc { background-position: 55% 65% !important; }
        .emoji1f4fd { background-position: 55% 67.5% !important; }
        .emoji1f4ff { background-position: 55% 70% !important; }
        .emoji1f500 { background-position: 55% 72.5% !important; }
        .emoji1f501 { background-position: 55% 75% !important; }
        .emoji1f502 { background-position: 55% 77.5% !important; }
        .emoji1f503 { background-position: 55% 80% !important; }
        .emoji1f504 { background-position: 55% 82.5% !important; }
        .emoji1f505 { background-position: 55% 85% !important; }
        .emoji1f506 { background-position: 55% 87.5% !important; }
        .emoji1f507 { background-position: 55% 90% !important; }
        .emoji1f508 { background-position: 55% 92.5% !important; }
        .emoji1f509 { background-position: 55% 95% !important; }
        .emoji1f50a { background-position: 55% 97.5% !important; }
        .emoji1f50b { background-position: 55% 100% !important; }
        .emoji1f50c { background-position: 57.5% 0% !important; }
        .emoji1f50d { background-position: 57.5% 2.5% !important; }
        .emoji1f50e { background-position: 57.5% 5% !important; }
        .emoji1f50f { background-position: 57.5% 7.5% !important; }
        .emoji1f510 { background-position: 57.5% 10% !important; }
        .emoji1f511 { background-position: 57.5% 12.5% !important; }
        .emoji1f512 { background-position: 57.5% 15% !important; }
        .emoji1f513 { background-position: 57.5% 17.5% !important; }
        .emoji1f514 { background-position: 57.5% 20% !important; }
        .emoji1f515 { background-position: 57.5% 22.5% !important; }
        .emoji1f516 { background-position: 57.5% 25% !important; }
        .emoji1f517 { background-position: 57.5% 27.5% !important; }
        .emoji1f518 { background-position: 57.5% 30% !important; }
        .emoji1f519 { background-position: 57.5% 32.5% !important; }
        .emoji1f51a { background-position: 57.5% 35% !important; }
        .emoji1f51b { background-position: 57.5% 37.5% !important; }
        .emoji1f51c { background-position: 57.5% 40% !important; }
        .emoji1f51d { background-position: 57.5% 42.5% !important; }
        .emoji1f51e { background-position: 57.5% 45% !important; }
        .emoji1f51f { background-position: 57.5% 47.5% !important; }
        .emoji1f520 { background-position: 57.5% 50% !important; }
        .emoji1f521 { background-position: 57.5% 52.5% !important; }
        .emoji1f522 { background-position: 57.5% 55% !important; }
        .emoji1f523 { background-position: 57.5% 57.5% !important; }
        .emoji1f524 { background-position: 57.5% 60% !important; }
        .emoji1f525 { background-position: 57.5% 62.5% !important; }
        .emoji1f526 { background-position: 57.5% 65% !important; }
        .emoji1f527 { background-position: 57.5% 67.5% !important; }
        .emoji1f528 { background-position: 57.5% 70% !important; }
        .emoji1f529 { background-position: 57.5% 72.5% !important; }
        .emoji1f52a { background-position: 57.5% 75% !important; }
        .emoji1f52b { background-position: 57.5% 77.5% !important; }
        .emoji1f52c { background-position: 57.5% 80% !important; }
        .emoji1f52d { background-position: 57.5% 82.5% !important; }
        .emoji1f52e { background-position: 57.5% 85% !important; }
        .emoji1f52f { background-position: 57.5% 87.5% !important; }
        .emoji1f530 { background-position: 57.5% 90% !important; }
        .emoji1f531 { background-position: 57.5% 92.5% !important; }
        .emoji1f532 { background-position: 57.5% 95% !important; }
        .emoji1f533 { background-position: 57.5% 97.5% !important; }
        .emoji1f534 { background-position: 57.5% 100% !important; }
        .emoji1f535 { background-position: 60% 0% !important; }
        .emoji1f536 { background-position: 60% 2.5% !important; }
        .emoji1f537 { background-position: 60% 5% !important; }
        .emoji1f538 { background-position: 60% 7.5% !important; }
        .emoji1f539 { background-position: 60% 10% !important; }
        .emoji1f53a { background-position: 60% 12.5% !important; }
        .emoji1f53b { background-position: 60% 15% !important; }
        .emoji1f53c { background-position: 60% 17.5% !important; }
        .emoji1f53d { background-position: 60% 20% !important; }
        .emoji1f549 { background-position: 60% 22.5% !important; }
        .emoji1f54a { background-position: 60% 25% !important; }
        .emoji1f54b { background-position: 60% 27.5% !important; }
        .emoji1f54c { background-position: 60% 30% !important; }
        .emoji1f54d { background-position: 60% 32.5% !important; }
        .emoji1f54e { background-position: 60% 35% !important; }
        .emoji1f550 { background-position: 60% 37.5% !important; }
        .emoji1f551 { background-position: 60% 40% !important; }
        .emoji1f552 { background-position: 60% 42.5% !important; }
        .emoji1f553 { background-position: 60% 45% !important; }
        .emoji1f554 { background-position: 60% 47.5% !important; }
        .emoji1f555 { background-position: 60% 50% !important; }
        .emoji1f556 { background-position: 60% 52.5% !important; }
        .emoji1f557 { background-position: 60% 55% !important; }
        .emoji1f558 { background-position: 60% 57.5% !important; }
        .emoji1f559 { background-position: 60% 60% !important; }
        .emoji1f55a { background-position: 60% 62.5% !important; }
        .emoji1f55b { background-position: 60% 65% !important; }
        .emoji1f55c { background-position: 60% 67.5% !important; }
        .emoji1f55d { background-position: 60% 70% !important; }
        .emoji1f55e { background-position: 60% 72.5% !important; }
        .emoji1f55f { background-position: 60% 75% !important; }
        .emoji1f560 { background-position: 60% 77.5% !important; }
        .emoji1f561 { background-position: 60% 80% !important; }
        .emoji1f562 { background-position: 60% 82.5% !important; }
        .emoji1f563 { background-position: 60% 85% !important; }
        .emoji1f564 { background-position: 60% 87.5% !important; }
        .emoji1f565 { background-position: 60% 90% !important; }
        .emoji1f566 { background-position: 60% 92.5% !important; }
        .emoji1f567 { background-position: 60% 95% !important; }
        .emoji1f56f { background-position: 60% 97.5% !important; }
        .emoji1f570 { background-position: 60% 100% !important; }
        .emoji1f573 { background-position: 62.5% 0% !important; }
        .emoji1f574 { background-position: 62.5% 2.5% !important; }
        .emoji1f575 { background-position: 62.5% 5% !important; }
        .emoji1f576 { background-position: 62.5% 7.5% !important; }
        .emoji1f577 { background-position: 62.5% 10% !important; }
        .emoji1f578 { background-position: 62.5% 12.5% !important; }
        .emoji1f579 { background-position: 62.5% 15% !important; }
        .emoji1f587 { background-position: 62.5% 17.5% !important; }
        .emoji1f58a { background-position: 62.5% 20% !important; }
        .emoji1f58b { background-position: 62.5% 22.5% !important; }
        .emoji1f58c { background-position: 62.5% 25% !important; }
        .emoji1f58d { background-position: 62.5% 27.5% !important; }
        .emoji1f590 { background-position: 62.5% 30% !important; }
        .emoji1f595 { background-position: 62.5% 45% !important; }
        .emoji1f596 { background-position: 62.5% 60% !important; }
        .emoji1f5a5 { background-position: 62.5% 75% !important; }
        .emoji1f5a8 { background-position: 62.5% 77.5% !important; }
        .emoji1f5b1 { background-position: 62.5% 80% !important; }
        .emoji1f5b2 { background-position: 62.5% 82.5% !important; }
        .emoji1f5bc { background-position: 62.5% 85% !important; }
        .emoji1f5c2 { background-position: 62.5% 87.5% !important; }
        .emoji1f5c3 { background-position: 62.5% 90% !important; }
        .emoji1f5c4 { background-position: 62.5% 92.5% !important; }
        .emoji1f5d1 { background-position: 62.5% 95% !important; }
        .emoji1f5d2 { background-position: 62.5% 97.5% !important; }
        .emoji1f5d3 { background-position: 62.5% 100% !important; }
        .emoji1f5dc { background-position: 65% 0% !important; }
        .emoji1f5dd { background-position: 65% 2.5% !important; }
        .emoji1f5de { background-position: 65% 5% !important; }
        .emoji1f5e1 { background-position: 65% 7.5% !important; }
        .emoji1f5e3 { background-position: 65% 10% !important; }
        .emoji1f5e8 { background-position: 65% 12.5% !important; }
        .emoji1f5ef { background-position: 65% 15% !important; }
        .emoji1f5f3 { background-position: 65% 17.5% !important; }
        .emoji1f5fa { background-position: 65% 20% !important; }
        .emoji1f5fb { background-position: 65% 22.5% !important; }
        .emoji1f5fc { background-position: 65% 25% !important; }
        .emoji1f5fd { background-position: 65% 27.5% !important; }
        .emoji1f5fe { background-position: 65% 30% !important; }
        .emoji1f5ff { background-position: 65% 32.5% !important; }
        .emoji1f600 { background-position: 65% 35% !important; }
        .emoji1f601 { background-position: 65% 37.5% !important; }
        .emoji1f602 { background-position: 65% 40% !important; }
        .emoji1f603 { background-position: 65% 42.5% !important; }
        .emoji1f604 { background-position: 65% 45% !important; }
        .emoji1f605 { background-position: 65% 47.5% !important; }
        .emoji1f606 { background-position: 65% 50% !important; }
        .emoji1f607 { background-position: 65% 52.5% !important; }
        .emoji1f608 { background-position: 65% 55% !important; }
        .emoji1f609 { background-position: 65% 57.5% !important; }
        .emoji1f60a { background-position: 65% 60% !important; }
        .emoji1f60b { background-position: 65% 62.5% !important; }
        .emoji1f60c { background-position: 65% 65% !important; }
        .emoji1f60d { background-position: 65% 67.5% !important; }
        .emoji1f60e { background-position: 65% 70% !important; }
        .emoji1f60f { background-position: 65% 72.5% !important; }
        .emoji1f610 { background-position: 65% 75% !important; }
        .emoji1f611 { background-position: 65% 77.5% !important; }
        .emoji1f612 { background-position: 65% 80% !important; }
        .emoji1f613 { background-position: 65% 82.5% !important; }
        .emoji1f614 { background-position: 65% 85% !important; }
        .emoji1f615 { background-position: 65% 87.5% !important; }
        .emoji1f616 { background-position: 65% 90% !important; }
        .emoji1f617 { background-position: 65% 92.5% !important; }
        .emoji1f618 { background-position: 65% 95% !important; }
        .emoji1f619 { background-position: 65% 97.5% !important; }
        .emoji1f61a { background-position: 65% 100% !important; }
        .emoji1f61b { background-position: 67.5% 0% !important; }
        .emoji1f61c { background-position: 67.5% 2.5% !important; }
        .emoji1f61d { background-position: 67.5% 5% !important; }
        .emoji1f61e { background-position: 67.5% 7.5% !important; }
        .emoji1f61f { background-position: 67.5% 10% !important; }
        .emoji1f620 { background-position: 67.5% 12.5% !important; }
        .emoji1f621 { background-position: 67.5% 15% !important; }
        .emoji1f622 { background-position: 67.5% 17.5% !important; }
        .emoji1f623 { background-position: 67.5% 20% !important; }
        .emoji1f624 { background-position: 67.5% 22.5% !important; }
        .emoji1f625 { background-position: 67.5% 25% !important; }
        .emoji1f626 { background-position: 67.5% 27.5% !important; }
        .emoji1f627 { background-position: 67.5% 30% !important; }
        .emoji1f628 { background-position: 67.5% 32.5% !important; }
        .emoji1f629 { background-position: 67.5% 35% !important; }
        .emoji1f62a { background-position: 67.5% 37.5% !important; }
        .emoji1f62b { background-position: 67.5% 40% !important; }
        .emoji1f62c { background-position: 67.5% 42.5% !important; }
        .emoji1f62d { background-position: 67.5% 45% !important; }
        .emoji1f62e { background-position: 67.5% 47.5% !important; }
        .emoji1f62f { background-position: 67.5% 50% !important; }
        .emoji1f630 { background-position: 67.5% 52.5% !important; }
        .emoji1f631 { background-position: 67.5% 55% !important; }
        .emoji1f632 { background-position: 67.5% 57.5% !important; }
        .emoji1f633 { background-position: 67.5% 60% !important; }
        .emoji1f634 { background-position: 67.5% 62.5% !important; }
        .emoji1f635 { background-position: 67.5% 65% !important; }
        .emoji1f636 { background-position: 67.5% 67.5% !important; }
        .emoji1f637 { background-position: 67.5% 70% !important; }
        .emoji1f638 { background-position: 67.5% 72.5% !important; }
        .emoji1f639 { background-position: 67.5% 75% !important; }
        .emoji1f63a { background-position: 67.5% 77.5% !important; }
        .emoji1f63b { background-position: 67.5% 80% !important; }
        .emoji1f63c { background-position: 67.5% 82.5% !important; }
        .emoji1f63d { background-position: 67.5% 85% !important; }
        .emoji1f63e { background-position: 67.5% 87.5% !important; }
        .emoji1f63f { background-position: 67.5% 90% !important; }
        .emoji1f640 { background-position: 67.5% 92.5% !important; }
        .emoji1f641 { background-position: 67.5% 95% !important; }
        .emoji1f642 { background-position: 67.5% 97.5% !important; }
        .emoji1f643 { background-position: 67.5% 100% !important; }
        .emoji1f644 { background-position: 70% 0% !important; }
        .emoji1f645 { background-position: 70% 2.5% !important; }
        .emoji1f646 { background-position: 70% 17.5% !important; }
        .emoji1f647 { background-position: 70% 32.5% !important; }
        .emoji1f648 { background-position: 70% 47.5% !important; }
        .emoji1f649 { background-position: 70% 50% !important; }
        .emoji1f64a { background-position: 70% 52.5% !important; }
        .emoji1f64b { background-position: 70% 55% !important; }
        .emoji1f64c { background-position: 70% 70% !important; }
        .emoji1f64d { background-position: 70% 85% !important; }
        .emoji1f64e { background-position: 70% 100% !important; }
        .emoji1f64f { background-position: 72.5% 12.5% !important; }
        .emoji1f680 { background-position: 72.5% 27.5% !important; }
        .emoji1f681 { background-position: 72.5% 30% !important; }
        .emoji1f682 { background-position: 72.5% 32.5% !important; }
        .emoji1f683 { background-position: 72.5% 35% !important; }
        .emoji1f684 { background-position: 72.5% 37.5% !important; }
        .emoji1f685 { background-position: 72.5% 40% !important; }
        .emoji1f686 { background-position: 72.5% 42.5% !important; }
        .emoji1f687 { background-position: 72.5% 45% !important; }
        .emoji1f688 { background-position: 72.5% 47.5% !important; }
        .emoji1f689 { background-position: 72.5% 50% !important; }
        .emoji1f68a { background-position: 72.5% 52.5% !important; }
        .emoji1f68b { background-position: 72.5% 55% !important; }
        .emoji1f68c { background-position: 72.5% 57.5% !important; }
        .emoji1f68d { background-position: 72.5% 60% !important; }
        .emoji1f68e { background-position: 72.5% 62.5% !important; }
        .emoji1f68f { background-position: 72.5% 65% !important; }
        .emoji1f690 { background-position: 72.5% 67.5% !important; }
        .emoji1f691 { background-position: 72.5% 70% !important; }
        .emoji1f692 { background-position: 72.5% 72.5% !important; }
        .emoji1f693 { background-position: 72.5% 75% !important; }
        .emoji1f694 { background-position: 72.5% 77.5% !important; }
        .emoji1f695 { background-position: 72.5% 80% !important; }
        .emoji1f696 { background-position: 72.5% 82.5% !important; }
        .emoji1f697 { background-position: 72.5% 85% !important; }
        .emoji1f698 { background-position: 72.5% 87.5% !important; }
        .emoji1f699 { background-position: 72.5% 90% !important; }
        .emoji1f69a { background-position: 72.5% 92.5% !important; }
        .emoji1f69b { background-position: 72.5% 95% !important; }
        .emoji1f69c { background-position: 72.5% 97.5% !important; }
        .emoji1f69d { background-position: 72.5% 100% !important; }
        .emoji1f69e { background-position: 75% 0% !important; }
        .emoji1f69f { background-position: 75% 2.5% !important; }
        .emoji1f6a0 { background-position: 75% 5% !important; }
        .emoji1f6a1 { background-position: 75% 7.5% !important; }
        .emoji1f6a2 { background-position: 75% 10% !important; }
        .emoji1f6a3 { background-position: 75% 12.5% !important; }
        .emoji1f6a4 { background-position: 75% 27.5% !important; }
        .emoji1f6a5 { background-position: 75% 30% !important; }
        .emoji1f6a6 { background-position: 75% 32.5% !important; }
        .emoji1f6a7 { background-position: 75% 35% !important; }
        .emoji1f6a8 { background-position: 75% 37.5% !important; }
        .emoji1f6a9 { background-position: 75% 40% !important; }
        .emoji1f6aa { background-position: 75% 42.5% !important; }
        .emoji1f6ab { background-position: 75% 45% !important; }
        .emoji1f6ac { background-position: 75% 47.5% !important; }
        .emoji1f6ad { background-position: 75% 50% !important; }
        .emoji1f6ae { background-position: 75% 52.5% !important; }
        .emoji1f6af { background-position: 75% 55% !important; }
        .emoji1f6b0 { background-position: 75% 57.5% !important; }
        .emoji1f6b1 { background-position: 75% 60% !important; }
        .emoji1f6b2 { background-position: 75% 62.5% !important; }
        .emoji1f6b3 { background-position: 75% 65% !important; }
        .emoji1f6b4 { background-position: 75% 67.5% !important; }
        .emoji1f6b5 { background-position: 75% 82.5% !important; }
        .emoji1f6b6 { background-position: 75% 97.5% !important; }
        .emoji1f6b7 { background-position: 77.5% 10% !important; }
        .emoji1f6b8 { background-position: 77.5% 12.5% !important; }
        .emoji1f6b9 { background-position: 77.5% 15% !important; }
        .emoji1f6ba { background-position: 77.5% 17.5% !important; }
        .emoji1f6bb { background-position: 77.5% 20% !important; }
        .emoji1f6bc { background-position: 77.5% 22.5% !important; }
        .emoji1f6bd { background-position: 77.5% 25% !important; }
        .emoji1f6be { background-position: 77.5% 27.5% !important; }
        .emoji1f6bf { background-position: 77.5% 30% !important; }
        .emoji1f6c0 { background-position: 77.5% 32.5% !important; }
        .emoji1f6c1 { background-position: 77.5% 47.5% !important; }
        .emoji1f6c2 { background-position: 77.5% 50% !important; }
        .emoji1f6c3 { background-position: 77.5% 52.5% !important; }
        .emoji1f6c4 { background-position: 77.5% 55% !important; }
        .emoji1f6c5 { background-position: 77.5% 57.5% !important; }
        .emoji1f6cb { background-position: 77.5% 60% !important; }
        .emoji1f6cc { background-position: 77.5% 62.5% !important; }
        .emoji1f6cd { background-position: 77.5% 65% !important; }
        .emoji1f6ce { background-position: 77.5% 67.5% !important; }
        .emoji1f6cf { background-position: 77.5% 70% !important; }
        .emoji1f6d0 { background-position: 77.5% 72.5% !important; }
        .emoji1f6e0 { background-position: 77.5% 75% !important; }
        .emoji1f6e1 { background-position: 77.5% 77.5% !important; }
        .emoji1f6e2 { background-position: 77.5% 80% !important; }
        .emoji1f6e3 { background-position: 77.5% 82.5% !important; }
        .emoji1f6e4 { background-position: 77.5% 85% !important; }
        .emoji1f6e5 { background-position: 77.5% 87.5% !important; }
        .emoji1f6e9 { background-position: 77.5% 90% !important; }
        .emoji1f6eb { background-position: 77.5% 92.5% !important; }
        .emoji1f6ec { background-position: 77.5% 95% !important; }
        .emoji1f6f0 { background-position: 77.5% 97.5% !important; }
        .emoji1f6f3 { background-position: 77.5% 100% !important; }
        .emoji1f910 { background-position: 80% 0% !important; }
        .emoji1f911 { background-position: 80% 2.5% !important; }
        .emoji1f912 { background-position: 80% 5% !important; }
        .emoji1f913 { background-position: 80% 7.5% !important; }
        .emoji1f914 { background-position: 80% 10% !important; }
        .emoji1f915 { background-position: 80% 12.5% !important; }
        .emoji1f916 { background-position: 80% 15% !important; }
        .emoji1f917 { background-position: 80% 17.5% !important; }
        .emoji1f918 { background-position: 80% 20% !important; }
        .emoji1f980 { background-position: 80% 35% !important; }
        .emoji1f981 { background-position: 80% 37.5% !important; }
        .emoji1f982 { background-position: 80% 40% !important; }
        .emoji1f983 { background-position: 80% 42.5% !important; }
        .emoji1f984 { background-position: 80% 45% !important; }
        .emoji1f9c0 { background-position: 80% 47.5% !important; }
        .emoji2320e3 { background-position: 80% 50% !important; }
        .emoji2a20e3 { background-position: 80% 52.5% !important; }
        .emoji3020e3 { background-position: 80% 55% !important; }
        .emoji3120e3 { background-position: 80% 57.5% !important; }
        .emoji3220e3 { background-position: 80% 60% !important; }
        .emoji3320e3 { background-position: 80% 62.5% !important; }
        .emoji3420e3 { background-position: 80% 65% !important; }
        .emoji3520e3 { background-position: 80% 67.5% !important; }
        .emoji3620e3 { background-position: 80% 70% !important; }
        .emoji3720e3 { background-position: 80% 72.5% !important; }
        .emoji3820e3 { background-position: 80% 75% !important; }
        .emoji3920e3 { background-position: 80% 77.5% !important; }
        .emoji1f1e61f1e8 { background-position: 80% 80% !important; }
        .emoji1f1e61f1e9 { background-position: 80% 82.5% !important; }
        .emoji1f1e61f1ea { background-position: 80% 85% !important; }
        .emoji1f1e61f1eb { background-position: 80% 87.5% !important; }
        .emoji1f1e61f1ec { background-position: 80% 90% !important; }
        .emoji1f1e61f1ee { background-position: 80% 92.5% !important; }
        .emoji1f1e61f1f1 { background-position: 80% 95% !important; }
        .emoji1f1e61f1f2 { background-position: 80% 97.5% !important; }
        .emoji1f1e61f1f4 { background-position: 80% 100% !important; }
        .emoji1f1e61f1f6 { background-position: 82.5% 0% !important; }
        .emoji1f1e61f1f7 { background-position: 82.5% 2.5% !important; }
        .emoji1f1e61f1f8 { background-position: 82.5% 5% !important; }
        .emoji1f1e61f1f9 { background-position: 82.5% 7.5% !important; }
        .emoji1f1e61f1fa { background-position: 82.5% 10% !important; }
        .emoji1f1e61f1fc { background-position: 82.5% 12.5% !important; }
        .emoji1f1e61f1fd { background-position: 82.5% 15% !important; }
        .emoji1f1e61f1ff { background-position: 82.5% 17.5% !important; }
        .emoji1f1e71f1e6 { background-position: 82.5% 20% !important; }
        .emoji1f1e71f1e7 { background-position: 82.5% 22.5% !important; }
        .emoji1f1e71f1e9 { background-position: 82.5% 25% !important; }
        .emoji1f1e71f1ea { background-position: 82.5% 27.5% !important; }
        .emoji1f1e71f1eb { background-position: 82.5% 30% !important; }
        .emoji1f1e71f1ec { background-position: 82.5% 32.5% !important; }
        .emoji1f1e71f1ed { background-position: 82.5% 35% !important; }
        .emoji1f1e71f1ee { background-position: 82.5% 37.5% !important; }
        .emoji1f1e71f1ef { background-position: 82.5% 40% !important; }
        .emoji1f1e71f1f1 { background-position: 82.5% 42.5% !important; }
        .emoji1f1e71f1f2 { background-position: 82.5% 45% !important; }
        .emoji1f1e71f1f3 { background-position: 82.5% 47.5% !important; }
        .emoji1f1e71f1f4 { background-position: 82.5% 50% !important; }
        .emoji1f1e71f1f6 { background-position: 82.5% 52.5% !important; }
        .emoji1f1e71f1f7 { background-position: 82.5% 55% !important; }
        .emoji1f1e71f1f8 { background-position: 82.5% 57.5% !important; }
        .emoji1f1e71f1f9 { background-position: 82.5% 60% !important; }
        .emoji1f1e71f1fb { background-position: 82.5% 62.5% !important; }
        .emoji1f1e71f1fc { background-position: 82.5% 65% !important; }
        .emoji1f1e71f1fe { background-position: 82.5% 67.5% !important; }
        .emoji1f1e71f1ff { background-position: 82.5% 70% !important; }
        .emoji1f1e81f1e6 { background-position: 82.5% 72.5% !important; }
        .emoji1f1e81f1e8 { background-position: 82.5% 75% !important; }
        .emoji1f1e81f1e9 { background-position: 82.5% 77.5% !important; }
        .emoji1f1e81f1eb { background-position: 82.5% 80% !important; }
        .emoji1f1e81f1ec { background-position: 82.5% 82.5% !important; }
        .emoji1f1e81f1ed { background-position: 82.5% 85% !important; }
        .emoji1f1e81f1ee { background-position: 82.5% 87.5% !important; }
        .emoji1f1e81f1f0 { background-position: 82.5% 90% !important; }
        .emoji1f1e81f1f1 { background-position: 82.5% 92.5% !important; }
        .emoji1f1e81f1f2 { background-position: 82.5% 95% !important; }
        .emoji1f1e81f1f3 { background-position: 82.5% 97.5% !important; }
        .emoji1f1e81f1f4 { background-position: 82.5% 100% !important; }
        .emoji1f1e81f1f5 { background-position: 85% 0% !important; }
        .emoji1f1e81f1f7 { background-position: 85% 2.5% !important; }
        .emoji1f1e81f1fa { background-position: 85% 5% !important; }
        .emoji1f1e81f1fb { background-position: 85% 7.5% !important; }
        .emoji1f1e81f1fc { background-position: 85% 10% !important; }
        .emoji1f1e81f1fd { background-position: 85% 12.5% !important; }
        .emoji1f1e81f1fe { background-position: 85% 15% !important; }
        .emoji1f1e81f1ff { background-position: 85% 17.5% !important; }
        .emoji1f1e91f1ea { background-position: 85% 20% !important; }
        .emoji1f1e91f1ec { background-position: 85% 22.5% !important; }
        .emoji1f1e91f1ef { background-position: 85% 25% !important; }
        .emoji1f1e91f1f0 { background-position: 85% 27.5% !important; }
        .emoji1f1e91f1f2 { background-position: 85% 30% !important; }
        .emoji1f1e91f1f4 { background-position: 85% 32.5% !important; }
        .emoji1f1e91f1ff { background-position: 85% 35% !important; }
        .emoji1f1ea1f1e6 { background-position: 85% 37.5% !important; }
        .emoji1f1ea1f1e8 { background-position: 85% 40% !important; }
        .emoji1f1ea1f1ea { background-position: 85% 42.5% !important; }
        .emoji1f1ea1f1ec { background-position: 85% 45% !important; }
        .emoji1f1ea1f1ed { background-position: 85% 47.5% !important; }
        .emoji1f1ea1f1f7 { background-position: 85% 50% !important; }
        .emoji1f1ea1f1f8 { background-position: 85% 52.5% !important; }
        .emoji1f1ea1f1f9 { background-position: 85% 55% !important; }
        .emoji1f1ea1f1fa { background-position: 85% 57.5% !important; }
        .emoji1f1eb1f1ee { background-position: 85% 60% !important; }
        .emoji1f1eb1f1ef { background-position: 85% 62.5% !important; }
        .emoji1f1eb1f1f0 { background-position: 85% 65% !important; }
        .emoji1f1eb1f1f2 { background-position: 85% 67.5% !important; }
        .emoji1f1eb1f1f4 { background-position: 85% 70% !important; }
        .emoji1f1eb1f1f7 { background-position: 85% 72.5% !important; }
        .emoji1f1ec1f1e6 { background-position: 85% 75% !important; }
        .emoji1f1ec1f1e7 { background-position: 85% 77.5% !important; }
        .emoji1f1ec1f1e9 { background-position: 85% 80% !important; }
        .emoji1f1ec1f1ea { background-position: 85% 82.5% !important; }
        .emoji1f1ec1f1eb { background-position: 85% 85% !important; }
        .emoji1f1ec1f1ec { background-position: 85% 87.5% !important; }
        .emoji1f1ec1f1ed { background-position: 85% 90% !important; }
        .emoji1f1ec1f1ee { background-position: 85% 92.5% !important; }
        .emoji1f1ec1f1f1 { background-position: 85% 95% !important; }
        .emoji1f1ec1f1f2 { background-position: 85% 97.5% !important; }
        .emoji1f1ec1f1f3 { background-position: 85% 100% !important; }
        .emoji1f1ec1f1f5 { background-position: 87.5% 0% !important; }
        .emoji1f1ec1f1f6 { background-position: 87.5% 2.5% !important; }
        .emoji1f1ec1f1f7 { background-position: 87.5% 5% !important; }
        .emoji1f1ec1f1f8 { background-position: 87.5% 7.5% !important; }
        .emoji1f1ec1f1f9 { background-position: 87.5% 10% !important; }
        .emoji1f1ec1f1fa { background-position: 87.5% 12.5% !important; }
        .emoji1f1ec1f1fc { background-position: 87.5% 15% !important; }
        .emoji1f1ec1f1fe { background-position: 87.5% 17.5% !important; }
        .emoji1f1ed1f1f0 { background-position: 87.5% 20% !important; }
        .emoji1f1ed1f1f2 { background-position: 87.5% 22.5% !important; }
        .emoji1f1ed1f1f3 { background-position: 87.5% 25% !important; }
        .emoji1f1ed1f1f7 { background-position: 87.5% 27.5% !important; }
        .emoji1f1ed1f1f9 { background-position: 87.5% 30% !important; }
        .emoji1f1ed1f1fa { background-position: 87.5% 32.5% !important; }
        .emoji1f1ee1f1e8 { background-position: 87.5% 35% !important; }
        .emoji1f1ee1f1e9 { background-position: 87.5% 37.5% !important; }
        .emoji1f1ee1f1ea { background-position: 87.5% 40% !important; }
        .emoji1f1ee1f1f1 { background-position: 87.5% 42.5% !important; }
        .emoji1f1ee1f1f2 { background-position: 87.5% 45% !important; }
        .emoji1f1ee1f1f3 { background-position: 87.5% 47.5% !important; }
        .emoji1f1ee1f1f4 { background-position: 87.5% 50% !important; }
        .emoji1f1ee1f1f6 { background-position: 87.5% 52.5% !important; }
        .emoji1f1ee1f1f7 { background-position: 87.5% 55% !important; }
        .emoji1f1ee1f1f8 { background-position: 87.5% 57.5% !important; }
        .emoji1f1ee1f1f9 { background-position: 87.5% 60% !important; }
        .emoji1f1ef1f1ea { background-position: 87.5% 62.5% !important; }
        .emoji1f1ef1f1f2 { background-position: 87.5% 65% !important; }
        .emoji1f1ef1f1f4 { background-position: 87.5% 67.5% !important; }
        .emoji1f1ef1f1f5 { background-position: 87.5% 70% !important; }
        .emoji1f1f01f1ea { background-position: 87.5% 72.5% !important; }
        .emoji1f1f01f1ec { background-position: 87.5% 75% !important; }
        .emoji1f1f01f1ed { background-position: 87.5% 77.5% !important; }
        .emoji1f1f01f1ee { background-position: 87.5% 80% !important; }
        .emoji1f1f01f1f2 { background-position: 87.5% 82.5% !important; }
        .emoji1f1f01f1f3 { background-position: 87.5% 85% !important; }
        .emoji1f1f01f1f5 { background-position: 87.5% 87.5% !important; }
        .emoji1f1f01f1f7 { background-position: 87.5% 90% !important; }
        .emoji1f1f01f1fc { background-position: 87.5% 92.5% !important; }
        .emoji1f1f01f1fe { background-position: 87.5% 95% !important; }
        .emoji1f1f01f1ff { background-position: 87.5% 97.5% !important; }
        .emoji1f1f11f1e6 { background-position: 87.5% 100% !important; }
        .emoji1f1f11f1e7 { background-position: 90% 0% !important; }
        .emoji1f1f11f1e8 { background-position: 90% 2.5% !important; }
        .emoji1f1f11f1ee { background-position: 90% 5% !important; }
        .emoji1f1f11f1f0 { background-position: 90% 7.5% !important; }
        .emoji1f1f11f1f7 { background-position: 90% 10% !important; }
        .emoji1f1f11f1f8 { background-position: 90% 12.5% !important; }
        .emoji1f1f11f1f9 { background-position: 90% 15% !important; }
        .emoji1f1f11f1fa { background-position: 90% 17.5% !important; }
        .emoji1f1f11f1fb { background-position: 90% 20% !important; }
        .emoji1f1f11f1fe { background-position: 90% 22.5% !important; }
        .emoji1f1f21f1e6 { background-position: 90% 25% !important; }
        .emoji1f1f21f1e8 { background-position: 90% 27.5% !important; }
        .emoji1f1f21f1e9 { background-position: 90% 30% !important; }
        .emoji1f1f21f1ea { background-position: 90% 32.5% !important; }
        .emoji1f1f21f1eb { background-position: 90% 35% !important; }
        .emoji1f1f21f1ec { background-position: 90% 37.5% !important; }
        .emoji1f1f21f1ed { background-position: 90% 40% !important; }
        .emoji1f1f21f1f0 { background-position: 90% 42.5% !important; }
        .emoji1f1f21f1f1 { background-position: 90% 45% !important; }
        .emoji1f1f21f1f2 { background-position: 90% 47.5% !important; }
        .emoji1f1f21f1f3 { background-position: 90% 50% !important; }
        .emoji1f1f21f1f4 { background-position: 90% 52.5% !important; }
        .emoji1f1f21f1f5 { background-position: 90% 55% !important; }
        .emoji1f1f21f1f6 { background-position: 90% 57.5% !important; }
        .emoji1f1f21f1f7 { background-position: 90% 60% !important; }
        .emoji1f1f21f1f8 { background-position: 90% 62.5% !important; }
        .emoji1f1f21f1f9 { background-position: 90% 65% !important; }
        .emoji1f1f21f1fa { background-position: 90% 67.5% !important; }
        .emoji1f1f21f1fb { background-position: 90% 70% !important; }
        .emoji1f1f21f1fc { background-position: 90% 72.5% !important; }
        .emoji1f1f21f1fd { background-position: 90% 75% !important; }
        .emoji1f1f21f1fe { background-position: 90% 77.5% !important; }
        .emoji1f1f21f1ff { background-position: 90% 80% !important; }
        .emoji1f1f31f1e6 { background-position: 90% 82.5% !important; }
        .emoji1f1f31f1e8 { background-position: 90% 85% !important; }
        .emoji1f1f31f1ea { background-position: 90% 87.5% !important; }
        .emoji1f1f31f1eb { background-position: 90% 90% !important; }
        .emoji1f1f31f1ec { background-position: 90% 92.5% !important; }
        .emoji1f1f31f1ee { background-position: 90% 95% !important; }
        .emoji1f1f31f1f1 { background-position: 90% 97.5% !important; }
        .emoji1f1f31f1f4 { background-position: 90% 100% !important; }
        .emoji1f1f31f1f5 { background-position: 92.5% 0% !important; }
        .emoji1f1f31f1f7 { background-position: 92.5% 2.5% !important; }
        .emoji1f1f31f1fa { background-position: 92.5% 5% !important; }
        .emoji1f1f31f1ff { background-position: 92.5% 7.5% !important; }
        .emoji1f1f41f1f2 { background-position: 92.5% 10% !important; }
        .emoji1f1f51f1e6 { background-position: 92.5% 12.5% !important; }
        .emoji1f1f51f1ea { background-position: 92.5% 15% !important; }
        .emoji1f1f51f1eb { background-position: 92.5% 17.5% !important; }
        .emoji1f1f51f1ec { background-position: 92.5% 20% !important; }
        .emoji1f1f51f1ed { background-position: 92.5% 22.5% !important; }
        .emoji1f1f51f1f0 { background-position: 92.5% 25% !important; }
        .emoji1f1f51f1f1 { background-position: 92.5% 27.5% !important; }
        .emoji1f1f51f1f2 { background-position: 92.5% 30% !important; }
        .emoji1f1f51f1f3 { background-position: 92.5% 32.5% !important; }
        .emoji1f1f51f1f7 { background-position: 92.5% 35% !important; }
        .emoji1f1f51f1f8 { background-position: 92.5% 37.5% !important; }
        .emoji1f1f51f1f9 { background-position: 92.5% 40% !important; }
        .emoji1f1f51f1fc { background-position: 92.5% 42.5% !important; }
        .emoji1f1f51f1fe { background-position: 92.5% 45% !important; }
        .emoji1f1f61f1e6 { background-position: 92.5% 47.5% !important; }
        .emoji1f1f71f1ea { background-position: 92.5% 50% !important; }
        .emoji1f1f71f1f4 { background-position: 92.5% 52.5% !important; }
        .emoji1f1f71f1f8 { background-position: 92.5% 55% !important; }
        .emoji1f1f71f1fa { background-position: 92.5% 57.5% !important; }
        .emoji1f1f71f1fc { background-position: 92.5% 60% !important; }
        .emoji1f1f81f1e6 { background-position: 92.5% 62.5% !important; }
        .emoji1f1f81f1e7 { background-position: 92.5% 65% !important; }
        .emoji1f1f81f1e8 { background-position: 92.5% 67.5% !important; }
        .emoji1f1f81f1e9 { background-position: 92.5% 70% !important; }
        .emoji1f1f81f1ea { background-position: 92.5% 72.5% !important; }
        .emoji1f1f81f1ec { background-position: 92.5% 75% !important; }
        .emoji1f1f81f1ed { background-position: 92.5% 77.5% !important; }
        .emoji1f1f81f1ee { background-position: 92.5% 80% !important; }
        .emoji1f1f81f1ef { background-position: 92.5% 82.5% !important; }
        .emoji1f1f81f1f0 { background-position: 92.5% 85% !important; }
        .emoji1f1f81f1f1 { background-position: 92.5% 87.5% !important; }
        .emoji1f1f81f1f2 { background-position: 92.5% 90% !important; }
        .emoji1f1f81f1f3 { background-position: 92.5% 92.5% !important; }
        .emoji1f1f81f1f4 { background-position: 92.5% 95% !important; }
        .emoji1f1f81f1f7 { background-position: 92.5% 97.5% !important; }
        .emoji1f1f81f1f8 { background-position: 92.5% 100% !important; }
        .emoji1f1f81f1f9 { background-position: 95% 0% !important; }
        .emoji1f1f81f1fb { background-position: 95% 2.5% !important; }
        .emoji1f1f81f1fd { background-position: 95% 5% !important; }
        .emoji1f1f81f1fe { background-position: 95% 7.5% !important; }
        .emoji1f1f81f1ff { background-position: 95% 10% !important; }
        .emoji1f1f91f1e6 { background-position: 95% 12.5% !important; }
        .emoji1f1f91f1e8 { background-position: 95% 15% !important; }
        .emoji1f1f91f1e9 { background-position: 95% 17.5% !important; }
        .emoji1f1f91f1eb { background-position: 95% 20% !important; }
        .emoji1f1f91f1ec { background-position: 95% 22.5% !important; }
        .emoji1f1f91f1ed { background-position: 95% 25% !important; }
        .emoji1f1f91f1ef { background-position: 95% 27.5% !important; }
        .emoji1f1f91f1f0 { background-position: 95% 30% !important; }
        .emoji1f1f91f1f1 { background-position: 95% 32.5% !important; }
        .emoji1f1f91f1f2 { background-position: 95% 35% !important; }
        .emoji1f1f91f1f3 { background-position: 95% 37.5% !important; }
        .emoji1f1f91f1f4 { background-position: 95% 40% !important; }
        .emoji1f1f91f1f7 { background-position: 95% 42.5% !important; }
        .emoji1f1f91f1f9 { background-position: 95% 45% !important; }
        .emoji1f1f91f1fb { background-position: 95% 47.5% !important; }
        .emoji1f1f91f1fc { background-position: 95% 50% !important; }
        .emoji1f1f91f1ff { background-position: 95% 52.5% !important; }
        .emoji1f1fa1f1e6 { background-position: 95% 55% !important; }
        .emoji1f1fa1f1ec { background-position: 95% 57.5% !important; }
        .emoji1f1fa1f1f2 { background-position: 95% 60% !important; }
        .emoji1f1fa1f1f8 { background-position: 95% 62.5% !important; }
        .emoji1f1fa1f1fe { background-position: 95% 65% !important; }
        .emoji1f1fa1f1ff { background-position: 95% 67.5% !important; }
        .emoji1f1fb1f1e6 { background-position: 95% 70% !important; }
        .emoji1f1fb1f1e8 { background-position: 95% 72.5% !important; }
        .emoji1f1fb1f1ea { background-position: 95% 75% !important; }
        .emoji1f1fb1f1ec { background-position: 95% 77.5% !important; }
        .emoji1f1fb1f1ee { background-position: 95% 80% !important; }
        .emoji1f1fb1f1f3 { background-position: 95% 82.5% !important; }
        .emoji1f1fb1f1fa { background-position: 95% 85% !important; }
        .emoji1f1fc1f1eb { background-position: 95% 87.5% !important; }
        .emoji1f1fc1f1f8 { background-position: 95% 90% !important; }
        .emoji1f1fd1f1f0 { background-position: 95% 92.5% !important; }
        .emoji1f1fe1f1ea { background-position: 95% 95% !important; }
        .emoji1f1fe1f1f9 { background-position: 95% 97.5% !important; }
        .emoji1f1ff1f1e6 { background-position: 95% 100% !important; }
        .emoji1f1ff1f1f2 { background-position: 97.5% 0% !important; }
        .emoji1f1ff1f1fc { background-position: 97.5% 2.5% !important; }
        .emoji1f468200d1f468200d1f466 { background-position: 97.5% 5% !important; }
        .emoji1f468200d1f468200d1f466200d1f466 { background-position: 97.5% 7.5% !important; }
        .emoji1f468200d1f468200d1f467 { background-position: 97.5% 10% !important; }
        .emoji1f468200d1f468200d1f467200d1f466 { background-position: 97.5% 12.5% !important; }
        .emoji1f468200d1f468200d1f467200d1f467 { background-position: 97.5% 15% !important; }
        .emoji1f468200d1f469200d1f466200d1f466 { background-position: 97.5% 17.5% !important; }
        .emoji1f468200d1f469200d1f467 { background-position: 97.5% 20% !important; }
        .emoji1f468200d1f469200d1f467200d1f466 { background-position: 97.5% 22.5% !important; }
        .emoji1f468200d1f469200d1f467200d1f467 { background-position: 97.5% 25% !important; }
        .emoji1f468200d2764fe0f200d1f468 { background-position: 97.5% 27.5% !important; }
        .emoji1f468200d2764fe0f200d1f48b200d1f468 { background-position: 97.5% 30% !important; }
        .emoji1f469200d1f469200d1f466 { background-position: 97.5% 32.5% !important; }
        .emoji1f469200d1f469200d1f466200d1f466 { background-position: 97.5% 35% !important; }
        .emoji1f469200d1f469200d1f467 { background-position: 97.5% 37.5% !important; }
        .emoji1f469200d1f469200d1f467200d1f466 { background-position: 97.5% 40% !important; }
        .emoji1f469200d1f469200d1f467200d1f467 { background-position: 97.5% 42.5% !important; }
        .emoji1f469200d2764fe0f200d1f469 { background-position: 97.5% 45% !important; }
        .emoji1f469200d2764fe0f200d1f48b200d1f469 { background-position: 97.5% 47.5% !important; }

        body {

        }

        @page {
            margin: 1cm 1cm;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;
            font-size: 12px;
            color: black;
            text-align: center;
        }
    </style>
    <div style="clear:both; margin-top: 20px;">
        <div style="width: 85%; float: left;">
            <h6 style="margin-bottom: 0px; padding-bottom: 5px; margin-top: 26px; font-size: 17px; border-bottom: 3px solid #b5b4b4;">{{ $nome }}</h6>
            <p style="color: #eb8e06; margin: 0;"><strong>Período: {{ $dt_inicial }} à {{ $dt_final }}</strong></p>
            <p style="color: #eb8e06; margin: 0; margin-top: -3px;">{{ session('cliente')['nome'] }}</p>
            <p style="color: #eb8e06; margin: 0; margin-top: -3px;">FORAM COLETADOS {{ count($dados) }} POSTS</p>
        </div>
        <div style="width: 15%; float: right; text-align: right;">
            <img style="width: 90%" src='data:image/png;base64,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' alt="" />
        </div>
    </div>
    <div style="clear:both; margin-top: 150px; ">
        @for($i = 0; $i < count($dados); $i++)

            <div class="mb-2">

                <div style="position: relative;">
                    {!! $dados[$i]['tipo'] !!}
                    <span style="position: absolute; top: -5px; font-size: 12px;">{{ $dados[$i]['username'] }}</span>
                    <span class="pull-right" style="font-size: 16px;">
                        {!! $dados[$i]['sentimento'] !!}
                    </span>
                </div>

                <p style="font-size: 12px;">{!! $dados[$i]['text'] !!}</p>

                <span class="badge badge-pill badge-primary">
                    <i class="fa fa-thumbs-up"></i> {{ $dados[$i]['like_count'] }}
                </span>

                <span class="badge badge-pill badge-warning">
                    <i class="fa fa-link text-white"></i> <a href="{{ $dados[$i]['link'] }}" target="_blank" >Post</a>
                </span>
                <span class="float-right" style="font-size: 12px;">{{ Carbon\Carbon::parse($dados[$i]['created_at'])->format('d/m/Y H:i') }}</span>
            </div>

            <hr/>

        @endfor
    </div>
    <footer>
        Relatório gerado em {{ date("d/m/Y") }} às {{ date("H:i:s") }}
    </footer>
@endsection
