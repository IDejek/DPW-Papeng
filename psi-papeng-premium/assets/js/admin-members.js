/**
 * PSI Papeng Premium - Admin Members JavaScript
 */
(function($) {
    'use strict';

    function showNotice(msg, type) {
        var n = $('#psi-admin-notice');
        if (!n.length) {
            n = $('<div id="psi-admin-notice"></div>');
            $('.psi-admin-header').after(n);
        }
        n.removeClass('success error').addClass(type).html(msg).fadeIn(200);
        setTimeout(function() { n.fadeOut(300); }, 4000);
    }

    // ============================================
    // MODAL OPEN / CLOSE
    // ============================================
    function openModal(title, data) {
        $('#memberModalTitle').text(title);
        if (data) {
            $('#memberEditId').val(data.id || '');
            $('#memberName').val(data.full_name || '');
            $('#memberNik').val(data.nik || '');
            $('#memberEmail').val(data.email || '');
            $('#memberPhone').val(data.phone || '');
            $('#memberKab').val(data.kabupaten || '');
            $('#memberKec').val(data.kecamatan || '');
            $('#memberAddress').val(data.address || '');
            $('#memberDob').val(data.birth_date || '');
            $('#memberGender').val(data.gender || '');
            $('#memberOccupation').val(data.occupation || '');
            $('#memberNotes').val(data.notes || '');
        } else {
            $('#memberEditId').val('');
            $('#memberModal').find('input, textarea, select').not('input[type="hidden"]').val('');
        }
        $('#memberModal').show();
        $('body').css('overflow', 'hidden');
    }

    function closeModal() {
        $('#memberModal').hide();
        $('body').css('overflow', '');
    }

    // Open Add Modal
    $('#addMemberBtn').on('click', function() {
        openModal('Tambah Anggota Baru', null);
    });

    // Open Edit Modal
    $(document).on('click', '.psi-edit-member', function() {
        var id = $(this).data('id');
        // Fetch member data
        $.post(psiMembers.ajaxUrl, {
            action: 'psi_member_get_single',
            nonce: psiMembers.nonce,
            id: id
        }, function(res) {
            if (res.success) {
                openModal('Edit Anggota', res.data);
            } else {
                showNotice(res.data.message || 'Gagal memuat data.', 'error');
            }
        });
    });

    // Note: For getting single member, we need a handler. 
    // We'll use a simpler approach - read from table row
    $(document).on('click', '.psi-edit-member', function(e) {
        e.stopImmediatePropagation(); // Prevent double trigger
        var row = $(this).closest('tr');
        var cells = row.find('td');
        openModal('Edit Anggota', {
            id: $(this).data('id'),
            full_name: cells.eq(1).text().trim(),
            nik: cells.eq(2).text().trim(),
            email: cells.eq(3).text().trim(),
            phone: cells.eq(4).text().trim(),
            kabupaten: cells.eq(5).text().trim()
        });
    });

    // Close modal
    $('#memberModalCancel, .psi-modal-close, .psi-modal-backdrop').on('click', function() {
        closeModal();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // ============================================
    // SAVE MEMBER (Add / Update)
    // ============================================
    $('#memberModalSave').on('click', function() {
        var btn = $(this);
        var editId = $('#memberEditId').val();
        var isEdit = editId.length > 0;

        var data = {
            action: isEdit ? 'psi_member_update' : 'psi_member_add',
            nonce: psiMembers.nonce,
            name: $('#memberName').val(),
            nik: $('#memberNik').val(),
            email: $('#memberEmail').val(),
            phone: $('#memberPhone').val(),
            kabupaten: $('#memberKab').val(),
            kecamatan: $('#memberKec').val(),
            address: $('#memberAddress').val(),
            dob: $('#memberDob').val(),
            gender: $('#memberGender').val(),
            occupation: $('#memberOccupation').val(),
            notes: $('#memberNotes').val()
        };

        if (isEdit) data.id = editId;

        // Validate required fields
        if (!data.name || !data.nik || !data.email || !data.phone || !data.kabupaten) {
            showNotice('Mohon lengkapi kolom yang bertanda bintang merah.', 'error');
            return;
        }

        btn.addClass('psi-loading').prop('disabled', true);
        $.post(psiMembers.ajaxUrl, data)
            .done(function(res) {
                if (res.success) {
                    showNotice(res.data.message, 'success');
                    closeModal();
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    showNotice(res.data.message, 'error');
                }
            })
            .fail(function() {
                showNotice('Terjadi kesalahan jaringan.', 'error');
            })
            .always(function() {
                btn.removeClass('psi-loading').prop('disabled', false);
            });
    });

    // ============================================
    // DELETE MEMBER
    // ============================================
    $(document).on('click', '.psi-delete-member', function() {
        if (!confirm('Apakah Anda yakin ingin menghapus anggota ini?')) return;

        var id = $(this).data('id');
        $.post(psiMembers.ajaxUrl, {
            action: 'psi_member_delete',
            nonce: psiMembers.nonce,
            id: id
        }, function(res) {
            if (res.success) {
                showNotice(res.data.message, 'success');
                setTimeout(function() { location.reload(); }, 800);
            } else {
                showNotice(res.data.message, 'error');
            }
        });
    });

    // ============================================
    // VERIFY MEMBER
    // ============================================
    $(document).on('click', '.psi-verify-member', function() {
        if (!confirm('Verifikasi anggota ini?')) return;

        var id = $(this).data('id');
        $.post(psiMembers.ajaxUrl, {
            action: 'psi_member_verify',
            nonce: psiMembers.nonce,
            id: id
        }, function(res) {
            if (res.success) {
                showNotice(res.data.message, 'success');
                setTimeout(function() { location.reload(); }, 800);
            } else {
                showNotice(res.data.message, 'error');
            }
        });
    });

    // ============================================
    // EXPORT MEMBERS
    // ============================================
    $('#exportMembersBtn').on('click', function() {
        var btn = $(this);
        btn.addClass('psi-loading').prop('disabled', true);

        $.post(psiMembers.ajaxUrl, {
            action: 'psi_member_export',
            nonce: psiMembers.nonce
        }, function(res) {
            if (res.success) {
                showNotice('Mengunduh file CSV...', 'success');
                // Trigger download
                var a = document.createElement('a');
                a.href = res.data.url;
                a.download = res.data.filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } else {
                showNotice(res.data.message, 'error');
            }
        })
        .fail(function() {
            showNotice('Gagal mengekspor data.', 'error');
        })
        .always(function() {
            btn.removeClass('psi-loading').prop('disabled', false);
        });
    });

})(jQuery);
