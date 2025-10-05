<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        // Normalize and validate price filters (handle min only, max only, both, and swap if min>max)
        $min = (is_numeric($hargamin) ? (int)$hargamin : null);
        $max = (is_numeric($hargamax) ? (int)$hargamax : null);

        if ($min !== null && $max !== null) {
            if ($min > $max) {
                // swap to make range valid
                [$min, $max] = [$max, $min];
            }
            $data_search = $data_search->whereBetween('harga_beli', [$min, $max]);
        } else {
            if ($min !== null) {
                $data_search = $data_search->where('harga_beli', '>=', $min);
            }
            if ($max !== null) {
                $data_search = $data_search->where('harga_beli', '<=', $max);
            }
        }

    // include img so front-end can show thumbnails. eager load categories for response
    // include 'id' because eager loading needs the model primary key to relate categories
    $data_search = $data_search->with('categories')->select('id','kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'img')->orderBy('id')->get();

        // map categories to simple comma-separated string and categories_list for front-end
        $data_search = $data_search->map(function($item){
            $arr = $item->toArray();
            $arr['categories'] = $item->categories->pluck('nama')->implode(', ');
            $arr['categories_list'] = $item->categories->map(function($c){
                return ['id' => $c->id, 'nama' => $c->nama];
            })->toArray();
            return $arr;
        });

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = Category::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        // eager load categories so single view can display them
        $data['data'] = MasterItem::with('categories')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // validate common fields
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|integer',
            'laba' => 'required|integer',
            'supplier' => 'required|string',
            'jenis' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            // $lastcode = $kode ? intval($kode)+1 :1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        // handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('master_items', 'public');

            // delete old file if present
            if ($method != 'new' && !empty($data_item->img)) {
                if (Storage::disk('public')->exists($data_item->img)) {
                    Storage::disk('public')->delete($data_item->img);
                }
            }

            $data_item->img = $path;
        }

        $data_item->save();

        // sync categories (many-to-many)
        if ($request->has('categories')) {
            $data_item->categories()->sync($request->categories);
        } else {
            $data_item->categories()->sync([]);
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
