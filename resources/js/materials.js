import {showMessage} from './helper.js'

$(function() {
    const message = $('.flesh_message').data('message');
    const columns = [
        { data: 'title', name: 'title' },
        { data: 'created_at', name: 'created_at' },
        { data: 'remove_btn', searchable: false, orderable: false, },
    ];
    const table = $('#materials-table');
    const url = table.data('url');

    datatable();

    function datatable() {
        table.DataTable({
            serverSide: true,
            scrollX: true,
            ajax: url,
            columns: columns
        });
    }

    $(document).on('click', '.delete_material', function (e) {
        $('#deleteMaterial').modal();
        const form = $(e.target).closest('form');
        const url = form.attr('action');
        const token = form.find('[name="_token"]').val();

        $('#removeMaterial').attr('data-route', url);
        $('#removeMaterial').attr('data-token', token);
    });

    $('.remove_material').on('click', function (e) {
        e.preventDefault();
        const url = $(e.target).attr('data-route');
        const token = $(e.target).attr('data-token');
        const flashMessage = $(e.target).data('flash-message')
        $.ajax({
            type: 'DELETE',
            url:  url,
            data: {'_token': token},
            success: function($data) {
                $('#deleteMaterial').modal('hide');
                showMessage(flashMessage);  
                table.DataTable().clear().draw();    
            },
            error: function($error) {
            }
        }); 
    });

    $(document).ready(function () {
        if (typeof(message) != "undefined") {
            showMessage(message); 
        }
    });

    $('#materialNumber').mask('(000) 000-0000');

});

const input = document.getElementById('materialAddress');

if (input) {
    const options = {
        componentRestrictions: {
            country: ['us']
        }
    };
    const autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addDomListener(window, 'load', autocomplete);
    
    google.maps.event.addDomListener(autocomplete, 'place_changed', function() {
        const place = autocomplete.getPlace();
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        $('#sendMaterials').find('[name="lat"]').val(lat);
        $('#sendMaterials').find('[name="lng"]').val(lng);

        const state = $('#sendMaterials').find('[name="state"]')
        state.val('')

        for (let addressComponent in place.address_components) {
            for (let type in place.address_components[addressComponent].types) {
                if(place.address_components[addressComponent].types[type] === "administrative_area_level_1")
                    state.val(place.address_components[addressComponent].short_name)
            }
        }
    });
}
    