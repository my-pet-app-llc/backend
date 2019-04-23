(function () {

    const axios = require('axios'),
          getChatsUrl = '/tickets/'

    $(document).on('click', 'tr[data-ticket]', function () {

        let ticketId = $(this).attr('data-ticket');

        axios.get(getChatsUrl + ticketId)
            .then((response) => {
                console.log(response)
            })
            .catch((error) => {
                console.log(error)
            })

    })

})()