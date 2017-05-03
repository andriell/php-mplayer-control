@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Название</th>
                <th>Статус</th>
                <th>Дата</th>
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
    </div>
@endsection

