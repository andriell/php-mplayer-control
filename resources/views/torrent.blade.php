@extends('layouts.app')

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($torrents as $torrent)
            <tr>
                <td>{{ $torrent['id'] }}</td>
                <td>{{ $torrent['name'] }}</td>
                <td>{{ $torrent['status'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

