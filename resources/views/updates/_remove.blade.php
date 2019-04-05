<div>
    <form method="DELETE" action="{{ route('updates.destroy', $update) }}">
        @csrf
        <button type="button" class="btn btn_remove delete_update">{{ __('admin.buttons.remove') }}</button>
    </form>
</div>