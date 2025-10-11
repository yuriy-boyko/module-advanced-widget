define(['jquery', 'underscore'], function($, _) {
    return function(htmlId, id, renderer, parameterName, data, index, isModal = false, modal = null) {
        let self = renderer;

        self._updateProduct = function (event) {
            let input = $(event.target),
                previewDiv = input.nextAll('.file-uploader-preview');

            if ($(previewDiv).find('.preview-sku').first().html() !== "") {
                $(previewDiv).show();
            }
        };

        self._deleteProduct = function (event) {
            let linkedField = $(event.target).attr('data-linked-input');
            let input = $('[name="'+linkedField+'"]');
            input.val("");
            input.attr("value", "");
            input.trigger("change");
            let previewDiv = input.nextAll('.file-uploader-preview');
            previewDiv.find(".preview-sku").text("");
            previewDiv.find(".preview-name").text("");
            previewDiv.find(".preview-image").attr("src", "");
            previewDiv.hide();
        };


        $(window).off('mageos_product_rows_selection' + id).on('mageos_product_rows_selection' + id, function(event) {
            let input = window.mageosEditProductModalInput;
            let inputName = input.attr('name');
            let productInput = $('[name="'+inputName+'"]');
            let previewDiv = productInput.nextAll('.file-uploader-preview');
            productInput.val(event.detail.id);
            previewDiv.find('.preview-name').text(event.detail.name);
            previewDiv.find('.preview-sku').text("sku: " + event.detail.sku);
            previewDiv.find('.preview-image').attr('src', event.detail.image);
            previewDiv.show();
            window.productSelectionModal.modal('closeModal');
        });

        $(window).off('mageos_row_modal_edit_'  + htmlId).on('mageos_row_modal_edit_'  + htmlId, function(event, data) {

            let mainForm = $("#repeatable_widget_"+htmlId);

            $(data.modal).find('[data-type="product"]').each(function() {
                let inputName = $(this).attr("name");
                let productInput = mainForm.find('[name="'+inputName+'"]');
                let previewDiv = productInput.nextAll('.file-uploader-preview');

                let name = previewDiv.find('.preview-name').text();
                let sku = previewDiv.find('.preview-sku').text();
                let image = previewDiv.find('.preview-image').attr('src');

                let modalPreviewDiv = $(this).nextAll('.file-uploader-preview'),
                    product = $(this).val();
                if (product !== "") {
                    modalPreviewDiv.find('.preview-name').text(name);
                    modalPreviewDiv.find('.preview-sku').text(sku);
                    modalPreviewDiv.find('.preview-image').attr('src',image);
                    modalPreviewDiv.show();
                    modalPreviewDiv.find('button.action-remove').first().click(self._deleteProduct.bind(self));
                }
            });
        });

        if (!_.isEmpty(self.productFields)) {
            $('tr[data-id="' + data["index"] + '"]').find('.product-input').each(function() {
                if ($(this).val() !== "") {
                    $(this).nextAll('.file-uploader-preview').show();
                }
            });
        }

        self.productFields.forEach(function (name) {
            let selector = "#" + name + "_" + htmlId + "_" + name + "_" + data.index;
            if (isModal) {
                selector = "#edit_" + name + "_" + htmlId + "_" + name + "_" + data.index;
            }
            let input = $(selector),
                button = input.prev();
            window.mageosEditRowModalIndex = index;
            if (!button) {
                return false;
            }
            button.off('click').on('click', function () {
                let options = {
                    type: 'slide',
                    responsive: true,
                    innerScroll: true,
                    title: 'Select Product',
                    buttons: []
                };
                let modal = $("#mageos_modal_product_selection");
                modal.modal(options);
                window.mageosEditProductModalInput = input;
                window.productSelectionModal = modal;
                modal.modal('openModal');
            });
            input.on('change', self._updateProduct.bind(self));
            input.nextAll('.file-uploader-preview').find('button.action-remove').first().click(
                self._deleteProduct.bind(self)
            );
        });

    }
});
