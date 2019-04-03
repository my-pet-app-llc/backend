<div class="modal fade" id="deleteUpdate" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="pull-right mr-2" aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="text-center">{!! __('admin.messages.warning_update') !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.buttons.cancel') }}</button>
            <button id="removeUpdate" data-flash-message="{{ __('admin.messages.remove_update') }}" type="button" class="btn btn-danger remove_update">{{ __('admin.buttons.delete') }}</button>
            </div>
        </div>
    </div>
</div>