<div class="im-root" wire:key="inventory-manager">
<style>
    /* ══════════════════════════════════════════════════════════════
       INVENTORY MANAGER — CLEAN THEME SYSTEM
    ══════════════════════════════════════════════════════════════ */
    .im-root { display: flex; flex-direction: column; gap: 20px; }

    /* ── KPI Cards ─────────────────────────────────────────────── */
    .im-kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
    @media (max-width: 1024px) { .im-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .im-kpi-grid { grid-template-columns: repeat(1, 1fr); } }

    .im-kpi-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
        transition: border-color .2s, transform .2s, box-shadow .2s;
        box-shadow: var(--card-shadow);
    }
    .im-kpi-card:hover {
        border-color: var(--blue);
        transform: translateY(-2px);
    }
    .im-kpi-label { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); }
    .im-kpi-icon  {
        width: 38px; height: 38px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .im-kpi-num   { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-top: 10px; }
    .im-kpi-sub   { font-size: 11px; color: var(--text-subtle); margin-top: 4px; }

    /* ── Toolbar ─────────────────────────────────────────────── */
    .im-toolbar {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 14px 18px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        box-shadow: var(--card-shadow);
    }
    @media (max-width: 640px) {
        .im-toolbar { padding: 12px; }
        .im-search-wrap { min-width: 100% !important; }
        .im-form-panel { padding: 16px 14px !important; }
    }
    .im-search-wrap {
        flex: 1; min-width: 200px;
        position: relative;
    }
    .im-search-wrap svg.search-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        width: 15px; height: 15px; color: var(--text-muted); pointer-events: none;
    }
    .im-search-input {
        width: 100%; background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        padding: 9px 12px 9px 36px;
        font-size: 13px; color: var(--text-primary);
        outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .im-search-input::placeholder { color: var(--text-subtle); }
    .im-search-input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
    }
    /* ── Custom Dropdown (replaces native select) ───────────── */
    .im-dropdown {
        position: relative;
        display: inline-block;
    }
    .im-dropdown-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-primary);
        cursor: pointer;
        outline: none;
        white-space: nowrap;
        transition: border-color .2s ease, box-shadow .2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        user-select: none;
    }
    .im-dropdown-btn:hover {
        border-color: var(--blue);
        box-shadow: 0 0 0 2px rgba(59,130,246,0.12);
    }
    .im-dropdown.active .im-dropdown-btn {
        border-color: var(--blue);
        box-shadow: 0 0 0 3.5px rgba(59,130,246,0.18);
        color: var(--blue);
    }
    .im-dropdown-chevron {
        width: 14px; height: 14px;
        flex-shrink: 0;
        color: var(--blue);
        transition: transform .2s ease;
    }
    .im-dropdown.active .im-dropdown-chevron {
        transform: rotate(180deg);
    }
    .im-dropdown-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
        display: none;
    }
    .im-dropdown.has-value .im-dropdown-dot {
        display: block;
    }
    .im-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 180px;
        z-index: 9999;
        background: var(--bg-card);
        border: 1.5px solid var(--border-alt);
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 16px 40px -4px rgba(0,0,0,0.25), 0 4px 16px rgba(0,0,0,0.1);
        animation: imDropIn 0.18s cubic-bezier(0.16,1,0.3,1);
    }
    html.light .im-dropdown-menu {
        box-shadow: 0 16px 40px -4px rgba(37,99,235,0.18), 0 4px 16px rgba(0,0,0,0.08);
    }
    @keyframes imDropIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .im-dropdown.active .im-dropdown-menu {
        display: block;
    }
    .im-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px 8px 32px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        position: relative;
        transition: background .12s, color .12s;
    }
    .im-dropdown-item:hover {
        background: rgba(59,130,246,0.1);
        color: var(--blue);
    }
    .im-dropdown-item.selected {
        background: rgba(59,130,246,0.12);
        color: var(--blue);
        font-weight: 700;
    }
    .im-dropdown-item .im-check {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px; height: 14px;
        color: var(--blue);
        opacity: 0;
        flex-shrink: 0;
    }
    .im-dropdown-item.selected .im-check {
        opacity: 1;
    }
    .im-dropdown-divider {
        height: 1px;
        background: var(--border-subtle);
        margin: 4px 6px;
    }
    .im-view-toggle {
        display: flex; align-items: center;
        background: var(--bg-card-subtle);
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px; padding: 3px;
    }
    .im-view-btn {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); cursor: pointer; border: none; background: none;
        transition: all .15s;
    }
    .im-view-btn.active { background: var(--blue-dark); color: #fff; }
    .im-view-btn:hover:not(.active) { color: var(--text-primary); }
    .im-add-btn {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--blue-dark);
        border: none; border-radius: 10px;
        padding: 9px 18px;
        font-size: 12px; font-weight: 700; color: #fff;
        cursor: pointer; white-space: nowrap;
        box-shadow: 0 4px 12px rgba(29,78,216,.3);
        transition: opacity .15s, transform .15s;
    }
    .im-add-btn:hover { opacity: .9; transform: translateY(-1px); }
    .im-add-btn:active { transform: translateY(0); }

    /* ── Form Panel ──────────────────────────────────────────── */
    .im-form-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: var(--card-shadow);
    }
    .im-form-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-subtle);
        margin-bottom: 20px;
    }
    .im-form-title { font-size: 17px; font-weight: 800; color: var(--text-primary); }
    .im-form-sub   { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
    .im-section-label {
        font-size: 10px; font-weight: 800; letter-spacing: .1em;
        text-transform: uppercase; margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .im-section-num {
        width: 18px; height: 18px; border-radius: 6px;
        font-size: 10px; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
    }
    .im-field-grid { display: grid; gap: 14px; grid-template-columns: repeat(3,1fr); }
    @media (max-width: 900px) { .im-field-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 600px) { .im-field-grid { grid-template-columns: 1fr; } }
    .im-field-grid-4 { display: grid; gap: 14px; grid-template-columns: repeat(4,1fr); }
    @media (max-width: 900px) { .im-field-grid-4 { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 600px) { .im-field-grid-4 { grid-template-columns: 1fr; } }
    .im-field-grid-2-3 { display: grid; gap: 14px; grid-template-columns: repeat(3,1fr); }
    @media (max-width: 900px) { .im-field-grid-2-3 { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 600px) { .im-field-grid-2-3 { grid-template-columns: 1fr; } }
    .im-span-2 { grid-column: span 2; }
    .im-span-full { grid-column: 1 / -1; }
    .im-label { font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; display: block; }
    .im-required { color: #f43f5e; }
    .im-input, .im-select-field {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        padding: 10px 13px;
        font-size: 13px; color: var(--text-primary);
        outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .im-input::placeholder { color: var(--text-subtle); }
    .im-input:focus, .im-select-field:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    .im-select-field option { background: var(--bg-card); color: var(--text-primary); }
    .im-select-field { cursor: pointer; }
    .im-field-error { font-size: 11px; font-weight: 600; color: #f87171; margin-top: 4px; }
    .im-form-divider { border: none; border-top: 1px solid var(--border-subtle); margin: 20px 0; }
    .im-form-actions {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        padding-top: 16px; border-top: 1px solid var(--border-subtle);
    }
    .im-btn-cancel {
        background: var(--bg-card-subtle); border: 1.5px solid var(--border-subtle);
        color: var(--text-muted); border-radius: 10px;
        padding: 9px 18px; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: background .15s, color .15s;
    }
    .im-btn-cancel:hover { background: var(--bg-hover); color: var(--text-primary); }
    .im-btn-save {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--blue-dark);
        border: none; border-radius: 10px;
        padding: 9px 22px;
        font-size: 12px; font-weight: 800; color: #fff;
        cursor: pointer; box-shadow: 0 4px 12px rgba(29,78,216,.3);
        transition: opacity .15s, transform .15s;
    }
    .im-btn-save:hover { opacity: .9; transform: translateY(-1px); }

    /* ── List Header ─────────────────────────────────────────── */
    .im-list-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
    }
    .im-list-title { font-size: 15px; font-weight: 800; color: var(--text-primary); }
    .im-count-badge {
        background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.22);
        color: var(--blue); border-radius: 999px;
        font-size: 11px; font-weight: 700; padding: 2px 10px; margin-left: 8px;
    }
    .im-reset-link { font-size: 11px; font-weight: 700; color: #f87171; cursor: pointer; display: flex; align-items: center; gap: 4px; }
    .im-reset-link:hover { text-decoration: underline; }

    /* ── Grid Cards ──────────────────────────────────────────── */
    .im-card-grid { display: grid; gap: 16px; grid-template-columns: repeat(3, 1fr); }
    @media (max-width: 1024px) { .im-card-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .im-card-grid { grid-template-columns: 1fr; } }

    .im-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 18px;
        overflow: hidden;
        transition: border-color .2s, transform .2s, box-shadow .2s;
        display: flex; flex-direction: column;
        box-shadow: var(--card-shadow);
    }
    .im-card:hover {
        border-color: var(--blue);
        transform: translateY(-2px);
    }
    .im-card-img {
        height: 160px; background: var(--bg-card-subtle);
        display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
        border-bottom: 1px solid var(--border-subtle);
    }
    .im-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
    .im-card:hover .im-card-img img { transform: scale(1.05); }
    .im-card-code {
        position: absolute; bottom: 8px; left: 8px;
        background: var(--bg-card); border: 1px solid var(--border-subtle);
        border-radius: 6px; padding: 2px 7px;
        font-size: 10px; font-family: monospace; font-weight: 700; color: var(--text-muted);
    }
    .im-card-status-badge {
        position: absolute; top: 10px; right: 10px;
        display: inline-flex; align-items: center; gap: 4px;
        border-radius: 999px; padding: 3px 9px;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
    }
    .im-card-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; }
    .im-card-cat  {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--bg-card-subtle); border: 1px solid var(--border-subtle);
        border-radius: 6px; padding: 3px 8px;
        font-size: 10px; font-weight: 700; color: var(--text-muted);
        margin-bottom: 10px; max-width: fit-content;
    }
    .im-card-name { font-size: 14px; font-weight: 800; color: var(--text-primary); line-height: 1.3; margin-bottom: 4px; }
    .im-card-meta { font-size: 11px; color: var(--text-muted); }
    .im-card-info-row {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 8px; margin-top: 12px; padding-top: 12px;
        border-top: 1px solid var(--border-subtle);
    }
    .im-card-info-cell {
        background: var(--bg-card-subtle); border: 1px solid var(--border-subtle);
        border-radius: 10px; padding: 8px 10px;
    }
    .im-cell-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-subtle); }
    .im-cell-val   { font-size: 12px; font-weight: 700; color: var(--text-primary); margin-top: 2px; }
    .im-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 18px 16px;
        border-top: 1px solid var(--border-subtle);
        margin-top: auto;
    }
    .im-stock-num { font-size: 20px; font-weight: 900; color: var(--blue); line-height: 1; }
    .im-stock-sub { font-size: 10px; font-weight: 600; color: var(--text-subtle); }
    .im-card-actions { display: flex; align-items: center; gap: 6px; }
    .im-btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.25);
        color: var(--blue); border-radius: 9px;
        padding: 6px 12px; font-size: 11px; font-weight: 700;
        cursor: pointer; transition: all .15s;
    }
    .im-btn-edit:hover { background: var(--blue); color: #fff; }
    .im-btn-del {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(244,63,94,.1); border: 1px solid rgba(244,63,94,.2);
        color: #fb7185; cursor: pointer; transition: all .15s;
    }
    .im-btn-del:hover { background: #f43f5e; color: #fff; }

    /* ── Table ─────────────────────────────────────────────── */
    .im-table-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: var(--card-shadow);
    }
    .im-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .im-table thead tr {
        background: var(--table-head-bg);
        border-bottom: 1px solid var(--border-subtle);
    }
    .im-table th {
        padding: 13px 16px; text-align: left;
        font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        color: var(--text-subtle); white-space: nowrap;
    }
    .im-table th.center { text-align: center; }
    .im-table th.right  { text-align: right; }
    .im-table tbody tr { border-bottom: 1px solid var(--border-subtle); transition: background .15s; }
    .im-table tbody tr:last-child { border-bottom: none; }
    .im-table tbody tr:hover { background: var(--table-hover); }
    .im-table td { padding: 13px 16px; color: var(--text-secondary); vertical-align: middle; }
    .im-table td.center { text-align: center; }
    .im-table td.right  { text-align: right; }
    .im-table-item-icon {
        width: 36px; height: 36px; background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .im-table-actions { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
    .im-tbl-btn {
        width: 30px; height: 30px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s;
    }
    .im-tbl-btn-edit { background: rgba(59,130,246,.1); border: 1px solid rgba(59,130,246,.18); color: var(--blue); }
    .im-tbl-btn-edit:hover { background: var(--blue); color: #fff; }
    .im-tbl-btn-del  { background: rgba(244,63,94,.1); border: 1px solid rgba(244,63,94,.18); color: #fb7185; }
    .im-tbl-btn-del:hover  { background: #f43f5e; color: #fff; }

    /* ── Empty State ─────────────────────────────────────────── */
    .im-empty {
        background: var(--bg-card); border: 1.5px dashed var(--border-alt);
        border-radius: 18px; padding: 64px 24px;
        text-align: center;
    }
    .im-empty-icon {
        width: 64px; height: 64px; margin: 0 auto 16px;
        background: var(--bg-card-subtle); border: 1px solid var(--border-subtle);
        border-radius: 20px; display: flex; align-items: center; justify-content: center;
    }

    /* ── Status Badges ────────────────────────────────────────── */
    .badge-tersedia   { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
    .badge-dipinjam   { background: rgba(244,63,94,.12);  color: #f43f5e; border: 1px solid rgba(244,63,94,.2); }
    .badge-maintenance{ background: rgba(245,158,11,.12); color: #f59e0b; border: 1px solid rgba(245,158,11,.2); }
    .badge-hilang     { background: rgba(148,163,184,.12);color: var(--text-muted); border: 1px solid rgba(148,163,184,.2); }

    /* ── Alert ─────────────────────────────────────────────────── */
    .im-alert {
        display: flex; align-items: center; gap: 12px;
        background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2);
        border-radius: 14px; padding: 14px 18px;
        font-size: 13px; font-weight: 600; color: #10b981;
    }
    .im-alert-icon { width: 32px; height: 32px; border-radius: 10px; background: rgba(16,185,129,.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .im-alert-close { margin-left: auto; cursor: pointer; color: rgba(16,185,129,.6); border: none; background: none; }
    .im-alert-close:hover { color: #10b981; }

    @keyframes imFadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .im-fade { animation: imFadeIn .25s ease; }
</style>

    {{-- ── SUCCESS ALERT ─────────────────────────────────────── --}}
    @if(session()->has('message'))
    <div class="im-alert im-fade">
        <div class="im-alert-icon">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span style="flex:1">{{ session('message') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="im-alert-close">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- ── KPI METRIC CARDS ─────────────────────────────────── --}}
    <div class="im-kpi-grid">

        {{-- Total Inventaris --}}
        <div class="im-kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="im-kpi-label">Total Inventaris</span>
                <div class="im-kpi-icon" style="background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.2)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="im-kpi-num">{{ $stats['total'] ?? 0 }}</div>
            <div class="im-kpi-sub">{{ $stats['total_stock'] ?? 0 }} unit fisik terdaftar</div>
        </div>

        {{-- Tersedia --}}
        <div class="im-kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="im-kpi-label">Siap Dipinjam</span>
                <div class="im-kpi-icon" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.2)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="im-kpi-num">{{ $stats['tersedia'] ?? 0 }}</div>
            <div class="im-kpi-sub" style="display:flex;align-items:center;gap:5px">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#10b981;animation:pulse 1.5s infinite"></span>
                Barang siap pakai
            </div>
        </div>

        {{-- Dipinjam --}}
        <div class="im-kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="im-kpi-label">Sedang Dipinjam</span>
                <div class="im-kpi-icon" style="background:rgba(244,63,94,.12);border:1px solid rgba(244,63,94,.2)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#f43f5e" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </div>
            <div class="im-kpi-num">{{ $stats['dipinjam'] ?? 0 }}</div>
            <div class="im-kpi-sub">Dalam pemakaian siswa/guru</div>
        </div>

        {{-- Maintenance --}}
        <div class="im-kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="im-kpi-label">Maintenance</span>
                <div class="im-kpi-icon" style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.2)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                </div>
            </div>
            <div class="im-kpi-num">{{ $stats['maintenance'] ?? 0 }}</div>
            <div class="im-kpi-sub"><span style="color:#10b981;font-weight:700">{{ $stats['baik'] ?? 0 }}</span> kondisi baik</div>
        </div>
    </div>

    {{-- ── CONTROL TOOLBAR ─────────────────────────────────── --}}
    <div class="im-toolbar">
        {{-- Search --}}
        <div class="im-search-wrap">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.250ms="search" type="text"
                placeholder="Cari nama, kode, merk, tipe barang..."
                class="im-search-input">
            @if($search)
            <button type="button" wire:click="$set('search','')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer">
                <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            @endif
        </div>

        {{-- Category Filter Dropdown --}}
        <div class="im-dropdown" id="imDropCat" data-wire="filterCategory">
            <button type="button" class="im-dropdown-btn" onclick="imToggleDrop('imDropCat', event)">
                <span class="im-dropdown-dot" style="background:#6366f1"></span>
                <span class="im-dropdown-label">Semua Kategori</span>
                <svg class="im-dropdown-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="im-dropdown-menu">
                <div class="im-dropdown-item selected" data-value="" onclick="imSelectItem('imDropCat', '', 'Semua Kategori', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Semua Kategori
                </div>
                <div class="im-dropdown-divider"></div>
                @foreach($categories as $cat)
                <div class="im-dropdown-item" data-value="{{ $cat->id }}" onclick="imSelectItem('imDropCat', '{{ $cat->id }}', '{{ $cat->name }}', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ $cat->name }}
                </div>
                @endforeach
            </div>
        </div>

        {{-- Status Filter Dropdown --}}
        <div class="im-dropdown" id="imDropStatus" data-wire="filterStatus">
            <button type="button" class="im-dropdown-btn" onclick="imToggleDrop('imDropStatus', event)">
                <span class="im-dropdown-dot" style="background:#10b981"></span>
                <span class="im-dropdown-label">Semua Status</span>
                <svg class="im-dropdown-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="im-dropdown-menu">
                <div class="im-dropdown-item selected" data-value="" onclick="imSelectItem('imDropStatus', '', 'Semua Status', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Semua Status
                </div>
                <div class="im-dropdown-divider"></div>
                <div class="im-dropdown-item" data-value="Tersedia" onclick="imSelectItem('imDropStatus', 'Tersedia', 'Tersedia', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#10b981;flex-shrink:0"></span> Tersedia
                    </span>
                </div>
                <div class="im-dropdown-item" data-value="Dipinjam" onclick="imSelectItem('imDropStatus', 'Dipinjam', 'Dipinjam', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;flex-shrink:0"></span> Dipinjam
                    </span>
                </div>
                <div class="im-dropdown-item" data-value="Maintenance" onclick="imSelectItem('imDropStatus', 'Maintenance', 'Maintenance', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#6366f1;flex-shrink:0"></span> Maintenance
                    </span>
                </div>
                <div class="im-dropdown-item" data-value="Hilang" onclick="imSelectItem('imDropStatus', 'Hilang', 'Hilang', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#94a3b8;flex-shrink:0"></span> Hilang
                    </span>
                </div>
            </div>
        </div>

        {{-- Condition Filter Dropdown --}}
        <div class="im-dropdown" id="imDropCond" data-wire="filterCondition">
            <button type="button" class="im-dropdown-btn" onclick="imToggleDrop('imDropCond', event)">
                <span class="im-dropdown-dot" style="background:#f59e0b"></span>
                <span class="im-dropdown-label">Semua Kondisi</span>
                <svg class="im-dropdown-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="im-dropdown-menu">
                <div class="im-dropdown-item selected" data-value="" onclick="imSelectItem('imDropCond', '', 'Semua Kondisi', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Semua Kondisi
                </div>
                <div class="im-dropdown-divider"></div>
                <div class="im-dropdown-item" data-value="Baik" onclick="imSelectItem('imDropCond', 'Baik', 'Baik', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#10b981;flex-shrink:0"></span> Baik
                    </span>
                </div>
                <div class="im-dropdown-item" data-value="Rusak Ringan" onclick="imSelectItem('imDropCond', 'Rusak Ringan', 'Rusak Ringan', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;flex-shrink:0"></span> Rusak Ringan
                    </span>
                </div>
                <div class="im-dropdown-item" data-value="Rusak Berat" onclick="imSelectItem('imDropCond', 'Rusak Berat', 'Rusak Berat', this)">
                    <svg class="im-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;flex-shrink:0"></span> Rusak Berat
                    </span>
                </div>
            </div>
        </div>

        {{-- View Toggle --}}
        <div class="im-view-toggle">
            <button type="button" wire:click="setViewMode('grid')" class="im-view-btn {{ $viewMode === 'grid' ? 'active' : '' }}" title="Tampilan Grid">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </button>
            <button type="button" wire:click="setViewMode('table')" class="im-view-btn {{ $viewMode === 'table' ? 'active' : '' }}" title="Tampilan Tabel">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </button>
        </div>

        {{-- Add Button --}}
        <button type="button" wire:click="toggleForm" class="im-add-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;transition:transform .2s;{{ $showForm ? 'transform:rotate(45deg)' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>{{ $showForm ? 'Tutup Form' : 'Tambah Barang' }}</span>
        </button>
    </div>

    {{-- ── FORM PANEL ───────────────────────────────────────── --}}
    @if($showForm || $editingId)
    <div class="im-form-panel im-fade">
        <div class="im-form-header">
            <div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <h2 class="im-form-title">{{ $editingId ? 'Edit Data Barang' : 'Tambah Barang Baru' }}</h2>
                    @if($editingId)
                    <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);color:#fbbf24;border-radius:999px;padding:3px 10px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.05em">
                        <span style="width:6px;height:6px;border-radius:50%;background:#fbbf24;animation:pulse 1.5s infinite"></span>
                        Mode Edit #{{ $editingId }}
                    </span>
                    @endif
                </div>
                <p class="im-form-sub">{{ $editingId ? 'Perbarui spesifikasi, lokasi, dan stok barang ini.' : 'Isi rincian barang untuk mencatatnya ke dalam sistem inventaris.' }}</p>
            </div>
            <button type="button" wire:click="resetForm" style="background:#1e293b;border:1.5px solid rgba(148,163,184,.14);border-radius:10px;padding:7px;color:#64748b;cursor:pointer;transition:all .15s" onmouseover="this.style.color='#fff';this.style.background='#334155'" onmouseout="this.style.color='#64748b';this.style.background='#1e293b'">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form wire:submit="save">
            {{-- ── SECTION 1: Informasi Utama --}}
            <div>
                <div class="im-section-label" style="color:#60a5fa">
                    <span class="im-section-num" style="background:rgba(59,130,246,.15);color:#60a5fa">1</span>
                    Informasi Utama
                </div>
                <div class="im-field-grid">
                    <div>
                        <label class="im-label">Nama Barang <span class="im-required">*</span></label>
                        <input wire:model="name" type="text" class="im-input" placeholder="Contoh: Laptop Asus ROG Strix">
                        @error('name') <span class="im-field-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="im-label">Kategori <span class="im-required">*</span></label>
                        <select wire:model="category_id" class="im-select-field">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="im-field-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="im-label">Jumlah / Stok <span class="im-required">*</span></label>
                        <input wire:model="stock" type="number" min="0" class="im-input" placeholder="1">
                        @error('stock') <span class="im-field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="im-span-full">
                        <label class="im-label">Deskripsi Barang</label>
                        <textarea wire:model="description" rows="2" class="im-input" style="resize:vertical" placeholder="Catatan atau spesifikasi ringkas barang..."></textarea>
                    </div>
                </div>
            </div>

            <hr class="im-form-divider">

            {{-- ── SECTION 2: Detail & Spesifikasi --}}
            <div>
                <div class="im-section-label" style="color:#22d3ee">
                    <span class="im-section-num" style="background:rgba(6,182,212,.15);color:#22d3ee">2</span>
                    Detail & Spesifikasi
                </div>
                <div class="im-field-grid-4">
                    <div>
                        <label class="im-label">Merk / Brand</label>
                        <input wire:model="brand" type="text" class="im-input" placeholder="Asus, Logitech...">
                    </div>
                    <div>
                        <label class="im-label">Tipe / Model</label>
                        <input wire:model="type" type="text" class="im-input" placeholder="G513, MX Master 3...">
                    </div>
                </div>
            </div>

            <hr class="im-form-divider">

            {{-- ── SECTION 3: Penempatan & Status --}}
            <div>
                <div class="im-section-label" style="color:#34d399">
                    <span class="im-section-num" style="background:rgba(16,185,129,.15);color:#34d399">3</span>
                    Penempatan, Kondisi & Foto
                </div>
                <div class="im-field-grid-2-3">
                    <div>
                        <label class="im-label">Lokasi Ruangan</label>
                        <select wire:model="location_id" class="im-select-field">
                            <option value="">— Pilih Lokasi —</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->building }}{{ $loc->floor ? ' Lt.'.$loc->floor : '' }} — {{ $loc->room }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="im-label">Kondisi Fisik</label>
                        <select wire:model="condition" class="im-select-field">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="im-label">Status Peminjaman</label>
                        <select wire:model="status" class="im-select-field">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="im-span-2">
                        <label class="im-label">Foto Barang</label>
                        <div style="display:flex;align-items:center;gap:10px">
                            <input wire:model="photo" type="file" accept="image/*"
                                class="im-input"
                                style="padding:7px 12px;font-size:11px;color:#64748b;cursor:pointer;flex:1">
                            @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" style="width:40px;height:40px;border-radius:10px;object-fit:cover;border:1px solid rgba(59,130,246,.3);flex-shrink:0">
                            @endif
                        </div>
                        @error('photo') <span class="im-field-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── ACTIONS --}}
            <div class="im-form-actions">
                <button type="button" wire:click="resetForm" class="im-btn-cancel">Batal / Reset</button>
                <button type="submit" class="im-btn-save">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ $editingId ? 'Simpan Perubahan' : 'Simpan Barang Baru' }}</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- ── INVENTORY LIST ────────────────────────────────────── --}}
    <div>
        {{-- List Header --}}
        <div class="im-list-header">
            <div style="display:flex;align-items:center">
                <span class="im-list-title">Daftar Barang Inventaris</span>
                <span class="im-count-badge">{{ $items->count() }} Item</span>
            </div>
            @if($search || $filterCategory || $filterStatus || $filterCondition)
            <button type="button" wire:click="$set('search','');$set('filterCategory','');$set('filterStatus','');$set('filterCondition','')" class="im-reset-link">
                <svg style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Filter
            </button>
            @endif
        </div>

        {{-- ── EMPTY STATE ──────────────────────────────────── --}}
        @if($items->isEmpty())
        <div class="im-empty">
            <div class="im-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;color:#475569" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h4 style="font-size:16px;font-weight:800;color:#f1f5f9;margin-bottom:6px">Tidak ada data barang</h4>
            <p style="font-size:12px;color:#64748b;max-width:360px;margin:0 auto 20px">
                @if($search || $filterCategory || $filterStatus || $filterCondition)
                Tidak ada hasil untuk kriteria pencarian atau filter Anda. Coba sesuaikan filter.
                @else
                Belum ada barang terdaftar dalam inventaris. Tambahkan barang baru untuk memulai.
                @endif
            </p>
            <div style="display:flex;justify-content:center;gap:8px">
                @if($search || $filterCategory || $filterStatus || $filterCondition)
                <button type="button" wire:click="$set('search','');$set('filterCategory','');$set('filterStatus','');$set('filterCondition','')" class="im-btn-cancel">Bersihkan Filter</button>
                @endif
                <button type="button" wire:click="toggleForm" class="im-add-btn">+ Tambah Barang Baru</button>
            </div>
        </div>

        {{-- ── GRID CARDS ─────────────────────────────────────── --}}
        @elseif($viewMode === 'grid')
        <div class="im-card-grid">
            @foreach($items as $item)
            @php
            $statusClass = match($item->status) {
                'Tersedia'    => 'badge-tersedia',
                'Dipinjam'    => 'badge-dipinjam',
                'Maintenance' => 'badge-maintenance',
                default       => 'badge-hilang',
            };
            $condColor = match($item->condition) {
                'Baik'        => '#34d399',
                'Rusak Ringan'=> '#fbbf24',
                default       => '#fb7185',
            };
            @endphp
            <div wire:key="card-{{ $item->id }}" class="im-card">
                {{-- Image --}}
                <div class="im-card-img">
                    @if($item->photo_path)
                    <img src="{{ asset('storage/'.$item->photo_path) }}" alt="{{ $item->name }}">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;color:#1e293b;stroke-width:1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    @endif
                    <div class="im-card-code">{{ $item->inventory_number }}</div>
                    <span class="im-card-status-badge {{ $statusClass }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                        {{ $item->status }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="im-card-body">
                    <div class="im-card-cat">
                        <svg style="width:10px;height:10px;color:#60a5fa" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $item->category?->name ?: 'Umum' }}
                    </div>
                    <div class="im-card-name">{{ $item->name }}</div>
                    <div class="im-card-meta">{{ implode(' · ', array_filter([$item->brand, $item->type, $item->purchase_year ? 'Th '.$item->purchase_year : null])) ?: 'Tanpa spesifikasi merk' }}</div>

                    <div class="im-card-info-row">
                        <div class="im-card-info-cell">
                            <div class="im-cell-label">Lokasi</div>
                            <div class="im-cell-val" style="display:flex;align-items:center;gap:4px">
                                <svg style="width:10px;height:10px;color:#64748b;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $item->location?->room ?? $item->location?->building ?? '—' }}
                            </div>
                        </div>
                        <div class="im-card-info-cell">
                            <div class="im-cell-label">Kondisi</div>
                            <div class="im-cell-val" style="color:{{ $condColor }}">{{ $item->condition }}</div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="im-card-footer">
                    <div>
                        <div class="im-stock-num">{{ $item->available_stock }} <span style="font-size:12px;font-weight:600;color:var(--text-subtle)">/ {{ $item->stock }}</span></div>
                        <div class="im-stock-sub">Tersedia / Total</div>
                    </div>
                    <div class="im-card-actions">
                        <button type="button" wire:click="edit({{ $item->id }})" class="im-btn-edit">
                            <svg style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Hapus barang '{{ $item->name }}'?" class="im-btn-del">
                            <svg style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── TABLE VIEW ──────────────────────────────────────── --}}
        @else
        <div class="im-table-wrap">
            <div style="overflow-x:auto">
                <table class="im-table">
                    <thead>
                        <tr>
                            <th style="width:40px" class="center">#</th>
                            <th>Barang & Kode</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th class="center">Tersedia / Total</th>
                            <th class="right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                        @php
                        $statusClass = match($item->status) {
                            'Tersedia'    => 'badge-tersedia',
                            'Dipinjam'    => 'badge-dipinjam',
                            'Maintenance' => 'badge-maintenance',
                            default       => 'badge-hilang',
                        };
                        $condColor = match($item->condition) {
                            'Baik'        => '#34d399',
                            'Rusak Ringan'=> '#fbbf24',
                            default       => '#fb7185',
                        };
                        @endphp
                        <tr wire:key="row-{{ $item->id }}">
                            <td class="center" style="color:#475569;font-family:monospace">{{ $i + 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="im-table-item-icon">
                                        <svg style="width:15px;height:15px;color:#60a5fa" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:#f1f5f9;font-size:13px">{{ $item->name }}</div>
                                        <div style="font-size:10px;font-family:monospace;color:#475569">{{ $item->inventory_number }}{{ $item->brand ? ' · '.$item->brand : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:5px;background:#1e293b;border:1px solid rgba(148,163,184,.1);border-radius:6px;padding:3px 9px;font-size:11px;font-weight:600;color:#94a3b8">
                                    {{ $item->category?->name ?: 'Umum' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:600;color:#e2e8f0;font-size:12px">{{ $item->location?->room ?? $item->location?->building ?? '—' }}</div>
                            </td>
                            <td><span style="font-weight:700;color:{{ $condColor }}">{{ $item->condition }}</span></td>
                            <td>
                                <span class="im-card-status-badge {{ $statusClass }}" style="position:static;font-size:10px">
                                    <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="center" style="font-weight:900;color:#60a5fa;font-size:14px">{{ $item->available_stock }} <span style="font-size:11px;color:#64748b;font-weight:600">/ {{ $item->stock }}</span></td>
                            <td class="right">
                                <div class="im-table-actions">
                                    <button type="button" wire:click="edit({{ $item->id }})" class="im-tbl-btn im-tbl-btn-edit" title="Edit Barang">
                                        <svg style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Hapus barang '{{ $item->name }}'?" class="im-tbl-btn im-tbl-btn-del" title="Hapus Barang">
                                        <svg style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

</div>

<script>
// ── Inventory Manager Custom Dropdown Handlers ──
function imToggleDrop(id, e) {
    e.stopPropagation();
    const el = document.getElementById(id);
    const isOpen = el.classList.contains('active');

    // Close all dropdowns first
    document.querySelectorAll('.im-dropdown.active').forEach(d => d.classList.remove('active'));

    // Open clicked (if it was closed)
    if (!isOpen) el.classList.add('active');
}

function imSelectItem(dropId, value, label, itemEl) {
    const drop = document.getElementById(dropId);

    // Update label in button
    drop.querySelector('.im-dropdown-label').textContent = label;

    // Toggle selected class
    drop.querySelectorAll('.im-dropdown-item').forEach(i => i.classList.remove('selected'));
    itemEl.classList.add('selected');

    // Toggle has-value dot indicator
    if (value === '') {
        drop.classList.remove('has-value');
    } else {
        drop.classList.add('has-value');
    }

    // Close dropdown
    drop.classList.remove('active');

    // Update Livewire property — find closest wire component
    const wireProp = drop.dataset.wire;
    const wireEl = drop.closest('[wire\\:id]');
    if (wireEl) {
        const wireId = wireEl.getAttribute('wire:id');
        const component = Livewire.find(wireId);
        if (component) {
            component.set(wireProp, value);
            return;
        }
    }
    // Fallback: find any inventory-manager component
    const allComponents = Livewire.all();
    for (const comp of allComponents) {
        if (typeof comp.get === 'function' && comp.get('filterCategory') !== undefined) {
            comp.set(wireProp, value);
            break;
        }
    }
}

// Close all dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.im-dropdown')) {
        document.querySelectorAll('.im-dropdown.active').forEach(d => d.classList.remove('active'));
    }
});
</script>
