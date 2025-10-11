define(['jquery'], function($) {
    return function (htmlId, id, renderer, parameterName,  data, index, isModal = false, modal = null) {
        let self = renderer;
        let _uploadImageUrl = self.uploadImageUrl;

        self._updateImage = function (event) {
            let input = $(event.target),
                newImagePath = input.val(),
                newImageUrl = newImagePath;

            if (newImagePath.indexOf(this.mediaUrl) === -1) {
                let value = newImagePath,
                    directive_re = /___directive/;

                if (directive_re.test(value)) {
                    let split_value = value.split("/___directive/");
                    split_value = split_value[1].split("/key/");
                    split_value = split_value[0].split(",");
                    split_value = split_value[0].replace("/", "");

                    let media_url_insert = atob(split_value),
                        attributes = media_url_insert
                            .replace("{{", "")
                            .replace("}}", "")
                            .split(" ")
                    ;
                    for (let i = 0; i < attributes.length; i++) {
                        let split_attribute = attributes[i]
                            .replace("\"", "")
                            .replace(new RegExp("\\\"" + '$'), '')
                            .split("=")
                        ;
                        if (split_attribute[0] === 'url') {
                            newImagePath = split_attribute[1];
                            break;
                        }
                    }
                }
            }
            if (newImagePath.indexOf(this.mediaUrl) === -1 && newImagePath.indexOf('/media/') === -1) {
                newImageUrl = self.mediaUrl + newImagePath;
            }

            if (newImagePath.length) {
                let previewDiv = input.nextAll('.file-uploader-preview');
                previewDiv.find('a').first().attr('href', newImageUrl);
                previewDiv.find('img').first().attr('src', newImageUrl);
                input.val(newImagePath);
                previewDiv.show();
            }
        };

        self._deleteImage = function (event) {
            let button = $(event.target),
                previewDiv = button.parents('.file-uploader-preview'),
                input = previewDiv.prev();
            previewDiv.find('a').first().attr('href', '');
            previewDiv.find('img').first().attr('src', '');
            previewDiv.prev().val('');
            previewDiv.hide();
        };

        $(window).on('mageos_row_modal_update_customfields_' + htmlId, function(event, data) {

            let parameterName = data.detail.parameterName;
            let eventIndex = data.detail.index;
            if (!_.isUndefined(parameterName) && String(eventIndex) === String(index)) {
                for (const [key, value] of Object.entries(data.detail)) {
                    if (self.imageFields.includes(key)) {
                        let selector = '[name="parameters[' + parameterName + '][' + index + '][' + key + ']"]';
                        let imageInput = $(selector);
                        if (imageInput.length) {
                            $(selector).val(value);
                            let previewDiv = $(selector).nextAll('.file-uploader-preview');
                            previewDiv.find('a').first().attr('href', value);
                            previewDiv.find('img').first().attr('src', value);
                            if (value !== "") {
                                previewDiv.show();
                            } else {
                                previewDiv.hide();
                            }
                        }
                    }
                }
            }
        });

        $(window).on('mageos_row_modal_edit_'  + htmlId, function(event, data) {
            $(data.modal).find('[data-type="image"]').each(function() {
                let previewDiv = $(this).nextAll('.file-uploader-preview'),
                    newImageUrl = $(this).val();
                if (newImageUrl !== "") {
                    previewDiv.find('a').first().attr('href', newImageUrl);
                    previewDiv.find('img').first().attr('src', newImageUrl);
                    previewDiv.show();
                }
            });
        });

        self.imageFields.forEach(function (name) {
            let selector = "#image_" + htmlId + "_" + name + "_" + data.index,
                replaceValue = 'image_' + htmlId + '_' + name + '_' + data.index;
            if (isModal) {
                selector = "#edit_image_" + htmlId + "_" + name + "_" + data.index;
                replaceValue = 'edit_image_' + htmlId + '_' + name + '_' + data.index;
            }
            let input = $(selector),
                button = input.prev();
            let uploadImageUrl = "";
            uploadImageUrl = _uploadImageUrl.replace('__target_element_id__', replaceValue);
            if (!button) {
                return false;
            }
            if (!button.hasClass("click-handler")) {
                button.addClass("click-handler");
                button.on('click', function () {
                    MediabrowserUtility.openDialog(uploadImageUrl)
                });
            }
            input.on('change', self._updateImage.bind(self));
            input.nextAll('.file-uploader-preview').find('button.action-remove').first().click(self._deleteImage.bind(self));
            input.trigger('change');
        });
    };
});
