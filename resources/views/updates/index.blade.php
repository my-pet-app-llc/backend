@extends('layout.layout')

@section('mainContent')
    <div class="row">
        <div style="height: 100vh; border: solid grey 1px" class="col-sm-6">
            <table class="table table-bordered" id="updates-table" data-url="{{ route('data_updates') }}">
                <thead>
                    <tr>
                        <th class="table_head">Update Title</th>
                        <th class="table_head">Date Added</th>
                        <th class="table_head"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div style="height: 100vh; border: solid grey 1px;" class="col-sm-6">

        </div>
    </div>
@endsection