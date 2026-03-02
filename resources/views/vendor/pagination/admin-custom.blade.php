@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:4px;" aria-label="Pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;cursor:default;font-size:13px;" aria-disabled="true">
            <i class="bi bi-chevron-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#0061A5;cursor:pointer;font-size:13px;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#0061A5'" onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0'" rel="prev">
            <i class="bi bi-chevron-left"></i>
        </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:13px;color:#94a3b8;">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;background:#0061A5;color:#fff;font-size:13px;font-weight:600;border:1px solid #0061A5;" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:13px;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='#eff6ff';this.style.color='#0061A5';this.style.borderColor='#0061A5'" onmouseout="this.style.background='#fff';this.style.color='#475569';this.style.borderColor='#e2e8f0'">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;background:#fff;color:#0061A5;cursor:pointer;font-size:13px;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#0061A5'" onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0'" rel="next">
            <i class="bi bi-chevron-right"></i>
        </a>
    @else
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;cursor:default;font-size:13px;" aria-disabled="true">
            <i class="bi bi-chevron-right"></i>
        </span>
    @endif
</nav>
@endif
