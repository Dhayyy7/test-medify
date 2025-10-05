@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Kategori - {{ $category->nama }}</div>

                <div class="card-body">
                    <table class="table table-borderless mb-3">
                        <tr>
                            <th>Kode</th>
                            <td>{{ $category->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $category->nama }}</td>
                        </tr>
                    </table>

                    <h5>Items dalam kategori ini</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga Beli</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->masterItems as $item)
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->harga_beli }}</td>
                                <td>
                                    <a href="{{ url('master-items/view/'.$item->kode) }}" class="btn btn-primary">View</a>
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
