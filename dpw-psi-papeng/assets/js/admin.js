/**
 * DPW PSI Theme - Admin JavaScript (Meta Boxes)
 * @version 1.0.1
 */
(function($) {
    'use strict';

    // DPD Photo Upload
    $(document).on('click', '#dpd_photo_upload', function(e) {
        e.preventDefault();
        
        // Cek apakah media library WordPress sudah dimuat
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            alert('Media library belum siap. Pastikan halaman ini dimuat melalui admin WordPress.');
            return;
        }

        var frame = wp.media({
            title: 'Pilih Foto Ketua DPD',
            button: { text: 'Gunakan Foto Ini' },
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.url;

            // Update input hidden
            $('#_psi_dpd_photo').val(url);

            // Update atau buat preview gambar
            var $imgPreview = $('#dpd_photo_upload').parent().find('img').first();
            
            if ($imgPreview.length) {
                // Jika gambar preview sudah ada, update src-nya
                $imgPreview.attr('src', url);
            } else {
                // Jika belum ada, buat elemen img baru sebelum tombol
                $('#dpd_photo_upload').before(
                    '<img src="' + url + '" style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin-bottom:8px;display:block;">'
                );
            }
        });

        frame.open();
    });

})(jQuery);
