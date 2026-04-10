{{-- Per-column filter toggle button. Include once per list page before the <table>. --}}
<div style="margin-bottom:8px;">
    <button type="button" id="toggleFilterBtn" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:13px;padding:4px 12px;">
        <i class="fa-solid fa-filter" style="font-size:11px;"></i> Filter
    </button>
</div>
<script src="{{ asset('js/list-filter.js') }}" defer></script>
