import Echo from "laravel-echo";
window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    auth: {headers: {Authorization: "Bearer " + window.token}}
})

let events = window.Echo.private('new_event.' + window.user)
events.listen('.new.event', (e) => {
    console.log(e)
    if(e.type === 'chat_message'){
        if(Number(window.$('#close-chat').attr('data-room')) !== e.data.room_id){
            window.$('#not-read').show();
            window.$(`.chat[data-room="${e.data.room_id}"]`).find('.is-read').html('Not read');
            if(e.data.message.message.type === 'text')
                window.$(`.chat[data-room="${e.data.room_id}"]`).find('.last-message').html(e.data.message.message.message);
            else if(e.data.message.message.type === 'text')
                window.$(`.chat[data-room="${e.data.room_id}"]`).find('.last-message').html('Image');
        }
    }

    if(e.type === 'chat_delete'){
        window.location.reload();
    }
})

window.chat = (room) => {
    let chat = window.Echo.private('chat.' + room)

    chat.listen('.chat.event', (e) => {
        console.log(e);

        let template = '';
        if(e.type === 'text'){
            template = `<div class="col-sm-12 chat">
                            <div class="row">
                                <div class="col-sm-1"><img src="${e.sender.profile_picture}" style="width: 30px;" /></div>
                                <div class="col-sm-8">
                                    <h5 style="font-weight: 600;">${e.sender.name}</h5>
                                    <p>${e.message.message}</p>
                                </div>
                            </div>
                         </div>`
        }else if(e.type === 'image'){
            template = `<div class="col-sm-12 chat">
                            <div class="row">
                                <div class="col-sm-1"><img src="${e.sender.profile_picture}" style="width: 30px;" /></div>
                                <div class="col-sm-8">
                                    <h5 style="font-weight: 600;">${e.sender.name}</h5>
                                    <p><img src="${e.message.message}" style="max-width: 100px; min-width: 50px;" /></p>
                                </div>
                            </div>
                         </div>`
        }

        window.$('#chat-cont').append(template);

        $('#chat-cont').scrollTop($('#chat-cont')[0].scrollHeight);

        window.$.ajax({
            headers: {
                'Authorization': 'Bearer ' + window.token
            },
            url: '/api/chats/to/read',
            method: 'POST',
            data: {
                room_id: room
            }
        })
    })
}

window.closeChat = (room) => {
    window.Echo.leaveChannel('private-chat.' + room)
    window.chatPage = 1;
}