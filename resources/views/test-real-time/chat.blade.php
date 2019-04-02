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
<div class="row">
    <div class="col-md-8 col-md-offset-2">

    </div>
</div>

<div class="row" style="margin-top: 40px;">
    <div class="col-md-12">
        <button class="btn btn-primary">New Message</button>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>

<script>
    $(document).ready(function () {
        const token = '{{ $token }}';

        window.room = '{{ $room }}';
        window.user = '{{ $user }}';

        $.ajax({
            headers: {
                'Authorization': 'Bearer ' + token
            },
            url: '/api/chats/' + window.room,
            method: 'GET',
            success: function (data) {
                console.log(data);
            }
        })
    });
</script>
</body>
</html>