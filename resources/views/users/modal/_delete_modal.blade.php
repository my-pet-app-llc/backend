<div class="modal fade" id="userBan" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="pull-right mr-2" aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="text-center">{!! __('admin.messages.confirm_user_ban') !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.buttons.cancel') }}</button>
                <button id="banUser" type="button" class="btn btn-danger confirm_ban">{{ __('admin.buttons.confirm') }}</button>
            </div>
        </div>
    </div>
</div>