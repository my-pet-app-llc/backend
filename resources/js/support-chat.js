(function () {

    const axios = require('axios')
    const moment = require('moment')

    window.ticketChat = {

        echo: null,

        loadProcess: false,

        container: $('.t_flex-message-container'),

        aligns: {
            self: 'self-message',
            other: 'other-message'
        },

        paginate: 5,

        room: {},

        axiosInstance: axios.create({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }),

        links: {
            ticketInfo: '/tickets/',
            roomMessages: '/tickets/messages/',
            sendMessage: '/tickets/messages/send/'
        },

        template: '<div class="t_message-container #{speaker} #{alignClass}" data-message="#{messageId}">' +
            '<div class="t_message">' +
            '<p class="message-box">#{message}</p>' +
            '<p class="message-time">#{time}</p>' +
            '</div>' +
            '<div class="t_message-sender">' +
            '#{sender}' +
            '</div>' +
            '</div>',

        imageTemplate: '<img src="#{message}" alt="">',

        dateTemplate: '<div class="t_messages-date"><span>#{date}</span></div>',

        /**
         * Initialize room object
         */
        initRoomObject() {
            this.room = {
                resolved: false,
                ticketId: null,
                roomId: null,
                lastSender: null,
                firstSender: null,
                lastMessage: null,
                firstMessage: null,
                lastDate: null,
                firstDate: null,
                end: false,
                toScroll: true,
                channel: null
            }
        },

        /**
         * Load ticket
         *
         * @param ticketId
         */
        initTicket(ticketId) {
            let self = this

            this.axiosInstance.get(this.links.ticketInfo + ticketId)
                .then((response) => {
                    self.clearChat()

                    self.initRoomObject()
                    self.room.ticketId = ticketId
                    self.room.roomId = response.data.rooms.creator_room.id
                    self.initToolBar(response.data.status, response.data.rooms)
                    self.getMessages(response.data.rooms.creator_room.id)
                    self.openChatChannel()
                    self.listenNewMessage()
                })
                .catch((error) => {
                    console.log(error)
                })
        },

        /**
         * Clear all data for chat
         */
        clearChat() {
            $('.t_not-select').detach()
            $('.t_toolbar').hide()
            $('.t_flex-message-container').empty()

            if(this.room.channel)
                this.closeChatChannel()

            this.initRoomObject()
        },

        /**
         * Initial toolbar
         *
         * @param status
         * @param rooms
         * @returns {boolean}
         */
        initToolBar(status, rooms) {
            let statusOption = $(`#status > option[value="${status}"]`)

            if(!statusOption.length)
                throw new Error('Status not found')

            let statusKey = statusOption.attr('data-key')

            if(statusKey === 'resolved'){
                this.room.resolved = true
                $('.t_toolbar').hide()
                return true
            }

            $('#status > option').each(function () {
                let optionStatusKey = $(this).attr('data-key')
                if(optionStatusKey === statusKey){
                    $(this).prop('selected', true)
                    $(this).prop('hidden', false)
                    return true
                }

                if(statusKey === 'new'){
                    if(optionStatusKey === 'reported_user'){
                        $(this).prop('hidden', true)
                    }
                }else if(statusKey === 'reported_user'){
                    if(optionStatusKey === 'new' || optionStatusKey === 'in_progress'){
                        $(this).prop('hidden', true)
                    }
                }else if(statusKey === 'in_progress'){
                    if(optionStatusKey === 'new' || optionStatusKey === 'reported_user'){
                        $(this).prop('hidden', true)
                    }
                }else{
                    $(this).prop('hidden', false)
                }
            })

            let roomsSelect = $('#rooms')
            roomsSelect.empty()
            if(rooms['reported_room']){
                roomsSelect.append(`<option value="${rooms['creator_room']['id']}">${rooms['creator_room']['full_name']}</option>`)
                roomsSelect.append(`<option value="${rooms['reported_room']['id']}">${rooms['reported_room']['full_name']}</option>`)
                roomsSelect.show();
            }else{
                roomsSelect.hide();
            }

            $('.t_toolbar').show()
        },

        /**
         * Get messages in server side
         */
        getMessages() {
            if(this.loadProcess)
                return true

            if(this.room.end)
                return true

            this.loadProcess = true

            let self = this,
                roomId = this.room.roomId

            let dateElements = $('.t_messages-date')
            if(dateElements.length)
                $(dateElements[dateElements.length - 1]).detach()

            let postData = {
                paginate: this.paginate,
                last_message: this.room.lastMessage
            }

            this.axiosInstance.post(this.links.roomMessages + roomId, postData)
                .then((response) => {
                    let tMessages = $('.t_messages'),
                        tMessageScrollTop = tMessages[0].scrollHeight

                    self.loadProcess = false

                    if(!response.data){
                        self.room.end = true
                        return true
                    }

                    if(response.data.length < self.paginate)
                        self.room.end = true

                    for (let message in response.data) {
                        self.messageHandler(response.data[message])
                    }

                    self.setDate(this.room.lastDate)

                    if(self.room.toScroll){
                        tMessages.scrollTop(tMessages[0].scrollHeight);
                        self.room.toScroll = false
                    }else{
                        $('.t_messages').scrollTop(tMessages[0].scrollHeight - tMessageScrollTop)
                    }
                })
                .catch((error) => {
                    console.log(error)
                    self.loadProcess = false
                })
        },

        /**
         * Add message element to chat
         *
         * @param message
         */
        messageHandler(message) {
            let id = message.id,
                senderId = (message.sender) ? message.sender.id : 0,
                sender = (message.sender) ? message.sender : null,
                dateTimeMoment = moment(message.date_time_created),
                type = message.type

            let newDate = dateTimeMoment.format('YYYY-MM-DD')
            if(this.room.lastDate !== newDate && this.room.lastDate){
                this.setDate(this.room.lastDate)
            }

            if(!this.room.firstDate)
                this.room.firstDate = newDate

            if(this.room.firstSender === null)
                this.room.firstSender = senderId

            if(!this.room.firstMessage)
                this.room.firstMessage = id


            let speaker = !(this.room.lastSender === senderId && this.room.lastSender !== null)
            let messageTemplate = this.getMessageTemplate({
                id: id,
                type: type,
                message: message.message.message.replace(/\n/g, '<br />'),
                sender: sender,
                time: dateTimeMoment.format('HH:mm')
            }, speaker)

            this.container.append(messageTemplate)

            this.room.lastMessage = id
            this.room.lastSender = senderId
            this.room.lastDate = dateTimeMoment.format('YYYY-MM-DD')
        },

        newMessageHandler(message) {
            let id = message.id,
                senderId = (message.sender) ? message.sender.id : 0,
                sender = (message.sender) ? message.sender : null,
                dateTimeMoment = moment(message.date_time_created),
                type = message.type

            let newDate = dateTimeMoment.format('YYYY-MM-DD')
            if(this.room.firstDate !== newDate || !this.room.firstDate){
                this.room.firstDate = newDate
                this.setDate(newDate, true)
            }

            if(this.room.firstSender === senderId && this.room.firstSender !== null){
                let firstMessageEl = $(`.t_message-container[data-message="${this.room.firstMessage}"]`)
                firstMessageEl.removeClass('speaker')
                firstMessageEl.find('.t_message-sender').html('')
            }

            let messageTemplate = this.getMessageTemplate({
                id: id,
                type: type,
                message: message.message.message.replace(/\n/g, '<br />'),
                sender: sender,
                time: dateTimeMoment.format('HH:mm')
            })

            this.container.prepend(messageTemplate)

            this.room.firstMessage = id
            this.room.firstSender = senderId
            this.room.firstDate = dateTimeMoment.format('YYYY-MM-DD')
        },

        /**
         * Add date of messages element to chat
         *
         * @param date
         * @param revers
         */
        setDate(date, revers = false) {
            let dateTemplate = this.renderTemplate(this.dateTemplate, {
                date: moment(date).format('D MMM YYYY')
            })

            if(revers)
                this.container.prepend(dateTemplate)
            else
                this.container.append(dateTemplate)
        },

        /**
         * Generate template for message
         *
         * @param data
         * @param speaker
         * @returns {*}
         */
        getMessageTemplate(data, speaker = true) {

            let id = data.id,
                type = data.type,
                message = data.message,
                senderName = (!data.sender) ? 'MyPet Admin' : data.sender.first_name + ' ' + data.sender.last_name,
                time = data.time,
                align = (!data.sender) ? this.aligns.self : this.aligns.other

            if(type === 'image'){
                message = this.renderTemplate(this.imageTemplate, {
                    message: message
                })
            }

            return this.renderTemplate(this.template, {
                alignClass: align,
                messageId: id,
                message: message,
                time: time,
                sender: speaker ? senderName : '',
                speaker: speaker ? 'speaker' : ''
            })

        },

        /**
         * Generate template with variables
         *
         * @param template
         * @param data
         * @returns {*}
         */
        renderTemplate(template, data) {
            let replacedTemplate = template

            for (let key in data) {
                let regexp = new RegExp(`#{${key}}`, 'g')
                replacedTemplate = replacedTemplate.replace(regexp, data[key])
            }

            return replacedTemplate
        },

        /**
         * Open ticket chat channel
         *
         * @returns {Window.ticketChat}
         */
        openChatChannel() {
            this.room.channel = window.Echo.private(`ticket.chat.${this.room.roomId}`)

            return this
        },

        /**
         * Close ticket chat channel
         *
         * @returns {Window.ticketChat}
         */
        closeChatChannel() {
            let channelName = `private-ticket.chat.${this.room.roomId}`
            window.Echo.leaveChannel(channelName)

            return this
        },

        listenNewMessage() {
            let self = this

            this.room.channel.listen('.ticket.chat.message', function (data) {

                self.newMessageHandler(data)

            })

            return this
        },

        listenTyping() {
            this.room.channel.listenForWhisper('typing', function (data) {

                console.log(data)

            })

            return this
        },

        makeTypingEvent() {
            this.room.channel.whisper('typing', {
                name: 'MyPet Admin'
            })

            return this
        },

        sendMessage() {

            let messageText = $('#message').val(),
                self = this

            if(messageText === '')
                return true

            this.axiosInstance.post(this.links.sendMessage + this.room.roomId, {
                    message: messageText
                }, {
                    headers: {
                        'X-Socket-ID': window.Echo.socketId()
                    }
                })
                .then((response) => {

                    let messageObject = response.data
                    self.newMessageHandler(messageObject)
                    $('#message').val('')
                    $('.t_messages').scrollTop($('.t_messages')[0].scrollHeight)

                })
                .catch((error) => {
                    console.log(error)
                })

        }

    }

    /**
     * Load ticket chats
     */
    $(document).on('click', 'tr[data-ticket]', function () {

        let ticketId = $(this).attr('data-ticket');

        window.ticketChat.initTicket(ticketId)

    })

    $('.t_messages').on('scroll', function () {

        if($(this).scrollTop() < 20)
            window.ticketChat.getMessages()

    })

    $('#message').on('keydown', function (e) {
        if(e.keyCode === 13 && !e.shiftKey) {
            $('.send-button').click()
            return false
        }
    })

    $('.send-button').on('click', function () {
        ticketChat.sendMessage()
    })

})()