@extends('web::alliance.layouts.view', ['viewname' => 'structures', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.alliance', 1) . ' ' . trans('web::seat.tracking'))

@section('alliance_content')
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans_choice('web::seat.structure', 2) }}</h3>
                </div>
                <div class="card-body">
                    @include('alliance-structure-mngmt::filters.filters')

                    {{ $dataTable->table() }}
                </div>
            </div>

        </div>
    </div>

    @include('web::corporation.structures.modals.fitting.fitting')
@stop

@push('javascript')
    {!! $dataTable->scripts() !!}
    <script>
        $('#fitting-detail').on('show.bs.modal', function (e) {
            var body = $(e.target).find('.modal-body');
            body.html('Loading...');

            $.ajax($(e.relatedTarget).data('url'))
                .done(function (data) {
                    body.html(data);
                    $(document).find('span[data-toggle="tooltip"]').tooltip();
                });
        });

        $(document).on('click', '.copy-fitting', function (e) {
            var buffer = $(this).data('export');

            $('body').append('<textarea id="copied-fitting"></textarea>');
            $('#copied-fitting').val(buffer);
            document.getElementById('copied-fitting').select();
            document.execCommand('copy');
            document.getElementById('copied-fitting').remove();

            $(this).attr('data-original-title', 'Copied !')
                .tooltip('show');

            $(this).attr('data-original-title', 'Copy to clipboard');
        });
    </script>
@endpush

