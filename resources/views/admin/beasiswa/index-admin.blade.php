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
        'Angkatan',
        'Semester',
        'UKT',
        'Telepon',
        'Berkas',
        'Status',
        'Skor',
        'Aksi',
    ];
    $config = [
    'order' => [[10, 'desc']],
    'columns' => [null, null, null, null, null, null, null, null, null, null, null, ['orderable' => false, 'className' => 'text-center']],
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
            <form action="{{ route('post.admin.beasiswa.terima') }}" method="post">
                @csrf
                <x-adminlte-modal id="modal-terima" title="Terima Penurunan UKT" size="lg" theme="primary"
                                  icon="fas fa-lg fa-fw fa-plus" v-centered static-backdrop scrollable>
                    <x-adminlte-input name="id_mahasiswa_terima" value="" hidden/>
                    <x-adminlte-input name="ukt" label="Nominal UKT" type="number"/>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Close" data-dismiss="modal"/>
                        <x-adminlte-button class="ml-auto" type="submit" label="Submit" name="action"
                                           value="terima" theme="success"
                                           icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-modal>
            </form>
            <form action="{{ route('post.admin.beasiswa.tolak') }}" method="post">
                @csrf
                <x-adminlte-modal id="modal-tolak" title="Tolak Penurunan UKT" size="lg" theme="primary"
                                  icon="fas fa-lg fa-fw fa-plus" v-centered static-backdrop scrollable>
                    <x-adminlte-input name="id_mahasiswa_tolak" value="" hidden/>
                    <x-adminlte-input name="alasan" label="Alasan Penolakan" type="text"/>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Close" data-dismiss="modal"/>
                        <x-adminlte-button class="ml-auto" type="submit" label="Submit" name="action"
                                           value="tolak" theme="success"
                                           icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-modal>
            </form>
            <x-adminlte-datatable id="table" :config="$config" :heads="$heads" {{--with-footer--}} hoverable bordered
                                  beautify>
                @if ($data !== null)
                    @foreach($data as $li)
                        <tr>
                            <td>{!! $loop->iteration !!}</td>
                            <td>{!! $li->nim !!}</td>
                            <td>{!! $li->user->name !!}</td>
                            <td>{!! $li->user->email !!}</td>
                            <td>{!! $li->angkatan !!}</td>
                            <td>{!! $li->semester !!}</td>
                            <td>{!! $li->ukt() !!}</td>
                            <td>{!! $li->telepon !!}</td>
                            <td>
                                @if (isset($li->berkas->file))
                                    <a type="button" class="btn btn-sm btn-primary"
                                       href="/beasiswa/{{ $li->berkas->file }}" target="_blank">
                                        Lihat
                                    </a>
                                @else
                                    Tidak Ada Berkas
                                @endif
                            </td>
                            <td>
                                @if ($li->is_beasiswa_approved == 1)
                                    <span class="badge badge-success">Diterima</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            @if (Session::get('is_saw') == 1)
                                <td>{!! round($li->skor->skor_saw, 4) !!}</td>
                            @else
                                <td>{!! round($li->skor->skor_smart, 4) !!}</td>
                            @endif
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary terima" data-id="{{ $li->id ?? '' }}">
                                        Terima
                                    </button>
                                    <button type="button" class="btn btn-danger tolak"
                                            data-id="{{ $li->id ?? '' }}">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </x-adminlte-datatable>
        </div>
        <!-- /.card-body -->
    </div>
    @if ($data !== null)
        <x-adminlte-card title="Proses Perhitungan" theme="info" icon="fas fa-lg fa-file" collapsible="collapsed">
            <div class="mb-3"><span class="text-bold">Nilai (Matrix) :</span></div>
            <table class="table table-bordered table-responsive-sm text-center">
                <thead>
                <tr>
                    <th rowspan="2">Alternatif</th>
                    <th colspan="{{ $data_kriteria->count() }}">Kriteria</th>
                </tr>
                <tr>
                    @foreach($data_kriteria->keys() as $kriteria)
                        <th>C{{ $kriteria }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($transpose_matrix as $key => $value)
                    <tr>
                        <td>A{{ $key }}</td>
                        @foreach($value as $li)
                            <td>{{ round($li, 3) }}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mb-3 mt-3"><span class="text-bold">Normalisasi (Matrix) :</span></div>
            <table class="table table-bordered table-responsive-sm text-center">
                <thead>
                <tr>
                    <th rowspan="2">Alternatif</th>
                    <th colspan="{{ $data_kriteria->count() }}">Kriteria</th>
                </tr>
                <tr>
                    @foreach($data_kriteria->keys() as $kriteria)
                        <th>C{{ $kriteria }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($transpose_matrix_normalisasi as $key => $value)
                    <tr>
                        <td>A{{ $key }}</td>
                        @foreach($value as $li)
                            <td>{{ round($li, 3) }}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mb-3 mt-3"><span class="text-bold">Perangkingan (Matrix) :</span></div>
            <table class="table table-bordered table-responsive-sm text-center">
                <thead>
                <tr>
                    <th rowspan="2">Mahasiswa</th>
                    <th colspan="{{ $data_kriteria->count() }}">Kriteria</th>
                </tr>
                <tr>
                    @foreach($data_kriteria->keys() as $kriteria)
                        <th>C{{ $kriteria }}</th>
                    @endforeach
                    <th>Hasil</th>
                </tr>
                </thead>
                <tbody>
                @foreach($perangkingan as $key => $value)
                    <tr>
                        <td>{{ $data->find($key)->user->name }}</td>
                        @foreach($value as $li)
                            <td>{{ round($li, 3) }}</td>
                        @endforeach
                        <td>{{ round(array_sum($value), 4) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-adminlte-card>
    @endif
@endsection

@section('css')
@stop

@section('js')
    <script type="text/javascript">
        $(".terima").click(function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('get.admin.beasiswa.ajax_modal') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#id_mahasiswa_terima').val(data.id);
                    $('#modal-terima').modal('show');
                }
            });
        });
        $(".tolak").click(function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('get.admin.beasiswa.ajax_modal') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#id_mahasiswa_tolak').val(data.id);
                    $('#modal-tolak').modal('show');
                }
            });
        });
    </script>
@stop
