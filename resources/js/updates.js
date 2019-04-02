$(function() {

    const columns = [
        { data: 'title', name: 'title' },
        { data: 'created_at', name: 'created_at' },
        { data: 'remove_btn', searchable: false, orderable: false, },
    ];
    const table = $('#updates-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: columns
        });
    }

});