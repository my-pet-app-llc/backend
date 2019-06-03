@extends('layout.layout')

@section('mainContent')
    <div class="row tickets_section">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
            <table style="width: 100%" class="table table-bordered" id="tickets-table" data-url="{{ route('data_tickets') }}">
                <thead>
                    <tr>
                        <th class="table_head">{{ __('admin.users.username') }}</th>
                        <th class="table_head">{{ __('admin.users.user_e-mail') }}</th>
                        <th class="table_head">{{ __('admin.tickets.date') }}</th>
                        <th class="table_head">{{ __('admin.tickets.time') }}</th>
                        <th class="table_head">{{ __('admin.tickets.ticket') }}</th>
                        <th class="table_head">{{ __('admin.tickets.status') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        @include('tickets.chat.chat')
    </div>

    <div class="photo_preview">
        <div class="fs_fade"></div>
        <div>
            <img src="" alt="" id="viewImage">
            <button id="closeViewImage"><i class="ti-close"></i></button>
        </div>
    </div>
@endsection