/**
 * PSI Papeng Premium - Admin Settings JavaScript
 */
(function($) {
    'use strict';

    // ============================================
    // UTILITY: Show notice
    // ============================================
    function showNotice(message, type) {
        var el = $('#psi-admin-notice');
        if (!el.length) return;
        el.removeClass('success error').addClass(type).html(message).fadeIn(200);
        setTimeout(function() { el.fadeOut(300); }, 4000);
    }

    // ============================================
    // UTILITY: Save settings via AJAX
    // ============================================
    function saveSettings(section, data) {
        return $.ajax({
            url: psiPapengAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'psi_papeng_save_settings',
                nonce: psiPapengAdmin.nonce,
                section: section,
                data: JSON.stringify(data)
            },
            beforeSend: function() {
                // Disable save button temporarily
            }
        });
    }

    // ============================================
    // MEDIA UPLOADER (reusable)
    // ============================================
    $(document).on('click', '.psi-upload-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var target = btn.data('target');
        var frame = wp.media({
            title: 'Pilih Gambar',
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.url;

            if (target) {
                $('#' + target).val(url);
                var preview = btn.closest('.psi-image-upload').find('.psi-image-preview');
                if (preview.length) {
                    preview.attr('src', url).show();
                } else {
                    btn.closest('.psi-image-upload').prepend('<img src="' + url + '" class="psi-image-preview">');
                }
            } else {
                var input = btn.closest('.psi-image-upload').find('input[type="hidden"]').first();
                input.val(url);
                var preview = btn.closest('.psi-image-upload').find('.psi-image-preview');
                if (preview.length) {
                    preview.attr('src', url).show();
                } else {
                    btn.closest('.psi-image-upload').prepend('<img src="' + url + '" class="psi-image-preview">');
                }
            }
        });
        frame.open();
    });

    $(document).on('click', '.psi-remove-img-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var target = btn.data('target');
        if (target) {
            $('#' + target).val('');
        } else {
            btn.closest('.psi-image-upload').find('input[type="hidden"]').val('');
        }
        btn.closest('.psi-image-upload').find('.psi-image-preview').remove();
    });

    // ============================================
    // TOGGLE REPEATER
    // ============================================
    $(document).on('click', '.psi-repeater-header', function(e) {
        if ($(e.target).closest('.psi-repeater-actions').length) return;
        $(this).next('.psi-repeater-body').toggleClass('open');
        var icon = $(this).find('.dashicons-arrow-down, .dashicons-arrow-up');
        if (icon.length) {
            icon.toggleClass('dashicons-arrow-down dashicons-arrow-up');
        }
    });

    $(document).on('click', '.psi-toggle-repeater', function(e) {
        e.stopPropagation();
        $(this).closest('.psi-repeater-item').find('.psi-repeater-body').toggleClass('open');
    });

    // ============================================
    // REMOVE REPEATER ITEM
    // ============================================
    $(document).on('click', '.psi-remove-repeater', function(e) {
        e.stopPropagation();
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            $(this).closest('.psi-repeater-item').fadeOut(300, function() { $(this).remove(); });
        }
    });

    // ============================================
    // SLIDER MANAGEMENT
    // ============================================
    var slideTemplate = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header">' +
            '<span class="psi-repeater-title">Slide Baru</span>' +
            '<div class="psi-repeater-actions">' +
                '<button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
                '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>' +
            '</div>' +
        '</div>' +
        '<div class="psi-repeater-body open">' +
            '<div class="psi-field"><label>Gambar Slide</label>' +
                '<div class="psi-image-upload">' +
                    '<input type="hidden" class="psi-slide-image" value="">' +
                    '<button type="button" class="button psi-upload-btn">Pilih Gambar</button>' +
                    '<button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>' +
                '</div>' +
            '</div>' +
            '<div class="psi-field"><label>Judul</label><input type="text" class="regular-text psi-slide-title" value=""></div>' +
            '<div class="psi-field"><label>Subjudul</label><textarea class="large-text psi-slide-subtitle" rows="3"></textarea></div>' +
            '<div class="psi-field-row">' +
                '<div class="psi-field"><label>Teks Tombol</label><input type="text" class="regular-text psi-slide-btn-text" value=""></div>' +
                '<div class="psi-field"><label>URL Tombol</label><input type="url" class="regular-text psi-slide-btn-url" value=""></div>' +
            '</div>' +
        '</div>' +
    '</div>';

    $('#addSlideBtn').on('click', function() {
        $('#slidesContainer').append(slideTemplate);
        // Open the new item's body
        $('#slidesContainer .psi-repeater-item:last .psi-repeater-body').addClass('open');
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

        btn.addClass('psi-loading');
        saveSettings('slides', slides)
            .done(function(res) {
                showNotice(res.data.message, res.success ? 'success' : 'error');
            })
            .fail(function() {
                showNotice('Gagal menyimpan data.', 'error');
            })
            .always(function() {
                btn.removeClass('psi-loading');
            });
    });

    // ============================================
    // WELCOME SETTINGS
    // ============================================
    $('#saveWelcomeBtn').on('click', function() {
        var btn = $(this);
        var data = {
            image: $('#welcomeImage').val(),
            title: $('#welcomeTitle').val(),
            text: (typeof tinyMCE !== 'undefined' && tinyMCE.get('welcomeText')) ? tinyMCE.get('welcomeText').getContent() : $('#welcomeText').val(),
            name: $('#welcomeName').val(),
            role: $('#welcomeRole').val()
        };

        btn.addClass('psi-loading');
        saveSettings('welcome', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

    // ============================================
    // LEADERSHIP SETTINGS
    // ============================================
    var leaderTemplate = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header">' +
            '<span class="psi-repeater-title">Pimpinan Baru</span>' +
            '<div class="psi-repeater-actions">' +
                '<button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
                '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>' +
            '</div>' +
        '</div>' +
        '<div class="psi-repeater-body open">' +
            '<div class="psi-field"><label>Foto</label>' +
                '<div class="psi-image-upload">' +
                    '<input type="hidden" class="psi-leader-photo" value="">' +
                    '<button type="button" class="button psi-upload-btn">Pilih Gambar</button>' +
                    '<button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>' +
                '</div>' +
            '</div>' +
            '<div class="psi-field-row">' +
                '<div class="psi-field"><label>Nama Lengkap</label><input type="text" class="large-text psi-leader-name" value=""></div>' +
                '<div class="psi-field"><label>Jabatan</label><input type="text" class="regular-text psi-leader-role" value=""></div>' +
            '</div>' +
            '<div class="psi-field"><label>Deskripsi Singkat</label><textarea class="large-text psi-leader-desc" rows="3"></textarea></div>' +
            '<div class="psi-field-row">' +
                '<div class="psi-field"><label>Facebook URL</label><input type="url" class="large-text psi-leader-fb" value=""></div>' +
                '<div class="psi-field"><label>Instagram URL</label><input type="url" class="large-text psi-leader-ig" value=""></div>' +
            '</div>' +
        '</div>' +
    '</div>';

    $('#addLeaderBtn').on('click', function() {
        $('#leadersContainer').append(leaderTemplate);
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

        btn.addClass('psi-loading');
        saveSettings('leadership', leaders)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

    // ============================================
    // DIVISIONS SETTINGS
    // ============================================
    var divTemplate = '<div class="psi-repeater-item" data-index="NEW">' +
        '<div class="psi-repeater-header">' +
            '<span class="psi-repeater-title">Bidang Baru</span>' +
            '<div class="psi-repeater-actions">' +
                '<button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>' +
                '<button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>' +
            '</div>' +
        '</div>' +
        '<div class="psi-repeater-body open">' +
            '<div class="psi-field-row">' +
                '<div class="psi-field"><label>Nama Bidang</label><input type="text" class="large-text psi-div-title" value=""></div>' +
                '<div class="psi-field"><label>Ikon</label><select class="psi-div-icon">' +
                    '<option value="bi-bank">bi-bank</option><option value="bi-shop">bi-shop</option><option value="bi-cpu">bi-cpu</option>' +
                    '<option value="bi-trophy">bi-trophy</option><option value="bi-tree">bi-tree</option><option value="bi-heart-pulse">bi-heart-pulse</option>' +
                    '<option value="bi-book">bi-book</option><option value="bi-people">bi-people</option><option value="bi-briefcase">bi-briefcase</option>' +
                    '<option value="bi-gear">bi-gear</option><option value="bi-globe">bi-globe</option><option value="bi-shield-check">bi-shield-check</option>' +
                '</select></div>' +
            '</div>' +
            '<div class="psi-field"><label>Nama Ketua Bidang</label><input type="text" class="large-text psi-div-head" value=""></div>' +
            '<div class="psi-field"><label>Deskripsi</label><textarea class="large-text psi-div-desc" rows="3"></textarea></div>' +
        '</div>' +
    '</div>';

    $('#addDivisionBtn').on('click', function() {
        $('#divisionsContainer').append(divTemplate);
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

        btn.addClass('psi-loading');
        saveSettings('divisions', divisions)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

    // ============================================
    // SOCIAL SETTINGS
    // ============================================
    $('#saveSocialBtn').on('click', function() {
        var btn = $(this);
        var data = {
            facebook: $('#socialFacebook').val(),
            instagram: $('#socialInstagram').val(),
            youtube: $('#socialYoutube').val(),
            tiktok: $('#socialTiktok').val()
        };

        btn.addClass('psi-loading');
        saveSettings('social', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

    // ============================================
    // CONTACT SETTINGS
    // ============================================
    $('#saveContactBtn').on('click', function() {
        var btn = $(this);
        var data = {
            address: $('#contactAddress').val(),
            email: $('#contactEmail').val(),
            whatsapp: $('#contactWhatsapp').val(),
            map_url: $('#contactMap').val()
        };

        btn.addClass('psi-loading');
        saveSettings('contact', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

    // ============================================
    // SEO SETTINGS
    // ============================================
    $('#saveSeoBtn').on('click', function() {
        var btn = $(this);
        var data = {
            enable_sitemap: $('#seoSitemap').is(':checked') ? 'yes' : 'no',
            robots_txt: $('#seoRobots').val()
        };

        btn.addClass('psi-loading');
        saveSettings('seo', data)
            .done(function(res) { showNotice(res.data.message, res.success ? 'success' : 'error'); })
            .fail(function() { showNotice('Gagal menyimpan.', 'error'); })
            .always(function() { btn.removeClass('psi-loading'); });
    });

})(jQuery);
