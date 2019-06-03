{{--<div class="modal fade" id="userSuspend" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                <span class="pull-right mr-2" aria-hidden="true">&times;</span>--}}
{{--            </button>--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="text-center">{!! __('admin.messages.confirm_user_suspend') !!}</h4>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.buttons.cancel') }}</button>--}}
{{--                <button id="suspendUser" type="button" class="btn btn-danger confirm_suspend">{{ __('admin.buttons.confirm') }}</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="modal fade" id="userSuspend" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="pull-right mr-2" aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header modal-user-reports">
                <h4>Select Ticket</h4>
                <div class="reports-modal__tickets">
                    <div class="reports-modal-ticket__container" id="example-ticket">
                        <div class="reports-modal-ticket__radio-container">
                            <input type="radio" name="" value="">
                        </div>
                        <div class="reports-modal-ticket__desc__container">
                            <p class="reports-modal-ticket__no"></p>
                            <p class="reports-modal-ticket__reason"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.buttons.cancel') }}</button>
                <button id="suspendUser" type="button" class="btn btn-danger confirm_suspend">{{ __('admin.buttons.confirm') }}</button>
            </div>
        </div>
    </div>
</div>