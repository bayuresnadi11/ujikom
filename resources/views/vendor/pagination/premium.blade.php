@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="premium-pagination-container">
        <div class="pagination-wrapper flex items-center justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="{{ __('Pagination Previous') }}">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="{{ __('Pagination Previous') }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="{{ __('Pagination Next') }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="{{ __('Pagination Next') }}">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>

        <style>
            .premium-pagination-container {
                display: flex;
                justify-content: center;
                margin-top: 30px;
                margin-bottom: 20px;
                padding: 10px;
            }
            .pagination-wrapper {
                display: flex;
                gap: 8px;
                align-items: center;
                background: rgba(255, 255, 255, 0.8);
                padding: 8px 15px;
                border-radius: 50px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                backdrop-filter: blur(5px);
            }
            .pagination-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 38px;
                height: 38px;
                padding: 0 5px;
                border-radius: 50%;
                font-weight: 600;
                font-size: 14px;
                color: #334155;
                text-decoration: none;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid transparent;
            }
            .pagination-btn:hover:not(.disabled):not(.active) {
                background-color: #f1f5f9;
                color: #8B1538;
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(139, 21, 56, 0.1);
            }
            .pagination-btn.active {
                background: linear-gradient(135deg, #8B1538 0%, #6B0F2A 100%);
                color: white;
                box-shadow: 0 4px 12px rgba(139, 21, 56, 0.3);
                border-color: #8B1538;
                transform: scale(1.1);
            }
            .pagination-btn.disabled {
                color: #cbd5e1;
                cursor: not-allowed;
                opacity: 0.6;
            }
            .pagination-dots {
                color: #94a3b8;
                font-weight: bold;
                padding: 0 2px;
            }

            @media (max-width: 480px) {
                .pagination-wrapper {
                    gap: 4px;
                    padding: 6px 10px;
                }
                .pagination-btn {
                    min-width: 32px;
                    height: 32px;
                    font-size: 12px;
                }
            }
        </style>
    </nav>
@endif
