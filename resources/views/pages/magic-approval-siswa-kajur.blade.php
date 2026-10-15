<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Peminjaman Siswa - SIPBAR</title>
    @include('partials.favicon')
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px 16px; background: #f1f5f9; color: #172033; font-family: Arial, sans-serif; }
        main { width: 100%; max-width: 720px; margin: 0 auto; }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: #1d4ed8; font-weight: 800; }
        .brand img { width: 42px; height: 42px; object-fit: contain; border-radius: 50%; background: white; padding: 3px; }
        .panel { overflow: hidden; background: white; border: 1px solid #dbe3ef; border-radius: 14px; box-shadow: 0 8px 24px rgba(15, 23, 42, .08); }
        .panel-header { padding: 24px; background: #1d4ed8; color: white; }
        h1 { margin: 0 0 6px; font-size: 22px; }
        .panel-header p { margin: 0; color: rgba(255,255,255,.86); font-size: 14px; line-height: 1.5; }
        .content { padding: 24px; }
        .alert { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; background: #eff6ff; color: #1e40af; }
        .alert.error { background: #fef2f2; color: #991b1b; }
        .alert.success { background: #ecfdf5; color: #065f46; }
        dl { display: grid; grid-template-columns: minmax(120px, .8fr) 2fr; margin: 0 0 22px; border-top: 1px solid #e2e8f0; }
        dt, dd { margin: 0; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; line-height: 1.5; }
        dt { color: #64748b; font-weight: 600; }
        dd { color: #172033; overflow-wrap: anywhere; }
        ul { margin: 0; padding-left: 18px; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }
        button { min-height: 42px; padding: 10px 16px; border: 0; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .approve { background: #15803d; color: white; }
        .reject { background: #b91c1c; color: white; }
        .reject-panel { display: none; margin-top: 16px; }
        .reject-panel.open { display: block; }
        label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 700; }
        textarea { width: 100%; min-height: 100px; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; resize: vertical; }
        .reject-submit { margin-top: 10px; }
        .footer-note { margin: 18px 0 0; color: #64748b; font-size: 12px; line-height: 1.5; text-align: center; }
        @media (max-width: 480px) { body { padding: 20px 12px; } .content, .panel-header { padding: 18px; } dl { grid-template-columns: 1fr; } dt { padding-bottom: 2px; border-bottom: 0; } dd { padding-top: 0; } }
    </style>
</head>
<body>
<main>
    <div class="brand"><img src="{{ asset('/logossmkn1.png') }}" alt="Logo SIPBAR"><span>SIPBAR · Persetujuan Kajur</span></div>
    <section class="panel">
        <header class="panel-header">
            <h1>Konfirmasi Peminjaman Siswa</h1>
            <p>Tinjau permohonan yang ditujukan langsung kepada Anda sebagai lender Kajur.</p>
        </header>
        <div class="content">
            @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert error">{{ session('error') }}</div> @endif
            @if(session('info')) <div class="alert">{{ session('info') }}</div> @endif
            @if($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

            <dl>
                <dt>Siswa</dt><dd>{{ $borrowingRequest->user?->name ?? 'Siswa' }}</dd>
                <dt>Tujuan pinjaman</dt><dd>{{ $borrowingRequest->purpose ?: '-' }}</dd>
                <dt>Barang</dt>
                <dd>
                    @if($borrowingRequest->items->isNotEmpty())
                        <ul>@foreach($borrowingRequest->items as $detail)<li>{{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang' }} ({{ $detail->quantity }} unit)</li>@endforeach</ul>
                    @else
                        {{ $borrowingRequest->itemWithTrashed?->name ?? $borrowingRequest->item?->name ?? 'Barang' }} ({{ $borrowingRequest->quantity ?? 1 }} unit)
                    @endif
                </dd>
                <dt>Tanggal pinjam</dt><dd>{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</dd>
                <dt>Tanggal kembali</dt><dd>{{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }} {{ $borrowingRequest->return_time ?? '' }}</dd>
                <dt>Status</dt><dd>{{ $borrowingRequest->status_label }}</dd>
            </dl>

            @if($borrowingRequest->status === \App\Models\BorrowingRequest::STATUS_PENDING)
                <div class="actions">
                    <form method="POST" action="{{ $approveUrl }}">@csrf<button class="approve" type="submit">Setujui pengajuan</button></form>
                    <button class="reject" type="button" id="toggleReject">Tolak pengajuan</button>
                </div>
                <div class="reject-panel" id="rejectPanel">
                    <form method="POST" action="{{ $rejectUrl }}">
                        @csrf
                        <label for="rejection_reason">Alasan penolakan (10-500 karakter)</label>
                        <textarea id="rejection_reason" name="rejection_reason" minlength="10" maxlength="500" required>{{ old('rejection_reason') }}</textarea>
                        <button class="reject reject-submit" type="submit">Konfirmasi penolakan</button>
                    </form>
                </div>
            @elseif($borrowingRequest->rejection_reason)
                <div class="alert error"><strong>Alasan penolakan:</strong> {{ $borrowingRequest->rejection_reason }}</div>
            @endif
        </div>
    </section>
    <p class="footer-note">Tautan persetujuan bertanda tangan dan hanya dapat digunakan oleh Kajur yang dipilih siswa.</p>
</main>
<script>
    document.getElementById('toggleReject')?.addEventListener('click', function () {
        document.getElementById('rejectPanel').classList.toggle('open');
    });
</script>
</body>
</html>
