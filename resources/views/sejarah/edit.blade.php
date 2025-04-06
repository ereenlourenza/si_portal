@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($sejarah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-informasi/sejarah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/sejarah/'.$sejarah->sejarah_id) }}" class="form-horizontal">
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Judul Sub Bab<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="judul_subbab" name="judul_subbab" value="{{ old('judul_subbab', $sejarah->judul_subbab) }}" required>
                            @error('judul_subbab')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Isi Konten<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <textarea id="editor" name="isi_konten" rows="10" class="form-control">{!! old('isi_konten', $sejarah->isi_konten) !!}</textarea>
                                                        
                            @error('isi_konten')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/sejarah') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
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
                    uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token() ]) }}"
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
                alert('Isi konten tidak boleh kosong.');
            }
        });

    </script>
@endpush