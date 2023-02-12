@extends('layouts.app')

@section('content')
    <!-- Page container -->
    <div class="login-container pace-done">

                <!-- Content area -->
                <div class="page-container">

                    <!-- Advanced login -->

						<form method="POST" action="/api/register" class="form-horizontal form-validate-jquery">
							@csrf
                        <div class="panel panel-body login-form">
                            <div class="text-center">
                                <div class="icon-object border-success text-success"><i class="icon-plus3"></i></div>
                                <h5 class="content-group">Create account <small class="display-block">One field are required</small></h5>
                            </div>

                            <div class="content-divider text-muted form-group"><span>Your credentials</span></div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control" placeholder="Your email" name="email">
                                <div class="form-control-feedback">
                                    <i class="icon-mention text-muted"></i>
                                </div>
                            </div>


                            <div class="content-divider text-muted form-group"><span>Additions</span></div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="styled">
                                        Accept <a href="#">terms of service</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn bg-teal btn-block btn-lg">Register <i class="icon-circle-right2 position-right"></i></button>
                        </div>
                </form>
                    <!-- /advanced login -->


                </div>
                <!-- /content area -->

    </div>
    <!-- /page container -->
@endsection
