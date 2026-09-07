<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editWakasek()
    {
        $wakasekNama = Setting::get('wakasek_nama', 'Drs. H. Mulyadi, M.Pd');
        $wakasekNip = Setting::get('wakasek_nip', '19780512 200501 1 004');

        return view('settings.wakasek', compact('wakasekNama', 'wakasekNip'));
    }

    public function updateWakasek(Request $request)
    {
        $validated = $request->validate([
            'wakasek_nama' => 'required|string|max:255',
            'wakasek_nip' => 'nullable|string|max:255',
        ]);

        Setting::set('wakasek_nama', $validated['wakasek_nama']);
        Setting::set('wakasek_nip', $validated['wakasek_nip'] ?? '-');

        return redirect()->back()
            ->with('success', 'Data TTD Wakasek Kesiswaan berhasil diperbarui!');
    }
}
