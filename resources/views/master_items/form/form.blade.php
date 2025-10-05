<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <optio @if($selected == 'Umum') selected @endif>Umum</option>
            <optio @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <div>
            @php
                $selectedIds = old('categories', []);
                if(empty($selectedIds) && isset($item) && is_object($item) && isset($item->categories)) {
                    $selectedIds = $item->categories->pluck('id')->toArray();
                }
            @endphp
            @foreach($categories as $cat)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="categories[]" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" @if(in_array($cat->id, (array)$selectedIds)) checked @endif>
                    <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->nama }} ({{ $cat->kode }})</label>
                </div>
            @endforeach
        </div>
        <small class="form-text text-muted">Centang satu atau lebih kategori untuk item ini.</small>
    </div>

     <div class="form-group">
        <label>Foto Barang</label>
        <input type="file" class="form-control" name="foto" accept="image/*">
    </div>

    {{-- Jika edit dan sudah ada foto --}}
    @if(isset($item) && !empty($item->img))
        <div class="mt-2">
            <img src="{{ asset('storage/'.$item->img) }}" alt="Foto Barang" width="100">
        </div>
    @endif

    <button class="btn btn-primary mt-3">Submit</button>

</form>