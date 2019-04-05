$(function() {

    const columns = [
        { data: 'username',    searchable: false, orderable: false},
        { data: 'user.email',  name: 'email'},
        { data: 'created_at',  name: 'created_at' },
        { data: 'age',         name: 'age'},
        { data: 'location',    name: 'location'},
        { data: 'status',      name: 'status'},
    ];
    const table = $('#users-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: false,
            ajax: url,
            columns: columns
        });
    }

});