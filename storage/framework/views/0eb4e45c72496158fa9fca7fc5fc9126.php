<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['iconClass' => 'topbar-icon']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['iconClass' => 'topbar-icon']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div class="notif-widget-wrapper" style="position:relative;display:inline-block;">
    <button type="button" class="<?php echo e($iconClass); ?>" id="messageIconBtn" title="Pesan" aria-label="Buka Pesan" style="position:relative;cursor:pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <div class="notif-dot" id="messageDot" style="display:none;"></div>
    </button>

    
    <div class="notif-dropdown-panel" id="messageDropdown" style="display:none;" role="dialog" aria-label="Panel Pesan">
        <div class="notif-dropdown-header">
            <div class="notif-header-title">
                <span>Pesan</span>
                <span class="notif-count-badge" id="messageCountBadge" style="display:none;">0</span>
            </div>
            <button type="button" class="notif-mark-all-btn" id="markAllMessagesReadBtn">
                Tandai semua dibaca
            </button>
        </div>
        <div class="notif-list-container" id="messageList">
            <div class="notif-empty-state">Memuat pesan...</div>
        </div>
    </div>
</div>


<div class="notif-widget-wrapper" style="position:relative;display:inline-block;">
    <button type="button" class="<?php echo e($iconClass); ?>" id="notifIconBtn" title="Notifikasi" aria-label="Buka Notifikasi" style="position:relative;cursor:pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <div class="notif-dot" id="notifDot" style="display:none;"></div>
    </button>

    
    <div class="notif-dropdown-panel" id="notifDropdown" style="display:none;" role="dialog" aria-label="Panel Notifikasi">
        <div class="notif-dropdown-header">
            <div class="notif-header-title">
                <span>Notifikasi</span>
                <span class="notif-count-badge" id="notifCountBadge" style="display:none;">0</span>
            </div>
            <button type="button" class="notif-mark-all-btn" id="markAllNotifsReadBtn">
                Tandai semua dibaca
            </button>
        </div>
        <div class="notif-list-container" id="notifList">
            <div class="notif-empty-state">Memuat notifikasi...</div>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('911e7ef1-a348-41c7-b704-17dab9668540')): $__env->markAsRenderedOnce('911e7ef1-a348-41c7-b704-17dab9668540'); ?>
