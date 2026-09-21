@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView({ behavior: 'smooth' })
    JS
    : '';
@endphp

<div class="sipbar-pagination-wrapper">
    <style>
        .sipbar-pagination-wrapper {
            width: 100%;
            margin-top: 16px;
        }
        .sipbar-pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 14px 20px;
            background: var(--card, #ffffff);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }
        .sipbar-page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 40px;
            height: 40px;
            padding: 0 16px;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1;
            border-radius: 10px;
            border: 1px solid var(--border, #e2e8f0);
            background: var(--card, #ffffff);
            color: var(--text, #334155);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.18s ease-in-out;
            user-select: none;
            box-sizing: border-box;
        }
        .sipbar-page-btn:hover:not(:disabled):not(.active):not(.disabled) {
            border-color: var(--primary, #2563eb);
            color: var(--primary, #2563eb);
            background: var(--primary-light, #eff6ff);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.12);
        }
        .sipbar-page-btn.disabled,
        .sipbar-page-btn:disabled {
            opacity: 0.45;
            background: var(--bg2, #f8fafc);
            border-color: var(--border, #e2e8f0);
            color: var(--subtle, #94a3b8);
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
            transform: none;
        }
        .sipbar-page-btn svg {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
        }
    </style>

    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="sipbar-pagination-container">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="sipbar-page-btn disabled" aria-disabled="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>{!! __('pagination.previous') !!}</span>
                    </span>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        @php($previousCursor = $paginator->previousCursor() ?? $paginator->cursor())
                        <button type="button"
                                dusk="previousPage"
                                wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $previousCursor?->encode() }}"
                                wire:click="setPage('{{ $previousCursor?->encode() }}','{{ $paginator->getCursorName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="sipbar-page-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>{!! __('pagination.previous') !!}</span>
                        </button>
                    @else
                        <button type="button"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                class="sipbar-page-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>{!! __('pagination.previous') !!}</span>
                        </button>
                    @endif
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        @php($nextCursor = $paginator->nextCursor() ?? $paginator->cursor())
                        <button type="button"
                                dusk="nextPage"
                                wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $nextCursor?->encode() }}"
                                wire:click="setPage('{{ $nextCursor?->encode() }}','{{ $paginator->getCursorName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="sipbar-page-btn">
                            <span>{!! __('pagination.next') !!}</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @else
                        <button type="button"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                class="sipbar-page-btn">
                            <span>{!! __('pagination.next') !!}</span>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif
                @else
                    <span class="sipbar-page-btn disabled" aria-disabled="true">
                        <span>{!! __('pagination.next') !!}</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </span>
        </nav>
    @endif
</div>
