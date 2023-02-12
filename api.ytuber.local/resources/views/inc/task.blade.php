@if ($flash = session('status'))
    <div class="clearfix">
    <div class="alert alert-warning no-border" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <span class="text-semibold">{{ $flash }}</span>
    </div>
    </div>
@endif