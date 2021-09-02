<div class="mb-3">
    <div class="btn-group d-flex">
        <button type="button" data-filter-field="fuel" data-filter-value="low_power" class="btn btn-danger dt-filters">
            @lang('alliance-structure-mngmt::alliance-structure-table.filter_low_power')
        </button>
        <button type="button" data-filter-field="fuel" data-filter-value="less_than_week" class="btn btn-warning dt-filters">
            @lang('alliance-structure-mngmt::alliance-structure-table.filter_less_week')
        </button>
        <button type="button" data-filter-field="fuel" data-filter-value="less_than_month" class="btn btn-primary dt-filters">
            @lang('alliance-structure-mngmt::alliance-structure-table.filter_less_month')
        </button>
    </div>
</div>

@push('javascript')
    <script>
        $(document).ready(function () {
            $('.dt-filters')
                .on('click', function () {
                    var clicked  = this;
                    $("[data-filter-field].dt-filters.active").each(function(i, e) {
                        var a = $(e);
                        if (e !== clicked) {
                            a.removeClass("active");
                        }
                    });

                    $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
                    window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                });
        });
    </script>
@endpush