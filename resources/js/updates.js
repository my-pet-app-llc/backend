import {showMessage} from './helper.js'

$(function() {

    const message = $('.flesh_message').data('message');
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
            serverSide: true,
            ajax: url,
            columns: columns
        });
    }

    $(document).on('click', '.delete_update', function (e) {
        $('#deleteUpdate').modal();
        const form = $(e.target).closest('form');
        const url = form.attr('action');
        const token = form.find('[name="_token"]').val();

        $('#removeUpdate').attr('data-route', url);
        $('#removeUpdate').attr('data-token', token);
    });

    $('.remove_update').on('click', function (e) {
        e.preventDefault();
        const url = $(e.target).attr('data-route');
        const token = $(e.target).attr('data-token');
        const flashMessage = $(e.target).data('flash-message')
        $.ajax({
            type: 'DELETE',
            url:  url,
            data: {'_token': token},
            success: function($data) {
                $('#deleteUpdate').modal('hide');
                showMessage(flashMessage);  
                table.DataTable().clear().draw();    
            },
            error: function($error) {
            }
        }); 
    });

    $(document).ready(function () {
        if (typeof(message) != "undefined") {
            showMessage(message); 
        }
    });
});