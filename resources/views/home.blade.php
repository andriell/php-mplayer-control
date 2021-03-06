@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Состояние сервера</div>

                <div class="panel-body">
                    Диск: {{ $disc_used_f }} / {{ $disc_total_f }}
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $disc_p }}%;">{{ $disc_p }}%</div>
                    </div>

                    Системный диск: {{ $system_used_f }} / {{ $system_total_f }}
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $system_p }}%;">{{ $system_p }}%</div>
                    </div>

                    @if ($yandex_total)
                        Яндекс диск: {{ $yandex_used_f }} / {{ $yandex_total_f }}
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $yandex_p }}%;">{{ $yandex_p }}%</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
