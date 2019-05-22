<div id="infoUser" class="row user_info">
    <div class="col-sm-10">
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.username') }}: </span>{{ $user->owner->fullName }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.user_e-mail') }}: </span>{{ $user->email }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.data_joined') }}: </span>{{ $user->owner->created_at->format('d/m/Y') }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.age') }}: </span>{{ $user->owner->age }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.location') }}: </span>{{ $user->owner->pet->city. ' ' .$user->owner->pet->state }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.status') }}: </span>{{ $user->owner->statusName }}</p>
    </div>
    <div class="col-sm-2 d-flex align-items-center">
        <form action="mailto:{{ $user->email }}" method="GET">
            <button class="btn btn-primary mr-2">{{ __('admin.buttons.email') }}</button> 
        </form>
        <button id="ban" data-flash-message="{{ __('admin.messages.user_ban') }}" class="btn btn-danger btn_ban element_none" data-ban-url="{{ route('users.ban', $user) }}">{{ __('admin.buttons.ban') }}</button>
        <button id="unBan" data-flash-message="{{ __('admin.messages.user_un_ban') }}" class="btn btn-success btn_ban element_none" data-ban-url="{{ route('users.ban', $user) }}">{{ __('admin.buttons.un_ban') }}</button>
    </div>
</div>
@php
    $pictures = $user->owner->pet->pictures;
@endphp
@if ($pictures->isNotEmpty())
<div class="user_info">
    <div>
        <p class="mb-0"><span class="text-dark ml-3">{{ __('admin.users.uploaded_images') }}: </span></p>
    </div>
    <div class="row info_img">
        @foreach ($pictures as $picture)
            <div class="col-sm-2 mb-2 img_box d-flex align-items-center" 
            style="background-image: url({{ $picture->getImgUrl() }})" data-patch="{{ $picture->getImgUrl() }}">
            </div>
        @endforeach
    </div>
    
</div>
@endIf
