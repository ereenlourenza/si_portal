@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-informasi/komisi') }}" class="form-horizontal" enctype='multipart/form-data'>
                @csrf
                
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Komisi Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="komisi_nama" name="komisi_nama" value="{{ old('komisi_nama') }}" required>
                        
                        @error('komisi_nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Deskripsi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <textarea id="editor" class="form-control" rows="10" name="deskripsi" >{{ old('deskripsi') }}</textarea>
                        
                        @error('deskripsi')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/komisi') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editor;
        let editorReady = false;

        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor-komisi.upload', ['_token' => csrf_token() ]) }}"
                }
            })
            .then(newEditor => {
                editor = newEditor;
                editorReady = true;
            });


        document.querySelector('form').addEventListener('submit', function (e) {
            if (!editorReady) {
                e.preventDefault();
                alert('Editor belum siap, mohon tunggu sebentar.');
                return;
            }

            const isi = editor.getData();
            document.querySelector('#isi_konten').value = isi;

            if (!isi.trim()) {
                e.preventDefault();
                alert('Deskripsi tidak boleh kosong.');
            }
        });

    </script>
@endpush