@extends('layouts.app')

@section('content')
    @auth
        <div id="app">
            <router-view name="FloatNav"></router-view>
            <router-view name="VideoPlayer"></router-view>
            <router-view></router-view>
        </div>
    @endauth
    @guest
        @include('inc.messages')
        <router-view name="Login"></router-view>
    @endguest
@endsection
