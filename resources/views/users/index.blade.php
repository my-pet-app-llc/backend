@extends('layout.layout')

@section('mainContent')
    <div class="row users_section">
        <div class="col-sm-12">
            <table style="width: 100%" class="table table-bordered" id="users-table" data-url="{{ route('data_users') }}">
                <thead>
                    <tr>
                        <th class="table_head">{{ __('admin.users.username') }}</th>
                        <th class="table_head">{{ __('admin.users.user_e-mail') }}</th>
                        <th class="table_head">{{ __('admin.users.data_joined') }}</th>
                        <th class="table_head">{{ __('admin.users.age') }}</th>
                        <th class="table_head">{{ __('admin.users.location') }}</th>
                        <th class="table_head">{{ __('admin.users.status') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    @include('users.modal._delete_modal')
    @include('users.modal._un_ban_modal')
    @include('users.modal._img_modal')
@endsection