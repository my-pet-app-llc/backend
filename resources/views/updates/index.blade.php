@extends('layout.layout')

@section('mainContent')
    <div class="row updates_section">
        <div class="col-sm-6">
            <table class="table table-bordered" id="updates-table" data-url="{{ route('data_updates') }}">
                <thead>
                    <tr>
                        <th class="table_head">{{ __('admin.updates.update_title') }}</th>
                        <th class="table_head">{{ __('admin.updates.date_added') }}</th>
                        <th class="table_head"></th>
                    </tr>
                </thead>
            </table>
        </div>
        
        @if(Request::session()->get('flash_message'))
            <span class="flesh_message" data-message="{{ Request::session()->get('flash_message') }}"></span>
        @endif

        <div class="col-sm-6 form_box">
            <h2><strong>{{ __('admin.updates.add_new_update') }}</strong></h2>
            <form method="POST" action="{{ route('updates.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="input-b2">{{__('admin.updates.update_image') }}</label>
                    <div class="{{ $errors->has('image') ? 'error_image' : '' }}">
                        <input id="input-b2" name="image" type="file" class="file" 
                        data-show-preview="false">
                    </div>
                    @if ($errors->has('image'))
                        <strong class="error_message_image">{{ $errors->first('image') }}</strong>
                    @endif
                <div class="form-group mt-3">
                    <label for="updateTitle">{{__('admin.updates.update_title') }}</label>
                    <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" 
                            name="title" id="updateTitle" placeholder="Enter Update Title..." value="{{ old('title') }}">
                    @if ($errors->has('title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="updateDescription">{{__('admin.updates.update_text') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" 
                        id="updateDescription" rows="3" placeholder="Enter Update Description...">{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
                <button class="btn btn-success pull-right">{{__('admin.buttons.add_update') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('modals')
    @include('updates.modal._delete_modal')
@endsection