@extends('layouts.app')

@section('content')
  <!-- Page container -->
  <div class="login-container pace-done">

    <!-- Content area -->
    <div class="page-container">
      <h1>Home page</h1>
      @auth()
        <div id="app">
          <router-view name="floatnav"></router-view>
          <router-view name="Filter"></router-view>
          <router-view name="videoplayer"></router-view>
          <router-view></router-view>
        </div>
      @endauth
    </div>
    <!-- /content area -->

  </div>
  <!-- /page container -->

@endsection
