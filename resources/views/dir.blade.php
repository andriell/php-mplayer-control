@extends('layouts.app')

@section('content')

<style>
    .item {
        display: inline-block;
        vertical-align: bottom;
        height: 140px;
        width: 128px;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="span4">
                    @forelse($files as $file)
                        <div class="item">{{ $file['name'] }}</div>
                    @empty
                        <p>No users</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
