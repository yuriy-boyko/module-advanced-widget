define(['jquery','jscolor'], function($) {
    return function(htmlId, id, renderer, parameterName, data, index, isModal = false, modal = null) {
        $(window).on('mageos_row_modal_edit_'  + htmlId, function(event, data) {
            $(data.modal).find('[data-type="colorpicker"]').each(function() {
                let value = $(this).attr('value');
                new JSColor(this, {
                    value: value ? value : '#FFFFFF',
                    format:'rgba',
                    required: false
                });
            });
        });
    }
});
