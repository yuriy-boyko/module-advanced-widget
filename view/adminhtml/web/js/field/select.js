define(['jquery'], function($) {
    return function(htmlId, id, renderer, parameterName, data, index, isModal = false, modal = null) {
        let self = renderer;

        self._updateSelect = function (event) {
            let select = $(event.target);
            let name = $(select).attr("name");
            $('[name="' + name + '"]').val(select.val());
        };

        $(window).on('mageos_row_modal_edit_'  + htmlId, function(event, data) {
            $(data.modal).find('[data-type="select"]').each(function() {
                let name = $(this).attr("name");
                if (typeof data[name] !== "undefined" && data[name] !== "") {
                    $(this).val(data[name]);
                }
            });
        });

        self.selectFields.forEach(function (name) {
            if (typeof data[name] === "undefined") {
                data[name] = "";
            }
        });

        self.selectFields.forEach(function (name) {
            let selector = "#select_" + htmlId + "_" + name + "_" + data.index;
            if (isModal) {
                selector = "#edit_select_" + htmlId + "_" + name + "_" + data.index;
            }
            let select = $(modal).find(selector);
            if (!select) {
                return false;
            }

            let value = (typeof data[name] != 'undefined') ? data[name] : false;
            select.children().each(function () {
                if (value == $(this).val()) {
                    $(this).attr('selected', 'selected');
                }
            });

            select.on('change', self._updateSelect.bind(self));
        });
    }
});
