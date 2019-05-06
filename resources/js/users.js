import {showMessage} from './helper.js'

$(function() {

    const columns = [
        { data: 'fullname',    name: 'fullname' },
        { data: 'user.email',       name: 'user.email' },
        { data: 'created_at',  name: 'created_at' },
        { data: 'age',         name: 'age' },
        { data: 'location',    name: 'location' },
        { data: 'status',      name: 'status' },
    ];
    const table = $('#users-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: true,
            "autoWidth": true,
            ajax: url,
            columns: columns,
            "createdRow": function(row) {
                const td = $(row).find('td').last();
                const status = td.find('.user_state').attr('data-status');
                const stateClass = 'bg_status_' + status;
                td.addClass(stateClass);

                $(row).css('cursor', 'pointer')
                $(row).addClass('press_row');
              }
        });
    }

    $(document).on('click', '.btn_ban', function (e) {
        const path = $(e.target).attr('data-ban-url');
        $('#userBan').modal();
        $('#userBan').find('.confirm_ban').attr('data-route-ban', path);
    });

    $(document).on('click', '.press_row', function (e) {
        const path = $(e.currentTarget).find('.click_row').data('owner-url');
        $('.user_info').remove();
        showInfo(path);
    });

    $('.confirm_ban').click(function (e) {
        const el = $(e.target);
        $('#userBan').modal();
        userBan(el);
    });

    function showInfo(path) {
        $.ajax({
            type: 'get',
            url:  path,
            success: function($data) {
                if (typeof($('.users_section')) != "undefined") {
                    $('.users_section').after($data);
                }
            },
            error: function($error) {
            }
        });
    }

    function userBan(el) {
        const path = el.attr('data-route-ban');
        const message = el.attr('data-flash-message');
        $.ajax({
            type: 'get',
            url:  path,
            success: function($data) {
                $('#userBan').modal('hide');
                showMessage(message);  
                table.dataTable().fnDestroy();  
                $('.user_info').remove();
                datatable();
            },
            error: function($error) {
            }
        });
    }
    
    $('.sidebar-link').each(function () {
        let href = window.location.href;
        let url = $(this).attr('href');
        if (href === url) {
            $(this).addClass('active_item');
            $(this).closest('li').addClass('active_item');
        }
    }); 

    $(document).on('click', '.img_pets', function (e) {
        $('#imgPets').find('.wrapp_img').children().remove();
        const img = $(e.target).clone().css({
            'height': '100%',
            'border-radius': '19px'
        });
        $('#imgPets').modal();
        $('#imgPets').find('.wrapp_img').append(img);
    });
});