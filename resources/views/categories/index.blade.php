@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Kategori</div>

                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ url('categories/create') }}" class="btn btn-primary">Tambah Kategori</a>
                    </div>

                    <form class="row g-2 mb-3" method="GET" action="{{ url('categories') }}">
                        <div class="col-auto">
                            <input type="text" class="form-control" name="kode" placeholder="Filter Kode" value="{{ request('kode') }}">
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control" name="nama" placeholder="Filter Nama" value="{{ request('nama') }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-secondary">Filter</button>
                        </div>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Items</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->kode }}</td>
                                <td>{{ $cat->nama }}</td>
                                <td>{{ $cat->masterItems->count() }}</td>
                                <td>
                                    <a href="{{ url('categories/'.$cat->id) }}" class="btn btn-info">View</a>
                                    <a href="{{ url('categories/'.$cat->id.'/edit') }}" class="btn btn-warning">Edit</a>
                                    <a href="{{ url('categories/delete/'.$cat->id) }}" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
