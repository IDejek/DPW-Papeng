/**
 * PSI Papeng Premium — Admin JS
 * @package PSI_Papeng_Premium
 */
(function ($) {
    'use strict';

    /* ── Verify / Reject Member ─────────────────────────────── */
    $(document).on('click', '.psi-btn-verify, .psi-btn-reject', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');
        var action = btn.data('action');
        var confirmMsg = action === 'verified' ? psiPapengAdmin.i18n.confirm_verify : psiPapengAdmin.i18n.confirm_reject;

        if (!confirm(confirmMsg)) return;

        btn.prop('disabled', true);

        $.post(psiPapengAdmin.ajaxUrl, {
            action: 'psi_member_verify',
            nonce: psiPapengAdmin.nonce,
            member_id: id,
            status: action
        }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.data.message);
                btn.prop('disabled', false);
            }
        }).fail(function () {
            alert('Terjadi kesalahan.');
            btn.prop('disabled', false);
        });
    });

    /* ── Delete Member ──────────────────────────────────────── */
    $(document).on('click', '.psi-btn-delete', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');

        if (!confirm(psiPapengAdmin.i18n.confirm_delete)) return;

        btn.prop('disabled', true);

        $.post(psiPapengAdmin.ajaxUrl, {
            action: 'psi_member_delete',
            nonce: psiPapengAdmin.nonce,
            member_id: id
        }, function (res) {
            if (res.success) {
                btn.closest('tr').fadeOut(300, function () { $(this).remove(); });
            } else {
                alert(res.data.message);
                btn.prop('disabled', false);
            }
        }).fail(function () {
            alert('Terjadi kesalahan.');
            btn.prop('disabled', false);
        });
    });

    /* ── Export CSV ─────────────────────────────────────────── */
    $('#psiExportCsv').on('click', function () {
        var btn = $(this);
        var originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner is-active" style="float:none;"></span> Mengunduh...');

        $.post(psiPapengAdmin.ajaxUrl, {
            action: 'psi_member_export_csv',
            nonce: psiPapengAdmin.nonce
        }, function (res) {
            if (res.success && res.data.csv) {
                var blob = new Blob([res.data.csv], { type: 'text/csv;charset=utf-8;' });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'psi-anggota-' + new Date().toISOString().slice(0, 10) + '.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else {
                alert(res.data.message || 'Gagal mengekspor.');
            }
            btn.prop('disabled', false).html(originalText);
        }).fail(function () {
            alert('Terjadi kesalahan jaringan.');
            btn.prop('disabled', false).html(originalText);
        });
    });

    /* ── SMTP Test ──────────────────────────────────────────── */
    $('#psi-smtp-test').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var result = $('#psi-smtp-test-result');
        var email = $('#psi_test_email').val();

        if (!email) return;

        result.html('<span class="spinner is-active" style="float:none;"></span> Mengirim...');

        $.post(psiPapengAdmin.ajaxUrl, {
            action: 'psi_smtp_test',
            nonce: psiPapengAdmin.nonce,
            email: email
        }, function (res) {
            if (res.success) {
                result.html('<div class="notice notice-success inline"><p>' + res.data.message + '</p></div>');
            } else {
                result.html('<div class="notice notice-error inline"><p>' + res.data.message + '</p></div>');
            }
        }).fail(function () {
            result.html('<div class="notice notice-error inline"><p>Kesalahan jaringan.</p></div>');
        });
    });

    /* ── Member Form AJAX (Front-end) ──────────────────────── */
    $(document).on('submit', '#psi-member-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var result = $('#psi-member-result');
        var btn = form.find('button[type="submit"]');
        var formData = new FormData(form[0]);
        formData.append('action', 'psi_member_register');
        formData.append('nonce', $('input[name="psi_member_nonce"]').val());

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

        $.ajax({
            url: dpwPsi.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (res) {
            if (res.success) {
                result.html('<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' + res.data.message + '</div>');
                form[0].reset();
                if (res.data.redirect) {
                    setTimeout(function () { window.location.href = res.data.redirect; }, 2000);
                }
            } else {
                result.html('<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>' + res.data.message + '</div>');
            }
            btn.prop('disabled', false).html('<i class="bi bi-person-plus me-2"></i>Daftar Sekarang');
        }).fail(function () {
            result.html('<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>Terjadi kesalahan jaringan.</div>');
            btn.prop('disabled', false).html('<i class="bi bi-person-plus me-2"></i>Daftar Sekarang');
        });
    });

    /* ── Member Check Status (Front-end) ───────────────────── */
    $(document).on('submit', '#psi-check-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var result = $('#psi-check-result');
        var btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mencari...');

        $.post(dpwPsi.ajaxUrl, {
            action: 'psi_member_check',
            nonce: $('#psi-check-nonce').val(),
            check_email: $('#psi-check-email').val()
        }, function (res) {
            if (res.success) {
                result.html(res.data.html);
            } else {
                result.html('<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>' + res.data.message + '</div>');
            }
            btn.prop('disabled', false).html('<i class="bi bi-search me-1"></i>Cek Status');
        }).fail(function () {
            result.html('<div class="alert alert-danger">Kesalahan jaringan.</div>');
            btn.prop('disabled', false).html('<i class="bi bi-search me-1"></i>Cek Status');
        });
    });

})(jQuery);
