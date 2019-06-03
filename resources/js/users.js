import {showMessage} from './helper.js'

$(function() {

    const columns = [
        { data: 'fullname',    name: 'fullname' },
        { data: 'user.email',  name: 'user.email' },
        { data: 'created_at',  name: 'created_at' },
        { data: 'age',         name: 'age' },
        { data: 'location',    name: 'location' },
        { data: 'status',      name: 'status' },
    ];
    const table = $('#users-table');
    const url = table.data('url');
    const stateBanned = 4;

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: true,
            "autoWidth": true,
            scrollX: true,
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

    $(document).on('click', '#ban', function (e) {
        const path = $(e.target).attr('data-ban-url');
        const message = $(e.target).attr('data-flash-message');
        $('#userBan').modal();
        $('#userBan').find('.confirm_ban').attr('data-route-ban', path);
        $('#userBan').find('.confirm_ban').attr('data-flash-message', message);
    });

    $(document).on('click', '#unBan', function (e) {
        const path = $(e.target).attr('data-ban-url');
        const message = $(e.target).attr('data-flash-message');
        $('#userUnBan').modal();
        $('#userUnBan').find('.confirm_ban').attr('data-route-ban', path);
        $('#userUnBan').find('.confirm_ban').attr('data-flash-message', message);
    });

    $(document).on('click', '#suspend', function (e) {
        const path = $(e.target).attr('data-suspend-url');
        const message = $(e.target).attr('data-flash-message');
        const reportsUrl = $(e.target).attr('data-reports-url');
        $.ajax({
            url: reportsUrl,
            method: 'GET',
            success: function (data) {
                let ticketsContainer = $('.reports-modal__tickets'),
                    ticketContainer = ticketsContainer.find('#example-ticket'),
                    suspendModal = $('#userSuspend');

                ticketsContainer.empty();
                ticketsContainer.append(ticketContainer);
                for (let ticket in data) {
                    let newTicket = ticketContainer.clone(),
                        newTicketRadio = newTicket.find('input[type=radio]'),
                        newTicketNoEl = newTicket.find('.reports-modal-ticket__no'),
                        newTicketReasonEl = newTicket.find('.reports-modal-ticket__reason');

                    newTicketRadio.attr('name', 'ticket');
                    newTicketRadio.attr('value', data[ticket].id);
                    newTicketNoEl.html(`Ticket #${data[ticket].id}`);
                    newTicketReasonEl.html(data[ticket].report_reason);

                    newTicket.removeAttr('id');
                    ticketsContainer.append(newTicket);
                }
                console.log(data);
                suspendModal.modal();
                suspendModal.find('.confirm_suspend').attr('data-route-suspend', path);
                suspendModal.find('.confirm_suspend').attr('data-flash-message', message);
            }
        });
    });

    $(document).on('click', '#unSuspend', function (e) {
        const path = $(e.target).attr('data-suspend-url');
        const message = $(e.target).attr('data-flash-message');
        $('#userUnSuspend').modal();
        $('#userUnSuspend').find('.confirm_suspend').attr('data-route-suspend', path);
        $('#userUnSuspend').find('.confirm_suspend').attr('data-flash-message', message);
    });

    $(document).on('click', '.press_row', function (e) {
        const state = $(e.currentTarget).find('.user_state').attr('data-status');
        const path = $(e.currentTarget).find('.click_row').data('owner-url'); 
        $('.user_info').remove();
        showInfo(path, state);
    });

    $('.confirm_ban').click(function (e) {
        const el = $(e.target);
        userBan(el);
    });

    $('.confirm_suspend').click(function (e) {
        const el = $(e.target);
        userSuspend(el);
    });

    function showInfo(path, state) {
        $.ajax({
            type: 'get',
            url:  path,
            success: function($data) {
                if (typeof($('.users_section')) != "undefined") {
                    $('.users_section').after($data);
                    if (state == stateBanned) {
                        $('#unBan').removeClass('element_none');
                        $('#ban').addClass('element_none');
                    }
                    else {
                        $('#ban').removeClass('element_none');
                        $('#unBan').addClass('element_none');
                    }
                    var scroller = document.getElementById('infoUser'); 
                    $('#infoUser').animate({
                        scrollTop: scroller.scrollIntoView(false)
                    }, 500);
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
                $('#userUnBan').modal('hide');
                showMessage(message);  
                table.dataTable().fnDestroy();  
                $('.user_info').remove();
                datatable();
            },
            error: function($error) {
            }
        });
    }

    function userSuspend(el) {
        const path = el.attr('data-route-suspend');
        const message = el.attr('data-flash-message');

        let data = {};
        if(el.attr('id') === 'suspendUser'){
            data['ticket'] = $('input[name="ticket"]:checked').val();
            if(!data['ticket']){
                showMessage('Before suspending a user, select a ticket.', 'alert_error');
                return false;
            }
        }

        $.ajax({
            type: 'get',
            url:  path,
            data: data,
            success: function() {
                $('#userSuspend').modal('hide');
                $('#userUnSuspend').modal('hide');
                showMessage(message);
                table.dataTable().fnDestroy();
                $('.user_info').remove();
                datatable();
            },
            error: function(err) {
                showMessage(err.responseJSON.message, 'alert_error');
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

    $(document).on('click', '.img_box', function (e) {
        $('#imgPets').find('.modal-dialog').children().remove();
        const url = $(e.target).attr('data-patch');
        const img = `<img class="img_modal" src="${url}">`;

        $('#imgPets').modal();
        $('#imgPets').find('.modal-dialog').append(img);
    });


});