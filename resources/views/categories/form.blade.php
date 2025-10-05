@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kategori</div>

                <div class="card-body">
                    @if($method == 'new')
                    <form method="POST" action="{{ url('categories') }}">
                    @csrf
                    @else
                    <form method="POST" action="{{ url('categories/'.$category->id) }}">
                    @csrf
                    @method('PUT')
                    @endif

                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kode" required value="{{ $category->kode ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama" required value="{{ $category->nama ?? '' }}">
                        </div>

                        <button class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
