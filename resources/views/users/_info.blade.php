<div class="row user_info">
    <div class="col-sm-10">
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.username') }}: </span>{{ $user->owner->fullName }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.user_e-mail') }}: </span>{{ $user->email }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.data_joined') }}: </span>{{ $user->owner->created_at->format('d/m/Y') }}</p>
        <p style="margin-bottom: 0"><span class="text-dark">{{ __('admin.users.age') }}: </span>{{ $user->owner->age }}</p>
    </div>
    <div class="col-sm-2 d-flex align-items-center">
        <form action="mailto:{{ $user->email }}" method="GET">
            <button class="btn btn-primary mr-2">{{ __('admin.buttons.email') }}</button> 
        </form>
        <button class="btn btn-danger btn_ban" data-ban-url="{{ route('users.ban', $user) }}">{{ __('admin.buttons.ban') }}</button>
    </div>
</div>
@php
    $pictures = $user->owner->pet->pictures;
@endphp
@if ($pictures->isNotEmpty())
<div class="row info_img user_info">
    @foreach ($pictures as $picture)
        <div class="col-sm-2 mb-2 img_box">
            <img class="img_pets" src="{{ $picture->getImgUrl() }}" alt="not picture">
        </div>
    @endforeach
</div>
@endIf
