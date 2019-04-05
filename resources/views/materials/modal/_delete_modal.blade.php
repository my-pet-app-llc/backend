<div class="modal fade" id="deleteMaterial" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="pull-right mr-2" aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="text-center">{!! __('admin.messages.material_warning') !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.buttons.cancel') }}</button>
                <button id="removeMaterial" data-flash-message="{{ __('admin.messages.material_remove') }}" 
                        type="button" class="btn btn-danger remove_material">{{ __('admin.buttons.delete') }}</button>
            </div>
        </div>
    </div>
</div>