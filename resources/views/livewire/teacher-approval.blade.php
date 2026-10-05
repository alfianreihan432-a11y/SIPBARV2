<div>
    <style>
        :root {
            --ta-bg: #f8fafc;
            --ta-surface: #ffffff;
            --ta-border: #e2e8f0;
            --ta-text: #0f172a;
            --ta-muted: #64748b;
            --ta-accent: #10b981;
            --ta-accent-light: #ecfdf5;
            --ta-accent-border: #a7f3d0;
            --ta-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 14px rgba(0,0,0,.02);
            --ta-shadow-hover: 0 10px 25px -5px rgba(5, 150, 105, 0.1), 0 8px 10px -6px rgba(5, 150, 105, 0.06);
            --ta-amber-bg: #fffbeb;
            --ta-amber-border: #fde68a;
            --ta-amber-text: #92400e;
            --ta-red-bg: #fef2f2;
            --ta-red-border: #fca5a5;
            --ta-red-text: #dc2626;
        }

        html.dark {
            --ta-bg: #050806;
            --ta-surface: rgba(255, 255, 255, 0.03);
            --ta-border: rgba(16, 185, 129, 0.14);
            --ta-text: #ecfdf5;
            --ta-muted: #94a3b8;
            --ta-accent: #10b981;
            --ta-accent-light: rgba(16, 185, 129, 0.18);
            --ta-accent-border: rgba(16, 185, 129, 0.4);
            --ta-shadow: 0 4px 16px rgba(0,0,0,.5);
            --ta-shadow-hover: 0 0 24px rgba(16, 185, 129, 0.15), 0 10px 25px -5px rgba(16, 185, 129, 0.2), 0 8px 10px -6px rgba(16, 185, 129, 0.1);
            --ta-amber-bg: rgba(245, 158, 11, 0.08);
            --ta-amber-border: rgba(245, 158, 11, 0.3);
            --ta-amber-text: #fbbf24;
            --ta-red-bg: rgba(239, 68, 68, 0.12);
            --ta-red-border: rgba(239, 68, 68, 0.3);
            --ta-red-text: #f87171;
        }

        * {
            transition: background-color 200ms ease, border-color 200ms ease, color 200ms ease, box-shadow 200ms ease;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }

        /* Page Header */
        .ta-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .ta-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ta-header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--ta-accent), #047857);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(5, 150, 105, .25);
        }

        .ta-header-icon svg {
            width: 24px;
            height: 24px;
            color: #fff;
        }

        .ta-header-content {
            display: flex;
            flex-direction: column;
        }

        .ta-page-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--ta-text);
            line-height: 1.2;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .ta-page-subtitle {
            font-size: 14px;
            color: var(--ta-muted);
            margin: 2px 0 0;
            font-weight: 400;
        }

        .ta-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--ta-amber-bg);
            color: var(--ta-amber-text);
            border: 1.5px solid var(--ta-amber-border);
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* Alerts */
        .ta-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .ta-alert-success {
            background: var(--ta-accent-light);
            border-color: var(--ta-accent-border);
            color: #065f46;
        }

        .ta-alert-error {
            background: var(--ta-red-bg);
            border-color: var(--ta-red-border);
            color: #991b1b;
        }

        html.dark .ta-alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #6ee7b7;
        }

        html.dark .ta-alert-error {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
        }

        .ta-alert-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .ta-alert-icon svg {
            width: 20px;
            height: 20px;
        }

        .ta-alert p {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            line-height: 1.5;
        }

        /* Empty State */
        .ta-empty {
            text-align: center;
            padding: 64px 32px;
            background: var(--ta-surface);
            border: 1.5px solid var(--ta-border);
            border-radius: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .ta-empty-icon {
            width: 80px;
            height: 80px;
            background: var(--ta-bg);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .ta-empty-icon svg {
            width: 40px;
            height: 40px;
            color: var(--ta-muted);
            opacity: 0.5;
        }

        .ta-empty-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--ta-text);
            margin: 0 0 8px;
        }

        .ta-empty-desc {
            font-size: 14px;
            color: var(--ta-muted);
            margin: 0 auto;
            max-width: 400px;
        }

        /* Card Grid */
        .ta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 640px) {
            .ta-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        .ta-card {
            background: var(--ta-surface);
            border: 1.5px solid var(--ta-border);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            box-shadow: var(--ta-shadow);
            transition: all .2s ease;
            position: relative;
            overflow: hidden;
        }

        .ta-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--ta-shadow-hover);
        }

        .ta-card.pending {
            border-left: 4px solid #f59e0b;
        }

        html.dark .ta-card {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Card Header */
        .ta-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .ta-student-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .ta-student-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--ta-accent), #047857);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(5, 150, 105, .2);
        }

        .ta-student-meta {
            min-width: 0;
            flex: 1;
        }

        .ta-student-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--ta-text);
            margin: 0 0 4px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ta-student-class {
            font-size: 13px;
            color: var(--ta-muted);
            margin: 0;
            font-weight: 500;
        }

        .ta-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--ta-amber-bg);
            color: var(--ta-amber-text);
            border: 1px solid var(--ta-amber-border);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .ta-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f59e0b;
            animation: ta-pulse 1.8s ease-in-out infinite;
        }

        @keyframes ta-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(.85); }
        }

        /* Item Block */
        .ta-item-block {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--ta-bg);
            border-radius: 12px;
            padding: 16px;
        }

        .ta-item-icon {
            width: 40px;
            height: 40px;
            background: rgba(16, 185, 129, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ta-item-icon svg {
            width: 20px;
            height: 20px;
            color: var(--ta-accent);
        }

        .ta-item-content {
            flex: 1;
            min-width: 0;
        }

        .ta-item-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ta-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .ta-item-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--ta-text);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ta-item-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: var(--ta-accent-light);
            color: var(--ta-accent);
            border: 1px solid var(--ta-accent-border);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            margin-top: 8px;
        }

        /* Detail Grid */
        .ta-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 520px) {
            .ta-detail-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }

        .ta-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .ta-detail-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: var(--ta-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ta-detail-label svg {
            width: 14px;
            height: 14px;
        }

        .ta-detail-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--ta-text);
        }

        /* Notes */
        .ta-notes {
            border-left: 3px solid var(--ta-accent);
            padding-left: 14px;
        }

        .ta-notes-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ta-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .ta-notes-label svg {
            width: 14px;
            height: 14px;
        }

        .ta-notes-text {
            font-size: 13px;
            color: var(--ta-text);
            line-height: 1.6;
            margin: 0;
        }

        /* Card Footer - Actions */
        .ta-card-footer {
            display: flex;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid var(--ta-border);
        }

        .ta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 44px;
            padding: 0 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all .15s;
            text-decoration: none;
        }

        .ta-btn:focus-visible {
            outline: 2px solid var(--ta-accent);
            outline-offset: 2px;
        }

        .ta-btn-approve {
            flex: 1;
            background: linear-gradient(135deg, var(--ta-accent), #047857);
            color: #fff;
            box-shadow: 0 4px 12px rgba(5, 150, 105, .25);
        }

        .ta-btn-approve:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            box-shadow: 0 6px 16px rgba(5, 150, 105, .35);
            transform: translateY(-1px);
        }

        .ta-btn-reject {
            flex: 1;
            background: transparent;
            color: var(--ta-red-text);
            border: 2px solid var(--ta-red-border);
        }

        .ta-btn-reject:hover {
            background: var(--ta-red-bg);
            border-color: var(--ta-red-text);
        }

        /* Modal */
        .ta-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .ta-modal {
            background: var(--ta-surface);
            border: 1.5px solid var(--ta-border);
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(0,0,0,.2);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: ta-modal-in .2s ease-out;
        }

        html.dark .ta-modal {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        @keyframes ta-modal-in {
            from { opacity: 0; transform: scale(.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .ta-modal-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--ta-border);
        }

        .ta-modal-icon {
            width: 42px;
            height: 42px;
            background: var(--ta-red-bg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ta-modal-icon svg {
            width: 20px;
            height: 20px;
            color: var(--ta-red-text);
        }

        .ta-modal-content {
            flex: 1;
            min-width: 0;
        }

        .ta-modal-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--ta-text);
            margin: 0 0 2px;
        }

        .ta-modal-sub {
            font-size: 13px;
            color: var(--ta-muted);
            margin: 0;
        }

        .ta-modal-close {
            margin-left: auto;
            width: 36px;
            height: 36px;
            border: none;
            background: var(--ta-bg);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--ta-muted);
            transition: all .15s;
            flex-shrink: 0;
        }

        .ta-modal-close:hover {
            background: var(--ta-border);
            color: var(--ta-text);
        }

        .ta-modal-close svg {
            width: 18px;
            height: 18px;
        }

        .ta-modal-body {
            padding: 20px 24px;
        }

        .ta-textarea-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--ta-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .ta-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid var(--ta-border);
            border-radius: 12px;
            background: var(--ta-bg);
            color: var(--ta-text);
            font-size: 14px;
            font-family: inherit;
            line-height: 1.6;
            resize: none;
            outline: none;
            transition: border-color .15s;
        }

        .ta-textarea:focus {
            border-color: var(--ta-red-text);
        }

        .ta-field-error {
            font-size: 13px;
            color: var(--ta-red-text);
            margin: 6px 0 0;
        }

        .ta-modal-footer {
            display: flex;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1px solid var(--ta-border);
        }

        .ta-btn-modal-cancel {
            flex: 1;
            height: 44px;
            border: 2px solid var(--ta-border);
            border-radius: 10px;
            background: transparent;
            color: var(--ta-text);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }

        .ta-btn-modal-cancel:hover {
            background: var(--ta-bg);
        }

        .ta-btn-modal-reject {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 44px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--ta-red-text), #ef4444);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .3);
            transition: all .15s;
        }

        .ta-btn-modal-reject:hover {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            transform: translateY(-1px);
        }
    </style>

    {{-- Page Header --}}
    <div class="ta-page-header">
        <div class="ta-header-left">
            <div class="ta-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="ta-header-content">
                <h1 class="ta-page-title">Persetujuan Peminjaman</h1>
                <p class="ta-page-subtitle">Tinjau dan proses permintaan peminjaman siswa bimbingan</p>
            </div>
        </div>
        @if(!$pendingRequests->isEmpty())
            <div class="ta-count-badge">
                <span class="ta-status-dot"></span>
                <span>{{ $pendingRequests->count() }} Menunggu</span>
            </div>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="ta-alert ta-alert-success">
            <div class="ta-alert-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="ta-alert ta-alert-error">
            <div class="ta-alert-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Empty State --}}
    @if($pendingRequests->isEmpty())
        <div class="ta-empty">
            <div class="ta-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="ta-empty-title">Tidak Ada Permintaan</h3>
            <p class="ta-empty-desc">Belum ada permintaan peminjaman yang menunggu persetujuan Anda saat ini.</p>
        </div>
    @else
        <div class="ta-grid">
            @foreach($pendingRequests as $request)
                <div class="ta-card pending">

                    {{-- Card Header --}}
                    <div class="ta-card-header">
                        <div class="ta-student-info">
                            <div class="ta-student-avatar">
                                {{ strtoupper(substr($request->user->name ?? 'N', 0, 2)) }}
                            </div>
                            <div class="ta-student-meta">
                                <h3 class="ta-student-name" title="{{ $request->user->name ?? 'N/A' }}">{{ $request->user->name ?? 'N/A' }}</h3>
                                <p class="ta-student-class">{{ $request->user->kelas ?? 'N/A' }} &bull; {{ $request->user->jurusan ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <span class="ta-status-badge">
                            <span class="ta-status-dot"></span>
                            Menunggu Persetujuan
                        </span>
                    </div>

                    {{-- Item Block --}}
                    @php
                        $displayItems = $request->items->count() ? $request->items : collect([$request->item])->filter();
                        $displayQuantity = $request->items->sum('quantity') ?: ($request->quantity ?? 0);
                        $itemNames = collect();
                        foreach($displayItems as $detail) {
                            $name = $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'N/A';
                            $itemNames->push($name);
                        }
                        $fullItemName = $itemNames->join(', ');
                    @endphp
                    <div class="ta-item-block">
                        <div class="ta-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ta-item-content">
                            <div class="ta-item-label">Nama Barang</div>
                            <div class="ta-item-name" title="{{ $fullItemName }}">{{ $fullItemName }}</div>
                            <div class="ta-item-badge">Total: {{ $displayQuantity }} unit</div>
                        </div>
                    </div>

                    {{-- Details Grid --}}
                    <div class="ta-detail-grid">
                        <div class="ta-detail-item">
                            <span class="ta-detail-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Jumlah
                            </span>
                            <span class="ta-detail-value">{{ $displayQuantity }} unit</span>
                        </div>
                        <div class="ta-detail-item">
                            <span class="ta-detail-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tgl. Peminjaman
                            </span>
                            <span class="ta-detail-value">{{ \Carbon\Carbon::parse($request->borrow_date)->format('d M Y') }}</span>
                        </div>
                        <div class="ta-detail-item">
                            <span class="ta-detail-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tgl. Pengembalian
                            </span>
                            <span class="ta-detail-value">{{ \Carbon\Carbon::parse($request->return_date)->format('d M Y') }} @if($request->return_time) · {{ $request->return_time }}@endif</span>
                        </div>
                        <div class="ta-detail-item">
                            <span class="ta-detail-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Keperluan
                            </span>
                            <span class="ta-detail-value">{{ $request->purpose ?? '-' }}</span>
                        </div>
                    </div>

                    @if($request->notes)
                        <div class="ta-notes">
                            <div class="ta-notes-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Catatan
                            </div>
                            <p class="ta-notes-text">{{ $request->notes }}</p>
                        </div>
                    @endif

                    {{-- Card Footer - Actions --}}
                    <div class="ta-card-footer">
                        <form action="{{ route('teacher.requests.approve', $request->id) }}" method="POST" style="flex:1">
                            @csrf
                            <button type="submit" class="ta-btn ta-btn-approve" aria-label="Setujui permintaan dari {{ $request->user->name ?? 'siswa' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:18px;height:18px">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Setujui
                            </button>
                        </form>
                        <button wire:click="openRejectModal({{ $request->id }})" class="ta-btn ta-btn-reject" aria-label="Tolak permintaan dari {{ $request->user->name ?? 'siswa' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:18px;height:18px">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Reject Modal --}}
    @if($showRejectModal)
        <div class="ta-modal-overlay" wire:click="closeRejectModal">
            <div class="ta-modal" wire:click.stop>
                <form action="{{ route('teacher.requests.reject', $selectedRequestId) }}" method="POST">
                    @csrf
                    <div class="ta-modal-header">
                        <div class="ta-modal-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div class="ta-modal-content">
                            <h3 class="ta-modal-title">Tolak Permintaan</h3>
                            <p class="ta-modal-sub">Berikan alasan penolakan yang jelas kepada siswa</p>
                        </div>
                        <button type="button" wire:click="closeRejectModal" class="ta-modal-close" aria-label="Tutup modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="ta-modal-body">
                        <label class="ta-textarea-label">Alasan Penolakan <span style="color:var(--ta-red-text)">*</span></label>
                        <textarea name="rejection_reason"
                                  wire:model="rejectionReason"
                                  rows="4"
                                  required
                                  minlength="10"
                                  maxlength="500"
                                  class="ta-textarea"
                                  placeholder="Contoh: Barang sedang dipinjam oleh kelas lain dan belum tersedia untuk tanggal yang diminta..."></textarea>
                        @error('rejection_reason')
                            <p class="ta-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="ta-modal-footer">
                        <button type="button" wire:click="closeRejectModal" class="ta-btn-modal-cancel">Batal</button>
                        <button type="submit" class="ta-btn-modal-reject">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:18px;height:18px">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
