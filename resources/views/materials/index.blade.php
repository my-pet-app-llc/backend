@extends('layout.layout')

@section('mainContent')
    <div class="row updates_section">
        <div class="col-sm-6">
            <table class="table table-bordered" id="materials-table" data-url="{{ route('data_materials') }}">
                <thead>
                    <tr>
                        <th class="table_head">{{ __('admin.materials.material_title') }}</th>
                        <th class="table_head">{{ __('admin.materials.date_added') }}</th>
                        <th class="table_head"></th>
                    </tr>
                </thead>
            </table>
        </div>
        
        @if(Request::session()->get('flash_message'))
            <span class="flesh_message" data-message="{{ Request::session()->get('flash_message') }}"></span>
        @endif

        <div class="col-sm-6 form_box">
            <h2><strong>{{ __('admin.materials.add_new_promotion') }}</strong></h2>
            <form method="POST" action="{{ route('materials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="input-b2">{{__('admin.materials.promotion_image') }}</label>
                    <div class="{{ $errors->has('image') ? 'error_image' : '' }}">
                        <input id="input-b2" name="image" type="file" class="file" 
                        data-show-preview="false">
                    </div>
                    @if ($errors->has('image'))
                        <strong class="error_message_image">{{ $errors->first('image') }}</strong>
                    @endif
                <div class="form-group mt-3">
                    <label for="materialTitle">{{__('admin.materials.material_title') }}</label>
                    <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" 
                            name="title" id="materialTitle" placeholder="Enter Promotion Title..." value="{{ old('title') }}">
                    @if ($errors->has('title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="materialShortText">{{__('admin.materials.promotion_short_text') }}</label>
                    <input type="text" class="form-control {{ $errors->has('short_text') ? ' is-invalid' : '' }}" 
                            name="short_text" id="materialShortText" placeholder="Enter Promotion Text..." value="{{ old('short_text') }}">
                    @if ($errors->has('short_text'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('short_text') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="materialFullText">{{__('admin.materials.promotion_full_text') }}</label>
                    <input type="text" class="form-control {{ $errors->has('full_text') ? ' is-invalid' : '' }}" 
                            name="full_text" id="materialFullText" placeholder="Enter Promotion Text..." value="{{ old('full_text') }}">
                    @if ($errors->has('full_text'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('full_text') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="materialAddress">{{__('admin.materials.promotion_address') }}</label>
                    <input type="text" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" 
                            name="address" id="materialAddress" placeholder="Enter Promotion Street Address..." value="{{ old('address') }}">
                    @if ($errors->has('address'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="materialNumber">{{__('admin.materials.promotion_phone') }}</label>
                    <input type="text" class="form-control {{ $errors->has('phone_number') ? ' is-invalid' : '' }}" 
                            name="phone_number" id="materialNumber" placeholder="Enter Promotion Phone Number..." value="{{ old('phone_number') }}" data-mask="00/00/0000" data-mask-reverse="true">
                    @if ($errors->has('phone_number'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="materialWebsite">{{__('admin.materials.promotion_website') }}</label>
                    <input type="text" class="form-control {{ $errors->has('website') ? ' is-invalid' : '' }}" 
                            name="website" id="materialWebsite" placeholder="Enter Promotion Website Address..." value="{{ old('website') }}">
                    @if ($errors->has('website'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('website') }}</strong>
                        </span>
                    @endif
                </div>
                <button class="btn btn-success pull-right">{{__('admin.buttons.add_material') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('modals')
    @include('materials.modal._delete_modal')
@endsection