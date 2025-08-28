<script>
    $(document).ready(function() {

        // CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Table Users
        let permissionTable = $('#fixed-header-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("permissions.table") }}", // Sesuaikan dengan route Anda
                type: "GET"
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Modal Add Users (Hide) 
        $('#info-header-modal').on('hidden.bs.modal', function() {
            console.log('Modal ditutup, reset form.');
            $('#permissionsForm')[0].reset();
            $('#info-header-modalLabel').text('ADD PERMISSION');
            $('#submitPermission').text('Submit'); // Atau $('#submitUsers').html('Update');
        });

        $('#info-header-modal').on('show.bs.modal', function() {
            console.log('Modal ditutup, reset form.');
            $('#permissionsForm')[0].reset();
            $('#info-header-modalLabel').text('ADD PERMISSION');
            $('#submitPermission').text('Submit'); // Atau $('#submitUsers').html('Update');
        });

        $('#permissionsForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let permissionId = $('#permissionId').val(); // Ambil ID user jika ada
            let url = permissionId ? `/simrs/permissions/${permissionId}/update` :
                "{{ route("permissions.store") }}";
            let method = permissionId ? 'POST' : 'POST'; // Gunakan POST dan _method=PUT untuk update

            if (permissionId) {
                formData += '&_method=PUT'; // Laravel membutuhkan _method=PUT

                // ✅ Jika UPDATE, munculkan SweetAlert
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data ini akan diperbarui!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, update!",
                    cancelButtonText: "Batal",
                    reverseButtons: true,
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(url, method, formData); // Panggil fungsi untuk update
                    } else {
                        Swal.fire({
                            title: "Dibatalkan",
                            text: "Data tidak diubah!",
                            icon: "error"
                        });
                    }
                });
            } else {
                // ✅ Jika CREATE, langsung kirim tanpa konfirmasi
                submitForm(url, method, formData);
            }
        });

        // ✅ Fungsi untuk submit form via AJAX
        function submitForm(url, method, formData) {
            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#info-header-modal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        $('#permissionsForm')[0].reset();
                        $('#permissionId').val(''); // Reset ID
                        permissionTable.ajax.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            let inputField = $(`#${key}`);
                            inputField.addClass('is-invalid');
                            inputField.after(
                                `<div class="invalid-feedback">${errors[key][0]}</div>`);
                        }
                    }
                }
            });
        }

        window.editPermissions = function(id) {
            const modal = $('#info-header-modal');
            modal.modal('show');

            $('#permissionsForm')[0].reset();
            $('#permissionId').val(id); // Set ID user
            console.log('id', id);

            $.ajax({
                url: `/simrs/permissions/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    console.log('dataPermissions :', response);
                    $('#info-header-modalLabel').text('EDIT PERMISSIONS'); // Ubah judul
                    $('#submitPermission').text(
                        'Update'); // Atau $('#submitUsers').html('Update');
                    $('#name').val(response.name);
                },
                error: function(xhr) {
                    console.error('Gagal mengambil data permissions', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch user data. Please try again.',
                    });
                    modal.modal('hide');
                }
            });
        };

        window.deletePermissions = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Permission ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("permissions.delete", ":id") }}".replace(':id',
                            id),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.message,
                                    'success'
                                );
                                permissionTable.ajax.reload(); // Reload DataTables
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus role.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

    });
</script>
