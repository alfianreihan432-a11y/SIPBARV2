{{--
  Reusable theme toggle button + JS.
  Usage: @include('partials._theme-toggle', ['key' => 'sipbar-dash-theme'])
--}}
@php $storageKey = $key ?? 'sipbar-dash-theme'; @endphp

{{-- Anti-flash: run BEFORE anything renders --}}
<script>
(function(){
  var k='{{ $storageKey }}',s=localStorage.getItem(k);
  var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
  if(s==='dark'||(s===null&&d)) document.documentElement.classList.add('dark');
  else document.documentElement.classList.remove('dark');
})();
</script>

<style>
/* ── Toggle button ── */
.theme-btn{
  display:flex;align-items:center;justify-content:center;
  width:34px;height:34px;border-radius:8px;cursor:pointer;
  border:1.5px solid var(--d-border,#334155);
  background:var(--d-card,#1e293b);
  color:var(--d-muted,#94a3b8);
  transition:all .2s,transform .25s;flex-shrink:0;
}
.theme-btn:hover{border-color:#3b82f6;color:#3b82f6;transform:rotate(12deg)}
.theme-btn .t-sun{display:none}
.theme-btn .t-moon{display:block}
html.dark .theme-btn .t-sun{display:block}
html.dark .theme-btn .t-moon{display:none}
</style>

<button class="theme-btn" id="themeBtn" aria-label="Toggle tema" title="Ganti Mode (Alt+D)">
  <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
  </svg>
  <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
  </svg>
</button>

<script>
document.addEventListener('DOMContentLoaded',function(){
  var btn=document.getElementById('themeBtn');
  var html=document.documentElement;
  var KEY='{{ $storageKey }}';
  function toggle(){
    var dark=html.classList.toggle('dark');
    localStorage.setItem(KEY,dark?'dark':'light');
    btn.style.transform='rotate(20deg) scale(.85)';
    setTimeout(function(){btn.style.transform=''},250);
  }
  if(btn) btn.addEventListener('click',toggle);
  document.addEventListener('keydown',function(e){if(e.altKey&&e.key==='d')toggle();});
});
</script>
