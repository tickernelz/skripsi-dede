@extends('adminlte::page')

@section('title')
    {{ $judul }}
@endsection

@section('content_header')
    <h1>{{ $judul }}</h1>
@stop

@section('plugins.Datatables', true)

@php
    $heads = [
        '#',
        'NIM',
        'Nama',
        'Email',
        'Jenis Kelamin',
        'Studi',
        'Fakultas',
        'Angkatan',
        'KTM',
        'Aksi',
    ];
    $config = [
    'order' => [[0, 'asc']],
    'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false, 'className' => 'text-center']],
    ];
@endphp

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Tabel
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (session('errors'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <x-adminlte-datatable id="table" :config="$config" :heads="$heads" hoverable bordered beautify>
                @foreach($data as $li)
                    <tr>
                        <td>{!! $loop->iteration !!}</td>
                        <td>{!! $li->nim !!}</td>
                        <td>{!! $li->user->name !!}</td>
                        <td>{!! $li->user->email !!}</td>
                        <td>{!! $li->jenis_kelamin !!}</td>
                        <td>{!! $li->studi !!}</td>
                        <td>{!! $li->fakultas !!}</td>
                        <td>{!! $li->angkatan !!}</td>
                        <td>
                            @if (isset($li->ktm))
                                <x-adminlte-modal id="modal-file-{{$li->id}}" title="KTM {{$li->user->name}}" size="lg">
                                    <embed
                                        src="{{ route('get.admin.mahasiswa.readfile', $li->ktm) }}"
                                        frameborder="0" width="100%" height="600px">
                                </x-adminlte-modal>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#modal-file-{{$li->id}}">
                                        Lihat
                                    </button>
                                </div>
                            @else
                                Tidak Ada Berkas
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a type="button" class="btn btn-success"
                                   href="{{ route('get.admin.mahasiswa.verifikasi.accept', $li->id) }}">
                                    <i class="fa fa-check"></i>
                                </a>
                                <a type="button" class="btn btn-secondary"
                                   href="{{ route('get.admin.mahasiswa.edit.index', [$li->id, $route]) }}">
                                    Edit
                                </a>
                                <a type="button" class="btn btn-danger"
                                   href="{{ route('get.admin.mahasiswa.verifikasi.reject', $li->id) }}"
                                   onclick="return confirm('Yakin Ingin Di Reject?');">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('css')
@stop

@section('js')
@stop
