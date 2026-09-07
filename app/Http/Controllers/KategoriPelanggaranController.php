<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    public function index()
    {
        $kategoriList = KategoriPelanggaran::withCount('jenisPelanggaran')->get();
        return view('kategori_pelanggaran.index', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriPelanggaran::create($validated);

        return redirect()->route('kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil ditambahkan!');
    }

    public function update(Request $request, KategoriPelanggaran $kategoriPelanggaran)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategoriPelanggaran->update($validated);

        return redirect()->route('kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil diperbarui!');
    }

    public function destroy(KategoriPelanggaran $kategoriPelanggaran)
    {
        $kategoriPelanggaran->delete();

        return redirect()->route('kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil dihapus!');
    }
}
