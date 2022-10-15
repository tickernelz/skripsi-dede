@extends('adminlte::page')

@section('title')
    {{ $judul }}
@endsection

@section('content_header')
    <h1>{{ $judul }}</h1>
@stop

@section('content')
    <div class="col-xl-12" style="float:none;margin:auto;">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Form Final</h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <a href="{{ redirect()->getUrlGenerator()->route('get.admin.daftar-beasiswa.step-three') }}">
                            <button type="button" class="btn btn-primary">{{ trans('auth.kembali') }}</button>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('post.admin.daftar-beasiswa.send') }}" enctype="multipart/form-data" method="post">
                <div class="card-body">
                    @if(session('errors'))
                        <x-adminlte-alert theme="danger" title="Error!" dismissable>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-adminlte-alert>
                    @endif
                    @if (Session::has('success'))
                        <x-adminlte-alert theme="success" title="Success!" dismissable>
                            {{ Session::get('success') }}
                        </x-adminlte-alert>
                    @endif
                    @if (Session::has('error'))
                        <x-adminlte-alert theme="danger" title="Error!" dismissable>
                            {{ Session::get('error') }}
                        </x-adminlte-alert>
                    @endif
                    @csrf
                    <x-adminlte-card title="Biodata" theme="info" icon="fas fa-lg fa-user" collapsible>
                        <x-adminlte-input name="nim" label="{{ trans('auth.nim') }}"
                                          placeholder="{{ trans('auth.nim') }}" value="{{ $mahasiswa->nim }}"
                                          disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-id-card text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="studi" label="{{ trans('auth.studi') }}"
                                          placeholder="{{ trans('auth.studi') }}" value="{{ $mahasiswa->studi }}"
                                          disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-id-card-alt text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="fakultas" label="{{ trans('auth.fakultas') }}"
                                          placeholder="{{ trans('auth.fakultas') }}"
                                          value="{{ $mahasiswa->fakultas }}" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-id-badge text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="name" label="{{ trans('auth.nama') }}"
                                          placeholder="{{ trans('auth.nama') }}" value="{{ $mahasiswa->user->name }}"
                                          disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="semester" label="{{ trans('auth.semester') }}"
                                          placeholder="{{ trans('auth.semester') }}"
                                          value="{{ $mahasiswa->semester }}" type="number" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-hourglass-half text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="ukt" label="{{ trans('auth.ukt') }}"
                                          placeholder="{{ trans('auth.ukt') }}"
                                          value="{{$mahasiswa->ukt }}" type="number" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-money-bill text-lightblue"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </x-adminlte-card>
                    <x-adminlte-card title="Pertanyaan" theme="info" icon="fas fa-lg fa-question" collapsible>
                        @foreach($kriteria as $k)
                            <div class="row">
                                <div class="col-lg-8 mb-4">
                                    <span class="text-bold">{{ $k->nama }}</span>
                                    <input type="hidden" name="kriteria[]" value="{{ $k->id }}">
                                    {{--Radio Option Subkriteria--}}
                                    @foreach($k->subkriteria as $s => $sub)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="subkriteria[{{ $k->id }}]"
                                                   id="{{ $sub->id }}"
                                                   value="{{ $sub->id }}" required disabled
                                                   @if ($beasiswa->where('kriteria_id', $k->id)->where('subkriteria_id', $sub->id)->first())
                                                       checked
                                                @endif>
                                            <label class="form-check-label" for="{{ $sub->id }}">
                                                {{ $sub->nama }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-4">
                                    @if ($berkas->where('kriteria_id', $k->id)->first())
                                        <div class="form-group">
                                            <x-adminlte-modal id="modal-file-{{$k->id}}" title="Lihat File" size="lg">
                                                <embed
                                                    src="{{ route('get.beasiswa.readfile', $berkas->where('kriteria_id', $k->id)->first()->id) }}"
                                                    frameborder="0" width="100%" height="600px"
                                                    type="application/pdf">
                                            </x-adminlte-modal>
                                            <x-adminlte-input-file name="berkas[{{ $k->id }}]" label="Upload File Bukti"
                                                                   placeholder="{{ $berkas->where('kriteria_id', $k->id)->first()->file }}" disabled/>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                                        data-target="#modal-file-{{$k->id}}">
                                                    Lihat File
                                                </button>
                                                <a type="button" class="btn btn-primary"
                                                   href="{{ route('get.beasiswa.download', $berkas->where('kriteria_id', $k->id)->first()->id) }}">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <x-adminlte-input-file name="berkas[{{ $k->id }}]" label="Upload File Bukti"
                                                                   placeholder="Pilih File..." disabled/>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <hr>

                        @endforeach
                    </x-adminlte-card>
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-right">
                    <button type="submit" onclick="return confirm('Yakin Ingin Mengirim Form?');"
                            class="btn btn-primary">{{ trans('auth.kirim') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
@stop

@section('js')
@stop
