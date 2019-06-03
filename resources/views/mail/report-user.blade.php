@extends('mail.layout')

@section('content')
    <p style="font-size: 17px; margin-bottom: 25px; font-weight: 700;">Hello {{ $user->owner->fullName }},</p>
    <p>We have received the report to your MyPet account due to following reason:</p>
    <blockquote style="margin-block-start: 20px; margin-block-end: 30px;">
        <p><em>{{ $ticket->report_reason }}</em></p>
    </blockquote>
    <p>For further enquires, please, contact our support team.</p>
@stop