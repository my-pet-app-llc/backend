@extends('mail.layout')

@section('content')
    <p style="font-size: 17px; margin-bottom: 25px; font-weight: 700;">Hello {{ $user->owner->fullName }},</p>
    <p>This is the MyPet App team notifying you that you have been reported as being in violation of the MyPet App Terms and Services Agreement.</p>
    <p>You have been reported for the following reason:</p>
    <blockquote style="margin-block-start: 20px; margin-block-end: 30px;">
        <p><em>{{ $ticket->report_reason }}</em></p>
    </blockquote>
    <p>Your account has been suspended for 72 hours pending investigation.</p>
    <p>If you wish to dispute this claim, or require any further explanation please contact the MyPet App support team by replying to this email.</p>
@stop