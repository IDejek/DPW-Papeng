/**
 * Admin Meta Box JS — Media Upload Buttons
 * @package DPW_PSIPapeng
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Media upload buttons
        $(document).on('click', '.dpw-media-upload', function (e) {
            e.preventDefault();
            var target = $(this).data('target');
            var frame = wp.media({
                title: 'Pilih Media',
                button: { text: 'Pilih' },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                $(target).val(attachment.url);
            });
            frame.open();
        });
    });
})(jQuery);
