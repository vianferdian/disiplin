<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Pelanggaran Siswa - DISIPLIN</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #000;
            font-size: 12px;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .table-print th {
            background-color: #f2f2f2 !important;
            border: 1px solid #000 !important;
            font-weight: bold;
            text-align: center;
        }
        .table-print td {
            border: 1px solid #000 !important;
            vertical-align: middle;
        }
        .signature-box {
            margin-top: 40px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body class="p-4">

    <!-- NO PRINT BUTTONS -->
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 border rounded">
        <div>
            <strong>Laporan Rekapitulasi Pelanggaran Kedisiplinan Siswa</strong>
            <p class="mb-0 fs-12 text-muted">Gunakan tombol di sebelah kanan untuk mencetak atau menyimpan sebagai PDF.</p>
        </div>
        <button onclick="window.print()" class="btn btn-primary font-w600">
            <i class="fa fa-print me-1"></i> Cetak / Simpan PDF
        </button>
    </div>

    <!-- KOP SURAT SEKOLAH -->
    <div class="kop-surat d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <img src="{{ asset('assets/images/logo-smk.png') }}" alt="Logo SMK" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div>
                <h4 class="mb-0 text-uppercase fw-bold" style="font-size: 16px; color: #111827;">TIM KESISWAAN</h4>
                <h5 class="mb-0 text-uppercase fw-bold text-primary" style="font-size: 14px;">SISTEM DIGITALISASI PELANGGARAN (DISIPLIN)</h5>
            </div>
        </div>
    </div>

    <!-- JUDUL DOCUMENT -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-uppercase text-decoration-underline mb-1">LAPORAN REKAPITULASI PELANGGARAN KEDISIPLINAN SISWA</h5>
        <p class="mb-0 text-muted fs-13">
            @if($kelas)
                Filter Rombel: <strong>{{ $kelas }}</strong> | 
            @endif
            @if($tingkat)
                Tingkat Kelas: <strong>Kelas {{ $tingkat }}</strong> | 
            @endif
            Total Record Data: <strong>{{ count($pelanggaranList) }} Kejadian</strong>
        </p>
    </div>

    <!-- TABLE PELANGGARAN -->
    <table class="table table-bordered table-print w-100 mb-4">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 90px;">NIS</th>
                <th>Nama Siswa</th>
                <th style="width: 90px;">Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th style="width: 70px;">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggaranList as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pelanggaran)->format('d/m/Y') }}</td>
                    <td class="text-center"><code>{{ $item->siswa_nisn }}</code></td>
                    <td><strong>{{ $item->siswa_nama }}</strong></td>
                    <td class="text-center fw-bold">{{ $item->siswa_kelas }}</td>
                    <td>
                        {{ $item->nama_pelanggaran_formatted }}
                        @if($item->catatan_keterangan)
                            <small class="d-block text-muted"><em>Catatan: {{ $item->catatan_keterangan }}</em></small>
                        @endif
                    </td>
                    <td class="text-center fw-bold text-danger">+{{ $item->poin_pelanggaran }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data pelanggaran siswa yang dicatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SIGNATURE SECTION -->
    <div class="row signature-box">
        <div class="col-6 text-center">
            <p class="mb-5">Mengetahui,<br><strong>Kepala Sekolah</strong></p>
            <br><br>
            <p class="mb-0 text-decoration-underline fw-bold">( ___________________________ )</p>
            <small>NIP. ........................................</small>
        </div>
        <div class="col-6 text-center">
            <p class="mb-5">Cirebon, {{ date('d F Y') }}<br><strong>Wakasek Kesiswaan</strong></p>
            <br><br>
            <p class="mb-0 text-decoration-underline fw-bold">( {{ \App\Models\Setting::get('wakasek_nama', 'Drs. H. Mulyadi, M.Pd') }} )</p>
            @php $nip = \App\Models\Setting::get('wakasek_nip', '19780512 200501 1 004'); @endphp
            @if($nip && $nip !== '-')
                <small>NIP. {{ $nip }}</small>
            @endif
        </div>
    </div>

    <script>
        // Auto trigger print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
