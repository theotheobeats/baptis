@push('report-title')
    <title>Export</title>
@endpush

@push('report-css')
<style>
    .no-print {
        position: fixed; bottom: 15px; width: 100%; text-align: center; gap: 20px; margin-bottom: 10px;
    }

    .no-print button {
        padding: 10px 20px;
        color: white;
        background-color: rgba(0, 0, 0, 0.3);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.1s ease;
    }

    .no-print button:active {
        transform: scale(0.95);
    }

    @media print {
        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>
@endpush

@push('report-print_control')
    @php
        function number_format_custom($number, $prefix = null)
        {
            if (!empty(Request::get('type'))) {
                return $number;
            }
            return $prefix . number_format($number);
        }
    @endphp
    @if (empty(Request::get('type')))
    <center>
        <div class="no-print">
            <button onclick="window.print()">
                Cetak
            </button>
            <button onclick="exportFile('excel')">
                Export Excel
            </button>
        </div>
    </center>
    @endif
@endpush

@push('report-js')
<script>
    function exportFile(type) {
        let url = window.location.href + `&type=${type}`;
        window.location.href = url;
    }
</script>
@endpush
