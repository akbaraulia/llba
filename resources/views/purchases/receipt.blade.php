@extends('layouts.app')

@section('title', 'Struk Pembelian')
@section('page_title', 'Struk Pembelian')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="panel-card p-3 p-lg-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1">{{ $purchase->invoice_number }}</h4>
                        <div class="muted">{{ $purchase->purchase_date->format('d/m/Y H:i') }}</div>
                        <div class="muted">Kasir: {{ $purchase->user->name }}</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-dark btn-sm">Kembali</a>
                        <a href="{{ $pdfDownloadUrl }}" id="btnDownloadPdf" class="btn btn-outline-primary btn-sm">Download
                            PDF</a>
                        <button type="button" id="btnPrintPdf" class="btn btn-dark btn-sm">Print</button>
                    </div>
                </div>

                <div class="mb-3" style="border:1px solid #e6d8c9; border-radius:12px; overflow:hidden;">
                    <iframe id="pdfPreviewFrame" src="{{ $pdfPreviewUrl }}" title="Preview Receipt PDF"
                        style="width:100%; height:75vh; border:0;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const printButton = document.getElementById('btnPrintPdf');
        const previewFrame = document.getElementById('pdfPreviewFrame');
        const previewUrl = '{{ $pdfPreviewUrl }}';

        printButton.addEventListener('click', () => {
            if (previewFrame?.contentWindow) {
                previewFrame.contentWindow.focus();
                previewFrame.contentWindow.print();
                return;
            }

            window.open(previewUrl, '_blank');
        });
    </script>
@endpush
