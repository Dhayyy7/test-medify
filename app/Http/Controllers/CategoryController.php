<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $query = Category::query();
        if (!empty($kode)) $query->where('kode', 'LIKE', "%$kode%");
        if (!empty($nama)) $query->where('nama', 'LIKE', "%$nama%");

        $data['categories'] = $query->orderBy('id')->get();
        return view('categories.index', $data);
    }

    public function create()
    {
        return view('categories.form', ['method' => 'new', 'category' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:categories,kode',
            'nama' => 'required|string',
        ]);

        Category::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect('categories');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.form', ['method' => 'edit', 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|unique:categories,kode,' . $category->id,
            'nama' => 'required|string',
        ]);

        $category->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect('categories');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect('categories');
    }

    public function show($id)
    {
        $category = Category::with('masterItems')->findOrFail($id);
        return view('categories.show', ['category' => $category]);
    }
}
