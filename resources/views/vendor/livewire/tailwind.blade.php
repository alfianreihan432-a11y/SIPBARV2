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
        .sipbar-pagination-info {
            font-size: 0.875rem;
            color: var(--muted, #64748b);
            margin: 0;
            line-height: 1.5;
        }
        .sipbar-pagination-info strong,
        .sipbar-pagination-info .sipbar-highlight {
            color: var(--text, #1e293b);
            font-weight: 600;
        }
        .sipbar-pagination-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .sipbar-page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
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
        .sipbar-page-btn.active {
            background: var(--primary, #2563eb) !important;
            border-color: var(--primary, #2563eb) !important;
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
            cursor: default;
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
            transition: transform 0.15s ease;
        }
        .sipbar-page-btn:hover:not(:disabled):not(.disabled) .sipbar-icon-prev {
            transform: translateX(-2px);
        }
        .sipbar-page-btn:hover:not(:disabled):not(.disabled) .sipbar-icon-next {
            transform: translateX(2px);
        }
        .sipbar-page-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 40px;
            color: var(--muted, #94a3b8);
            font-weight: 600;
            font-size: 0.875rem;
            user-select: none;
        }
        @media (max-width: 640px) {
            .sipbar-pagination-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 14px;
                gap: 12px;
            }
            .sipbar-pagination-controls {
                justify-content: center;
                width: 100%;
                gap: 6px;
            }
            .sipbar-page-btn {
                min-width: 38px;
                height: 38px;
                padding: 0 8px;
                font-size: 0.8125rem;
            }
        }
    </style>

    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="sipbar-pagination-container">
            <div class="sipbar-pagination-info">
                <span>Showing</span>
                <span class="sipbar-highlight">{{ $paginator->firstItem() }}</span>
                <span>to</span>
                <span class="sipbar-highlight">{{ $paginator->lastItem() }}</span>
                <span>of</span>
                <span class="sipbar-highlight">{{ $paginator->total() }}</span>
                <span>results</span>
            </div>

            <div class="sipbar-pagination-controls">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="sipbar-page-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <svg class="sipbar-icon-prev" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="sipbar-page-btn"
                            aria-label="{{ __('pagination.previous') }}">
                        <svg class="sipbar-icon-prev" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="sipbar-page-dots" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                @if ($page == $paginator->currentPage())
                                    <span class="sipbar-page-btn active" aria-current="page">{{ $page }}</span>
                                @else
                                    <button type="button"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                            wire:loading.attr="disabled"
                                            class="sipbar-page-btn"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            </span>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="sipbar-page-btn"
                            aria-label="{{ __('pagination.next') }}">
                        <svg class="sipbar-icon-next" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <span class="sipbar-page-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <svg class="sipbar-icon-next" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
