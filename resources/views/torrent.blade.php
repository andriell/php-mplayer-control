@extends('layouts.app')

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Status</th>
            <th>doneDate</th>
            <th>haveValid</th>
            <th>totalSize</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($torrents as $torrent)
            <tr>
                <td>{{ $torrent['id'] }}</td>
                <td>{{ $torrent['name'] }}</td>
                <td>{{ $torrent['status'] }}</td>
                <td>{{ $torrent['doneDate'] }}</td>
                <td>{{ $torrent['haveValid'] }}</td>
                <td>{{ $torrent['totalSize'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

