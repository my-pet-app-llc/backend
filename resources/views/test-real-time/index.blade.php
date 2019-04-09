<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="row" style="margin: 0;">
    <div class="col-md-8 col-md-offset-2" style="margin-top: 10px;">
        <h3 style="display: inline-block;">Chats</h3>
        <p id="not-read" style="width: 10px; height: 10px; background: #1028f5; border-radius: 50%; display: inline-block;"></p>
        <div class="row" id="chats-cont" style="border: 1px solid; margin-right: 5px; margin-left: 5px; padding-top: 5px; border-radius: 10px; margin-bottom: 10px;"></div>
    </div>
</div>

<div class="row" id="room" style="display: none; margin: 0;">
    <div class="col-md-8 col-md-offset-2">
        <h3 style="display: inline-block; width: 100px;">Chat</h3>
        <p id="close-chat" style="display: inline-block; color: red; cursor: pointer;">Close</p>
        <div class="row" id="chat-cont" style="height: 400px; overflow: scroll; margin: 0; border: 1px solid; padding-top: 10px;"></div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="row" style="margin-top: 10px; margin-bottom: 20px;">
            <div class="col-sm-8">
                <input type="text" id="message" style="width: 100%; padding-left: 5px; height: 30px; border: 1px solid; border-radius: 5px;">
            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary" id="send-message">Send message</button>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="row" style="margin-top: 10px; margin-bottom: 20px;">
            <div class="col-sm-8">

            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary" id="send-image">Send image</button>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 40px; margin: 0;">
    <div class="col-md-12">
        <button class="btn btn-primary" id="new-message">New Message</button>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    window.user = '{{ $user }}';
    window.token = '{{ $token }}';
    window.chatPage = 1;
</script>
<script src="{{ asset('js/test-real-time.js') }}"></script>
<script>
    $(document).ready(function () {
        $.ajax({
            headers: {
                'Authorization': 'Bearer ' + window.token
            },
            url: '/api/chats',
            method: 'GET',
            success: function (data) {
                console.log(data);
                if(!data){
                    $('#chats-cont').html('<div class="col-md-12">Not chats</div>');
                }else{
                    let isRead = true;
                    for (let chat in data) {
                        $('#chats-cont').html('');
                        let lastMessage = '';
                        if(data[chat].last_message){
                            if(data[chat].last_message.type === 'text'){
                                lastMessage = data[chat].last_message.message.message;
                            }else if(data[chat].last_message.type === 'image'){
                                lastMessage = 'Image';
                            }
                        }
                        $('#chats-cont').append(`<div class="col-sm-12 chat" data-room="${data[chat].id}">
                                                    <div class="row">
                                                        <div class="col-sm-1"><img src="${data[chat].picture}" style="width: 40px; height: 40px;" /></div>
                                                        <div class="col-sm-8">
                                                            <h5 style="font-weight: 600;">${data[chat].name}</h5>
                                                            <p class="last-message">${lastMessage}</p>
                                                        </div>
                                                        <div class="col-sm-2 is-read" style="color: #1027f5;">${data[chat].is_read ? '' : 'Not read'}</div>
                                                    </div>
                                                 </div>`);
                        if(isRead && !data[chat].is_read)
                            isRead = false;
                    }
                    if(isRead){
                        $('#not-read').hide();
                    }
                }
            }
        })

        $(document).on('click', '.chat', function () {
            $('#not-read').hide();
            $(this).find('.is-read').html('');
            let room = $(this).attr('data-room');
            $('#chat-cont').html('');
            getMessagesInRoom(room);
        })

        $('#close-chat').click(function () {
            closeChat($(this).attr('data-room'));
            $(this).attr('data-room', '');
            $('#room').hide();
        })

        $('#new-message').click(function () {
            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + window.token
                },
                url: '/api/chats',
                method: 'POST',
                data: {
                    friend_id: (Number(window.user) === 1 ? 2 : 1)
                },
                success: function (data) {
                    $('#chat-cont').html('');
                    $('#room').show();
                    $('#close-chat').attr('data-room', data.room_id);
                    $('#send-message').attr('data-room', data.room_id);
                    chat(data.room_id);
                }
            });
        });

        $('#send-message').click(function () {
            let message = $('#message').val(),
                room = $(this).attr('data-room');

            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + window.token
                },
                url: '/api/chats/' + room,
                method: 'POST',
                data: {
                    type: 'text',
                    message: message
                },
                success: function (data) {
                    console.log(data);
                    $('#chat-cont').append(`<div class="col-sm-12 chat">
                                                    <div class="row">
                                                        <div class="col-sm-1"><img src="${data.sender.profile_picture}" style="width: 30px;" /></div>
                                                        <div class="col-sm-8">
                                                            <h5 style="font-weight: 600;">${data.sender.name}</h5>
                                                            <p>${data.message.message}</p>
                                                        </div>
                                                    </div>
                                                 </div>`);
                    $('#message').val('');
                    $('#chat-cont').scrollTop($('#chat-cont')[0].scrollHeight);
                }
            });
        });

        $('#send-image').click(function () {
            let message = 'data:image/jpeg;base64,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',
                room = $(this).attr('data-room');

            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + window.token
                },
                url: '/api/chats/' + room,
                method: 'POST',
                data: {
                    type: 'image',
                    message: message
                },
                success: function (data) {
                    console.log(data);
                    $('#chat-cont').append(`<div class="col-sm-12 chat">
                                                    <div class="row">
                                                        <div class="col-sm-1"><img src="${data.sender.profile_picture}" style="width: 30px;" /></div>
                                                        <div class="col-sm-8">
                                                            <h5 style="font-weight: 600;">${data.sender.name}</h5>
                                                            <p><img src="${data.message.message}" style="max-width: 100px; min-width: 50px;" /></p>
                                                        </div>
                                                    </div>
                                                 </div>`);
                    $('#message').val('');
                    $('#chat-cont').scrollTop($('#chat-cont')[0].scrollHeight);
                }
            });
        });

        // $('#chat-cont').on('scroll', function () {
        //     if($(this).scrollTop() < 10){
        //         ++window.chatPage;
        //         getMessagesInRoom($('#close-chat').attr('data-room'));
        //     }
        // });

        function getMessagesInRoom(room){
            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + window.token
                },
                url: '/api/chats/' + room,
                method: 'GET',
                data: {page: window.chatPage},
                success: function (data) {
                    for (let message in data) {

                        let mess = '';
                        if(data[message].type === 'text')
                            mess = data[message].message.message;
                        else if(data[message].type === 'image')
                            mess = `<img src="${data[message].message.message}" style="max-width: 100px; min-width: 50px;" />`;

                        $('#chat-cont').prepend(`<div class="col-sm-12 chat">
                                                    <div class="row">
                                                        <div class="col-sm-1"><img src="${data[message].sender.profile_picture}" style="width: 30px;" /></div>
                                                        <div class="col-sm-8">
                                                            <h5 style="font-weight: 600;">${data[message].sender.name}</h5>
                                                            <p>${mess}</p>
                                                        </div>
                                                    </div>
                                                 </div>`);
                    }
                    $('#room').show();
                    $('#chat-cont').scrollTop($('#chat-cont')[0].scrollHeight);
                    $('#close-chat').attr('data-room', room);
                    $('#send-message').attr('data-room', room);
                    $('#send-image').attr('data-room', room);
                    chat(room);
                    $.ajax({
                        headers: {
                            'Authorization': 'Bearer ' + window.token
                        },
                        url: '/api/chats/to/read',
                        method: 'POST',
                        data: {
                            room_id: room
                        }
                    })
                }
            });
        }
    });
</script>
</body>
</html>