$(function() {

    const columns = [
        { data: 'username',    name: 'username' },
        { data: 'email',       name: 'email' },
        { data: 'date',        name: 'date' },
        { data: 'time',        name: 'time' },
        { data: 'ticket',      name: 'ticket' },
        { data: 'status',      name: 'status' },
    ];
    const table = $('#tickets-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: true,
            "autoWidth": true,
            ajax: url,
            columns: columns,
        });
    }

});