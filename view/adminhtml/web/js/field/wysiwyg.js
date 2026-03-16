define(['jquery',
    'tinymce',
    'mage/translate',
    'mage/adminhtml/events',
    'mage/adminhtml/wysiwyg/tiny_mce/setup',
    'mage/adminhtml/wysiwyg/widget'], function($,tinyMCE) {
    return function(htmlId, id, renderer, parameterName, data, index, isModal = false, modal = null) {
        let self = renderer;

        $(window).on('mageos_row_modal_edit_'  + htmlId, function(event, data) {
            $(data.modal).find('[data-type="wysiwyg"]').each(function() {
                let name = $(this).attr("name");
                if (typeof data[name] !== "undefined" && data[name] !== "") {
                    $(this).val(data[name]);
                }
            });
        });

        $(window).on('mageos_row_modal_update_customfields_' + htmlId, function(event, data) {

            let parameterName = data.detail.parameterName;
            let eventIndex = data.detail.index;
            if (!_.isUndefined(parameterName) && String(eventIndex) === String(index)) {
                for (const [key, value] of Object.entries(data.detail)) {
                    if (self.wysiwygFields.includes(key)) {
                        let selector = '[name="parameters[' + parameterName + '][' + index + '][' + key + ']"]';
                        let wysiwygInput = $(selector);
                        if (wysiwygInput.length) {
                            let editor = tinyMCE.get(wysiwygInput.attr('id'));
                            if(editor) {
                                editor.setContent(value);
                            }
                            $(selector).val(value);
                        }
                    }
                }
            }
        });

        self.wysiwygFields.forEach(function (name) {
            let selector = "#wysiwyg_" + htmlId + "_" + name + "_" + data.index,
                replaceValue = 'wysiwyg_' + htmlId + '_' + name + '_' + data.index;
            let wysiwygToggle = '#toggle_'+replaceValue;
            if (isModal) {
                selector = "#edit_wysiwyg_" + htmlId + "_" + name + "_" + data.index;
                replaceValue = 'edit_wysiwyg_' + htmlId + '_' + name + '_' + data.index;
                wysiwygToggle = '#modal_toggle_wysiwyg_'+ htmlId + '_' + name + '_' + data.index;
            }

            if($(selector).length) {
                let editorConfig = (window.mageos && window.mageos.wyiwyg_config) ? window.mageos.wyiwyg_config : {};

                let editor = new wysiwygSetup(replaceValue, editorConfig);

                if(!isModal && editorConfig.turn_on === true) {
                    editor.wysiwygInstance.turnOn();
                }
                if(isModal && editorConfig.turn_on_modal === true) {
                    editor.wysiwygInstance.turnOn();
                }

                $(wysiwygToggle).off('click').on('click', function() {
                    editor.toggle();
                });
            }
        });
    }
});
