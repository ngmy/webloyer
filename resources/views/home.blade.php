@extends('app')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Breadcrumbs::render('index') !!}
        </div>
    </div>
</div>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
