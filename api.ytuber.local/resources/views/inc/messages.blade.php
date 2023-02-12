@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger no-border">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
            <span class="text-semibold"><i class="icon-cancel-circle2 position-left"></i>  {{$error}}</span>
        </div>
@endforeach
    @endif
@if (session('success'))
    <div class="alert alert-success no-border">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <span class="text-semibold">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger no-border">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"><i class="icon-cancel-circle2 position-left"></i>   {{ session('error') }}</span>
    </div>
    @endif
@if ($flash = session('logout'))
    <div class="alert alert-warning no-border">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <span class="text-semibold">{{ $flash }}</span>
    </div>
@endif
