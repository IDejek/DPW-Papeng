/**
 * DPW PSI - Admin JavaScript
 * Handles media uploader for meta boxes
 */
(function($) {
    'use strict';

    // DPD Photo Uploader
    $(document).on('click', '#dpd_photo_upload', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Pilih Foto Ketua DPD',
            button: { text: 'Gunakan Foto Ini' },
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#psi_dpd_photo').val(attachment.url);
            // Update preview image or add new one
            var preview = $('#dpd_photo_upload').prev('img');
            if (preview.length) {
                preview.attr('src', attachment.url);
            } else {
                $('#dpd_photo_upload').parent().prepend('<img src="' + attachment.url + '" style="width:80px;height:80px;object-fit:cover;border-radius:50;" />');
            }
        });
        frame.open();
    });

    $(document).on('click', '#dpd_photo_remove', function(e) {
        e.preventDefault();
        $('#psi_dpd_photo').val('');
        var preview = $('#dpd_photo_upload').prev('img');
        if (preview.length) preview.remove();
    });

    // Leader Photo Uploader
    $(document).on('click', '#leader_photo_upload', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Pilih Foto Pimpinan',
            button: { text: 'Gunakan Foto Ini' },
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#psi_leader_photo').val(attachment.url);
            var preview = $('#leader_photo_upload').prev('img');
            if (preview.length) {
                preview.attr('src', attachment.url);
            } else {
                $('#leader_photo_upload').parent().prepend('<img src="' + attachment.url + '" style="width:80px;height:80px;object-fit:cover;border-radius:12px;" />');
            }
        });
        frame.open();
    });

})(jQuery);
