<div>
    <form method="DELETE" action="{{ route('materials.destroy', $material) }}">
        @csrf
        <button type="button" class="btn btn_remove delete_material">{{ __('admin.buttons.remove') }}</button>
    </form>
</div>