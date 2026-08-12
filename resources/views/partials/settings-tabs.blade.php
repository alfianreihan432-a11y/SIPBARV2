{{-- Reusable Settings Tab Navigation --}}
<div style="display:flex;gap:0;background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:14px;padding:4px;width:fit-content">
    @php $tabs = [
        ['route' => 'profile.edit', 'label' => 'Profil', 'key' => 'profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['route' => 'security.edit', 'label' => 'Keamanan', 'key' => 'security', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
        ['route' => 'appearance.edit', 'label' => 'Tampilan', 'key' => 'appearance', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
    ]; @endphp
    @foreach($tabs as $tab)
        @php $isActive = ($active ?? '') === $tab['key']; @endphp
        <a href="{{ route($tab['route']) }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;transition:all .2s;white-space:nowrap;{{ $isActive ? 'background:var(--blue-dark);color:#fff;box-shadow:0 4px 12px rgba(29,78,216,.3)' : 'color:var(--text-muted)' }}" {{ $isActive ? '' : 'onmouseover="this.style.color=\'var(--text-primary)\'" onmouseout="this.style.color=\'var(--text-muted)\'"' }}>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