<style>
    .notif-widget-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .notif-dropdown-panel {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 340px;
        max-width: calc(100vw - 32px);
        background: var(--bg-card, var(--card, var(--panel-bg, #ffffff)));
        border: 1px solid var(--border-main, var(--border2, var(--border, #e2e8f0)));
        border-radius: 14px;
        box-shadow: 0 14px 35px -4px rgba(0, 0, 0, 0.25), 0 4px 14px -2px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        overflow: hidden;
        animation: notifSlideDown 0.18s ease-out;
        color: var(--text-primary, var(--text, #0f172a));
        font-family: inherit;
        text-align: left;
    }

    @keyframes notifSlideDown {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .notif-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-subtle, var(--border2, var(--border, #e2e8f0)));
        background: var(--bg-card-subtle, var(--bg3, rgba(0,0,0,0.02)));
    }

    .notif-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary, var(--text, #0f172a));
    }

    .notif-count-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 999px;
        background: #ef4444;
        color: #ffffff;
    }

    .notif-mark-all-btn {
        background: none;
        border: none;
        padding: 4px 6px;
        font-size: 11px;
        font-weight: 600;
        color: var(--blue, var(--primary, var(--accent, #2563eb)));
        cursor: pointer;
        border-radius: 6px;
        transition: opacity 0.15s;
    }
    .notif-mark-all-btn:hover {
        opacity: 0.75;
        text-decoration: underline;
    }

    .notif-list-container {
        max-height: 360px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }
    .notif-list-container::-webkit-scrollbar {
        width: 5px;
    }
    .notif-list-container::-webkit-scrollbar-thumb {
        background: var(--scrollbar, #cbd5e1);
        border-radius: 4px;
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-subtle, var(--border2, var(--border, rgba(148,163,184,0.12))));
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
        color: inherit;
    }
    .notif-item:last-child {
        border-bottom: none;
    }
    .notif-item:hover {
        background: var(--bg-hover, var(--bg3, rgba(148,163,184,0.1)));
    }
    .notif-item.unread {
        background: var(--bg-card-subtle, var(--bg3, rgba(37,99,235,0.06)));
    }

    .notif-item-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--bg-card-subtle, var(--bg3, #f1f5f9));
        color: var(--blue, var(--primary, var(--accent, #2563eb)));
    }

    .notif-item-content {
        flex: 1;
        min-width: 0;
    }
    .notif-item-msg {
        font-size: 12px;
        line-height: 1.45;
        color: var(--text-primary, var(--text, #0f172a));
        word-break: break-word;
        font-weight: 500;
    }
    .notif-item.unread .notif-item-msg {
        font-weight: 600;
    }
    .notif-item-time {
        font-size: 10px;
        color: var(--text-subtle, var(--subtle, #94a3b8));
        margin-top: 4px;
    }

    .notif-unread-indicator {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ef4444;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .notif-empty-state {
        padding: 28px 16px;
        text-align: center;
        font-size: 12px;
        color: var(--text-subtle, var(--subtle, #94a3b8));
    }
</style>

<script>
(function(){
    const state = {
        message: { list: [], unread: 0, loaded: false },
        notification: { list: [], unread: 0, loaded: false }
    };

    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function timeAgo(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        if (seconds < 60) return 'Baru saja';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes} mnt lalu`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours} jam lalu`;
        const days = Math.floor(hours / 24);
        if (days < 30) return `${days} hari lalu`;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    }

    function updateBadgeAndDot(category, count) {
        const dot = document.getElementById(category === 'message' ? 'messageDot' : 'notifDot');
        const badge = document.getElementById(category === 'message' ? 'messageCountBadge' : 'notifCountBadge');

        if (dot) {
            dot.style.display = count > 0 ? 'block' : 'none';
        }
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    async function fetchNotifications(category) {
        try {
            const res = await fetch(`/notifications?category=${encodeURIComponent(category)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data && data.success) {
                state[category].list = data.notifications || [];
                state[category].unread = data.unread_count || 0;
                state[category].loaded = true;
                updateBadgeAndDot(category, state[category].unread);
                renderList(category);
            }
        } catch(e) {
            console.error('Error fetching ' + category, e);
        }
    }

    function renderList(category) {
        const container = document.getElementById(category === 'message' ? 'messageList' : 'notifList');
        if (!container) return;

        const items = state[category].list;
        if (!items || items.length === 0) {
            container.innerHTML = `<div class="notif-empty-state">Tidak ada ${category === 'message' ? 'pesan' : 'notifikasi'} baru</div>`;
            return;
        }

        let html = '';
        items.forEach(item => {
            const isUnread = !item.is_read;
            const msg = escapeHtml(item.message);
            const time = timeAgo(item.created_at);
            const iconSvg = category === 'message'
                ? `<svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
                : `<svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`;

            html += `
                <div class="notif-item ${isUnread ? 'unread' : ''}" data-id="${item.id}" data-category="${category}">
                    <div class="notif-item-icon">
                        ${iconSvg}
                    </div>
                    <div class="notif-item-content">
                        <div class="notif-item-msg">${msg}</div>
                        <div class="notif-item-time">${time}</div>
                    </div>
                    ${isUnread ? '<div class="notif-unread-indicator"></div>' : ''}
                </div>
            `;
        });

        container.innerHTML = html;

        // Attach click events on items
        container.querySelectorAll('.notif-item').forEach(el => {
            el.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                const cat = this.getAttribute('data-category');
                if (this.classList.contains('unread')) {
                    this.classList.remove('unread');
                    const ind = this.querySelector('.notif-unread-indicator');
                    if (ind) ind.remove();

                    // Optimistic update
                    if (state[cat].unread > 0) {
                        state[cat].unread--;
                        updateBadgeAndDot(cat, state[cat].unread);
                    }

                    try {
                        await fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrf(),
                                'Accept': 'application/json'
                            }
                        });
                    } catch(e) {
                        console.error('Error marking as read', e);
                    }
                }
            });
        });
    }

    async function markAllRead(category) {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ category })
            });

            state[category].unread = 0;
            state[category].list.forEach(i => { i.is_read = true; });
            updateBadgeAndDot(category, 0);
            renderList(category);
        } catch(e) {
            console.error('Error markAllRead', e);
        }
    }

    function setupDropdown(btnId, dropdownId, category) {
        const btn = document.getElementById(btnId);
        const dropdown = document.getElementById(dropdownId);
        if (!btn || !dropdown) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.style.display === 'block';

            // Close all dropdowns first
            document.querySelectorAll('.notif-dropdown-panel').forEach(d => d.style.display = 'none');

            if (!isOpen) {
                dropdown.style.display = 'block';
                fetchNotifications(category);
            }
        });
    }

    function init() {
        setupDropdown('messageIconBtn', 'messageDropdown', 'message');
        setupDropdown('notifIconBtn', 'notifDropdown', 'notification');

        // Mark all read buttons
        document.getElementById('markAllMessagesReadBtn')?.addEventListener('click', (e) => {
            e.stopPropagation();
            markAllRead('message');
        });
        document.getElementById('markAllNotifsReadBtn')?.addEventListener('click', (e) => {
            e.stopPropagation();
            markAllRead('notification');
        });

        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notif-widget-wrapper')) {
                document.querySelectorAll('.notif-dropdown-panel').forEach(d => d.style.display = 'none');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.notif-dropdown-panel').forEach(d => d.style.display = 'none');
            }
        });

        // Initial fetch for count/dot
        fetchNotifications('notification');
        fetchNotifications('message');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
<?php endif; ?>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/partials/notif-widget.blade.php ENDPATH**/ ?>