/**
 * PSI Papeng Premium - Admin Settings JavaScript
 * @version 1.0.1
 */
(function($) {
    'use strict';

    // === UTILITY: Notice ===
    function showNotice(message, type) {
        var el = $('#psi-admin-notice');
        if (!el.length) return;
        el.removeClass('success error').addClass(type).html(message).fadeIn(200);
        setTimeout(function() { el.fadeOut(300); }, 4000);
    }

    // === UTILITY: Save via AJAX ===
    function saveSettings(section, data) {
        return $.ajax({
            url: psiPapengAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'psi_papeng_save_settings',
                nonce: psiPapengAdmin.nonce,
                section: section,
                data: JSON.stringify(data)
            }
        });
    }

    // === MEDIA UPLOADER ===
    $(document).on('click', '.psi-upload-btn', function(e) {
        e.preventDefault();
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            alert('Media library tidak tersedia.');
            return;
        }

        var btn = $(this);
        var targetId = btn.data('target');

        var frame = wp.media({
            title: 'Pilih Gambar',
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function() {
            var url = frame.state().get('selection').first().toJSON().url;
            var $container = btn.closest('.psi-image-upload');

            if (targetId) {
                $('#' + targetId).val(url);
            } else {
                $container.find('input[type="hidden"]').first().val(url);
            }

            var $preview = $container.find('.psi-image-preview');
            if ($preview.length) {
                $preview.attr('src', url).show();
            } else {
                $container.prepend('<img src="' + url + '" class="psi-image-preview">');
            }
        });
        frame.open();
    });

    $(document).on('click', '.psi-remove-img-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var targetId = btn.data('target');
        var $container = btn.closest('.psi-image-upload');

        if (targetId) {
            $('#' + targetId).val('');
        } else {
            $container.find('input[type="hidden"]').first().val('');
        }
        $container.find('.psi-image-preview').remove();
    });

    // === REPEATER TOGGLE ===
    $(document).on('click', '.psi-repeater-header', function(e) {
        if ($(e.target).closest('.psi-repeater-actions').length) return;
        $(this).next('.psi-repeater-body').toggleClass('open');
    });

    $(document).on('click', '.psi-toggle-repeater', function(e) {
        e.stopPropagation();
        $(this).closest('.psi-repeater-item').find('.psi-repeater-body').toggleClass('open');
    });

    // === REPEATER REMOVE ===
    $(document).on('click', '.psi-remove-repeater', function(e) {
        e.stopPropagation();
        if (confirm('Hapus item ini?')) {
            $(this).closest('.psi-repeater-item').fadeOut(300, function() { $(this).remove(); });
        }
    });

    // === SLIDER MANAGEMENT ===
    var slideHTML = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header"><span class="psi-repeater-title">Slide Baru</span>' +
        '<div class="psi-repeater-actions"><button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
        '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button></div></div>' +
        '<div class="psi-repeater-body open">' +
        '<div class="psi-field"><label>Gambar Slide</label><div class="psi-image-upload"><input type="hidden" class="psi-slide-image" value=""><button type="button" class="button psi-upload-btn">Pilih Gambar</button><button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button></div></div>' +
        '<div class="psi-field"><label>Judul</label><input type="text" class="large-text psi-slide-title" value=""></div>' +
        '<div class="psi-field"><label>Subjudul</label><textarea class="large-text psi-slide-subtitle" rows="3"></textarea></div>' +
        '<div class="psi-field-row"><div class="psi-field"><label>Teks Tombol</label><input type="text" class="regular-text psi-slide-btn-text" value=""></div><div class="psi-field"><label>URL Tombol</label><input type="url" class="regular-text psi-slide-btn-url" value=""></div></div>' +
        '</div></div>';

    $('#addSlideBtn').on('click', function() {
        $('#slidesContainer').append(slideHTML);
    });

    $('#saveSlidesBtn').on('click', function() {
        var btn = $(this);
        var slides = [];
        $('#slidesContainer .psi-repeater-item').each(function() {
            slides.push({
                image: $(this).find('.psi-slide-image').val(),
                title: $(this).find('.psi-slide-title').val(),
                subtitle: $(this).find('.psi-slide-subtitle').val(),
                btn_text: $(this).find('.psi-slide-btn-text').val(),
                btn_url: $(this).find('.psi-slide-btn-url').val()
            });
        });
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('slides', slides)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan data.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === WELCOME SETTINGS ===
    $('#saveWelcomeBtn').on('click', function() {
        var btn = $(this);
        var data = {
            image: $('#welcomeImage').val(),
            title: $('#welcomeTitle').val(),
            text: $('#welcomeText').val(), // Menggunakan textarea biasa (anti-crash)
            name: $('#welcomeName').val(),
            role: $('#welcomeRole').val()
        };
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('welcome', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === LEADERSHIP SETTINGS ===
    var leaderHTML = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header"><span class="psi-repeater-title">Pimpinan Baru</span>' +
        '<div class="psi-repeater-actions"><button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
        '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button></div></div>' +
        '<div class="psi-repeater-body open">' +
        '<div class="psi-field"><label>Foto</label><div class="psi-image-upload"><input type="hidden" class="psi-leader-photo" value=""><button type="button" class="button psi-upload-btn">Pilih Gambar</button><button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button></div></div>' +
        '<div class="psi-field-row"><div class="psi-field"><label>Nama Lengkap</label><input type="text" class="large-text psi-leader-name" value=""></div><div class="psi-field"><label>Jabatan</label><input type="text" class="regular-text psi-leader-role" value=""></div></div>' +
        '<div class="psi-field"><label>Deskripsi Singkat</label><textarea class="large-text psi-leader-desc" rows="3"></textarea></div>' +
        '<div class="psi-field-row"><div class="psi-field"><label>Facebook URL</label><input type="url" class="large-text psi-leader-fb" value=""></div><div class="psi-field"><label>Instagram URL</label><input type="url" class="large-text psi-leader-ig" value=""></div></div>' +
        '</div></div>';

    $('#addLeaderBtn').on('click', function() {
        $('#leadersContainer').append(leaderHTML);
    });

    $('#saveLeadersBtn').on('click', function() {
        var btn = $(this);
        var leaders = [];
        $('#leadersContainer .psi-repeater-item').each(function() {
            leaders.push({
                name: $(this).find('.psi-leader-name').val(),
                role: $(this).find('.psi-leader-role').val(),
                photo: $(this).find('.psi-leader-photo').val(),
                desc: $(this).find('.psi-leader-desc').val(),
                social: {
                    facebook: $(this).find('.psi-leader-fb').val(),
                    instagram: $(this).find('.psi-leader-ig').val()
                }
            });
        });
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('leadership', leaders)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === DIVISIONS SETTINGS ===
    var divHTML = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header"><span class="psi-repeater-title">Bidang Baru</span>' +
        '<div class="psi-repeater-actions"><button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
        '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button></div></div>' +
        '<div class="psi-repeater-body open">' +
        '<div class="psi-field-row"><div class="psi-field"><label>Nama Bidang</label><input type="text" class="large-text psi-div-title" value=""></div>' +
        '<div class="psi-field"><label>Ikon</label><select class="psi-div-icon"><option value="bi-bank">bi-bank</option><option value="bi-shop">bi-shop</option><option value="bi-cpu">bi-cpu</option><option value="bi-trophy">bi-trophy</option><option value="bi-tree">bi-tree</option><option value="bi-heart-pulse">bi-heart-pulse</option><option value="bi-book">bi-book</option><option value="bi-people">bi-people</option><option value="bi-briefcase">bi-briefcase</option><option value="bi-gear">bi-gear</option><option value="bi-globe">bi-globe</option><option value="bi-shield-check">bi-shield-check</option></select></div></div>' +
        '<div class="psi-field"><label>Nama Ketua Bidang</label><input type="text" class="large-text psi-div-head" value=""></div>' +
        '<div class="psi-field"><label>Deskripsi</label><textarea class="large-text psi-div-desc" rows="3"></textarea></div>' +
        '</div></div>';

    $('#addDivisionBtn').on('click', function() {
        $('#divisionsContainer').append(divHTML);
    });

    $('#saveDivisionsBtn').on('click', function() {
        var btn = $(this);
        var divisions = [];
        $('#divisionsContainer .psi-repeater-item').each(function() {
            divisions.push({
                title: $(this).find('.psi-div-title').val(),
                icon: $(this).find('.psi-div-icon').val(),
                head: $(this).find('.psi-div-head').val(),
                desc: $(this).find('.psi-div-desc').val()
            });
        });
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('divisions', divisions)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === SOCIAL SETTINGS ===
    $('#saveSocialBtn').on('click', function() {
        var btn = $(this);
        var data = {
            facebook: $('#socialFacebook').val(),
            instagram: $('#socialInstagram').val(),
            youtube: $('#socialYoutube').val(),
            tiktok: $('#socialTiktok').val()
        };
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('social', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === CONTACT SETTINGS ===
    $('#saveContactBtn').on('click', function() {
        var btn = $(this);
        var data = {
            address: $('#contactAddress').val(),
            email: $('#contactEmail').val(),
            whatsapp: $('#contactWhatsapp').val(),
            map_url: $('#contactMap').val()
        };
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('contact', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

    // === SEO SETTINGS ===
    $('#saveSeoBtn').on('click', function() {
        var btn = $(this);
        var data = {
            enable_sitemap: $('#seoSitemap').is(':checked') ? 'yes' : 'no',
            robots_txt: $('#seoRobots').val()
        };
        btn.addClass('psi-loading').prop('disabled', true);
        saveSettings('seo', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading').prop('disabled', false); });
    });

})(jQuery);
