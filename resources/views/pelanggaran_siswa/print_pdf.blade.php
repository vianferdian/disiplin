<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pelanggaran Siswa</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 10px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 13px;
            font-weight: bold;
            color: #1E33F2;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .header-meta {
            font-size: 9px;
            color: #555;
        }
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .doc-sub {
            text-align: center;
            font-size: 10px;
            color: #444;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th {
            background-color: #e2e8f0;
            border: 1px solid #64748b;
            padding: 6px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .data-table td {
            border: 1px solid #94a3b8;
            padding: 6px;
            font-size: 10px;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .poin-badge {
            color: #dc2626;
            font-weight: bold;
        }
        .sig-table {
            width: 100%;
            margin-top: 30px;
        }
        .sig-cell {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH -->
    <table class="header-table">
        <tr>
            <td style="width: 60px;">
                <img src="{{ public_path('assets/images/logo-smk.png') }}" style="width: 50px; height: 50px; object-fit: contain;">
            </td>
            <td>
                <div class="header-title">TIM KESISWAAN</div>
                <div class="header-subtitle">SISTEM DIGITALISASI PELANGGARAN (DISIPLIN)</div>
            </td>
        </tr>
    </table>

    <!-- JUDUL LAPORAN -->
    <div class="doc-title">LAPORAN REKAPITULASI PELANGGARAN KEDISIPLINAN SISWA</div>
    <div class="doc-sub">
        @if($kelas)
            Filter Rombel: <strong>{{ $kelas }}</strong> | 
        @endif
        @if($tingkat)
            Tingkat Kelas: <strong>Kelas {{ $tingkat }}</strong> | 
        @endif
        Total Record Data: <strong>{{ count($pelanggaranList) }} Kejadian</strong>
    </div>

    <!-- TABEL DATA PELANGGARAN -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">Tanggal</th>
                <th style="width: 80px;">NIS</th>
                <th style="width: 150px;">Nama Siswa</th>
                <th style="width: 70px;">Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th style="width: 55px;">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggaranList as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pelanggaran)->format('d/m/Y') }}</td>
                    <td class="text-center"><code>{{ $item->siswa_nisn }}</code></td>
                    <td><strong>{{ $item->siswa_nama }}</strong></td>
                    <td class="text-center"><strong>{{ $item->siswa_kelas }}</strong></td>
                    <td>
                        {{ $item->nama_pelanggaran_formatted }}
                        @if($item->catatan_keterangan)
                            <div style="font-size: 8.5px; color: #555; margin-top: 2px;"><em>Catatan: {{ $item->catatan_keterangan }}</em></div>
                        @endif
                    </td>
                    <td class="text-center poin-badge">+{{ $item->poin_pelanggaran }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data pelanggaran siswa yang dicatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="sig-table">
        <tr>
            <td class="sig-cell">
                Mengetahui,<br>
                <strong>Kepala Sekolah</strong>
                <br><br><br><br>
                <strong>( ___________________________ )</strong><br>
                <small style="color: #666;">NIP. ........................................</small>
            </td>
            <td class="sig-cell">
                Cirebon, {{ date('d F Y') }}<br>
                <strong>Wakasek Kesiswaan</strong>
                <br><br><br><br>
                <strong>( {{ \App\Models\Setting::get('wakasek_nama', 'Drs. H. Mulyadi, M.Pd') }} )</strong><br>
                @php $nip = \App\Models\Setting::get('wakasek_nip', '19780512 200501 1 004'); @endphp
                @if($nip && $nip !== '-')
                    <small style="color: #666;">NIP. {{ $nip }}</small>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
