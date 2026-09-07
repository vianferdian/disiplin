<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pelanggaran — {{ $pelanggaranSiswa->siswa_nama }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
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
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 12px;
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
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #334155;
            width: 140px;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
        }
        .info-value {
            border: 1px solid #cbd5e1;
            background-color: #fff;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background-color: #1E33F2;
            color: #ffffff;
            padding: 5px 10px;
            margin-top: 15px;
            margin-bottom: 8px;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #e2e8f0;
            border: 1px solid #64748b;
            padding: 6px;
            font-size: 9.5px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 7px;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .poin-badge {
            color: #dc2626;
            font-weight: bold;
        }
        .sig-table {
            width: 100%;
            margin-top: 25px;
        }
        .sig-cell {
            width: 33.3%;
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

    <!-- JUDUL SURAT -->
    <div class="doc-title">LEMBAR REKAM JEJAK & CATATAN PELANGGARAN SISWA</div>

    <!-- PROFIL SISWA & DETAIL KEJADIAN -->
    <div class="section-title">1. Profil Siswa & Informasi Kejadian Terkini</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Siswa:</td>
            <td class="info-value"><strong>{{ $pelanggaranSiswa->siswa_nama }}</strong></td>
            <td class="info-label">Total Poin Akumulasi:</td>
            <td class="info-value"><strong style="color: #dc2626; font-size: 13px;">{{ $totalPoinSiswa }} Poin</strong></td>
        </tr>
        <tr>
            <td class="info-label">NIS / Kelas:</td>
            <td class="info-value">{{ $pelanggaranSiswa->siswa_nisn }} | <strong>{{ $pelanggaranSiswa->siswa_kelas }}</strong></td>
            <td class="info-label">Poin Kejadian Ini:</td>
            <td class="info-value"><strong style="color: #dc2626;">+{{ $pelanggaranSiswa->poin_pelanggaran }} Poin</strong></td>
        </tr>
        <tr>
            <td class="info-label">Jenis Pelanggaran:</td>
            <td class="info-value"><strong>{{ $pelanggaranSiswa->nama_pelanggaran_formatted }}</strong></td>
            <td class="info-label">Kategori:</td>
            <td class="info-value">{{ $pelanggaranSiswa->jenisPelanggaran->kategori->nama_kategori ?? 'Umum' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal & Waktu:</td>
            <td class="info-value" colspan="3">{{ \Carbon\Carbon::parse($pelanggaranSiswa->tanggal_pelanggaran)->format('d F Y') }} ({{ $pelanggaranSiswa->waktu_pelanggaran ?? '-' }})</td>
        </tr>
        <tr>
            <td class="info-label">Sanksi Default:</td>
            <td class="info-value" colspan="3"><span style="color: #b91c1c; font-weight: bold;">{{ $pelanggaranSiswa->jenisPelanggaran->sanksi_default ?? 'Teguran & Pembinaan' }}</span></td>
        </tr>
        @if($pelanggaranSiswa->catatan_keterangan)
        <tr>
            <td class="info-label">Catatan / Kronologi:</td>
            <td class="info-value" colspan="3"><em>{{ $pelanggaranSiswa->catatan_keterangan }}</em></td>
        </tr>
        @endif
    </table>

    <!-- TABEL HISTORI PELANGGARAN -->
    <div class="section-title">2. Riwayat Lengkap Pelanggaran Kedisiplinan Siswa</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">Tanggal</th>
                <th style="width: 55px;">Waktu</th>
                <th>Jenis Pelanggaran & Catatan</th>
                <th style="width: 90px;">Kategori</th>
                <th style="width: 45px;">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatSiswa as $index => $history)
                <tr style="{{ $history->id == $pelanggaranSiswa->id ? 'background-color: #fef2f2;' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($history->tanggal_pelanggaran)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $history->waktu_pelanggaran ?? '-' }}</td>
                    <td>
                        <strong>{{ $history->nama_pelanggaran_formatted }}</strong>
                        @if($history->catatan_keterangan)
                            <div style="font-size: 8.5px; color: #555; margin-top: 2px;"><em>Rincian: {{ $history->catatan_keterangan }}</em></div>
                        @endif
                    </td>
                    <td class="text-center">{{ $history->jenisPelanggaran->kategori->nama_kategori ?? 'Umum' }}</td>
                    <td class="text-center poin-badge">+{{ $history->poin_pelanggaran }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Belum ada riwayat pelanggaran lainnya.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="sig-table">
        <tr>
            <td class="sig-cell">
                Mengetahui,<br>
                <strong>Orang Tua / Wali Siswa</strong>
                <br><br><br><br>
                <strong>( ___________________________ )</strong>
            </td>
            <td class="sig-cell">
                Siswa Yang Bersangkutan,<br>
                <strong>Pelanggar</strong>
                <br><br><br><br>
                <strong>( {{ $pelanggaranSiswa->siswa_nama }} )</strong>
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
