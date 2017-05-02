@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Диск: {{ $disc_free_f }} / {{ $disc_total_f }}
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $disc_p }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
