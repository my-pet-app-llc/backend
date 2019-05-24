$(function() {

    const columns = [
        { data: 'owner.full_name',  name: 'owner.full_name' },
        { data: 'owner.user.email', name: 'owner.user.email' },
        { data: 'date',             name: 'date' },
        { data: 'time',             name: 'time' },
        { data: 'id',               name: 'id' },
        { data: 'status',           name: 'status' },
    ];
    const table = $('#tickets-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: true,
            "autoWidth": true,
            "bSort": true,
            scrollX: true,
            ajax: url,
            columns: columns,
            createdRow: function (row, data) {
                $(row).attr('data-ticket', data.id);
                const td = $(row).find('td').last();
                const status = td.find('.ticket_state').attr('data-status');
                const stateClass = 'bg_status_' + status;
                td.addClass(stateClass);

                $(row).css('cursor', 'pointer')
                $(row).addClass('press_row');
            }
        });
    }

});