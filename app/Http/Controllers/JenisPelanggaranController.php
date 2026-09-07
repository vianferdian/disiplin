<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index()
    {
        $jenisPelanggaranList = JenisPelanggaran::with('kategori')->orderBy('kategori_id')->get();
        $kategoriList = KategoriPelanggaran::all();
        return view('jenis_pelanggaran.index', compact('jenisPelanggaranList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_pelanggarans,id',
            'kode_pelanggaran' => 'nullable|string|max:50|unique:jenis_pelanggarans,kode_pelanggaran',
            'nama_pelanggaran' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'sanksi_default' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        JenisPelanggaran::create($validated);

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil ditambahkan!');
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_pelanggarans,id',
            'kode_pelanggaran' => 'nullable|string|max:50|unique:jenis_pelanggarans,kode_pelanggaran,' . $jenisPelanggaran->id,
            'nama_pelanggaran' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'sanksi_default' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $jenisPelanggaran->update($validated);

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui!');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        $jenisPelanggaran->delete();

        return redirect()->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil dihapus!');
    }
}
