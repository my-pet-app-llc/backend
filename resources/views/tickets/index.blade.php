@extends('layout.layout')

@section('mainContent')
    <div class="row tickets_section">
        <div class="col-sm-9">
            <table class="table table-bordered" id="tickets-table" data-url="{{ route('data_tickets') }}">
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
@endsection