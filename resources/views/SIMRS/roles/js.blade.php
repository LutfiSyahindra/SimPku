<script>
    $(document).ready(function() {

        // CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Table Users
        let rolesTable = $('#fixed-header-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("roles.table") }}", // Sesuaikan dengan route Anda
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
            $('#rolesForm')[0].reset();
            $('#info-header-modalLabel').text('ADD ROLES');
            $('#submitRoles').text('Submit'); // Atau $('#submitUsers').html('Update');
        });

        $('#rolesForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let roleId = $('#roleId').val(); // Ambil ID user jika ada
            let url = roleId ? `/simrs/roles/${roleId}/update` : "{{ route("roles.store") }}";
            let method = roleId ? 'POST' : 'POST'; // Gunakan POST dan _method=PUT untuk update

            if (roleId) {
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

                        $('#rolesForm')[0].reset();
                        $('#roleId').val(''); // Reset ID
                        rolesTable.ajax.reload();
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

        window.editRoles = function(id) {
            const modal = $('#info-header-modal');
            modal.modal('show');

            $('#rolesForm')[0].reset();
            $('#roleId').val(id); // Set ID user
            console.log('id', id);

            $.ajax({
                url: `/simrs/roles/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    console.log('dataRoles :', response);
                    $('#info-header-modalLabel').text('EDIT ROLES'); // Ubah judul
                    $('#submitRoles').text('Update'); // Atau $('#submitUsers').html('Update');
                    $('#name').val(response.name);
                },
                error: function(xhr) {
                    console.error('Gagal mengambil data user', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch user data. Please try again.',
                    });
                    modal.modal('hide');
                }
            });
        };

        window.deleteRoles = function(id) {
            // Tampilkan konfirmasi hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'User ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE menggunakan AJAX
                    $.ajax({
                        url: "{{ route("roles.delete", ":id") }}".replace(':id',
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
                                rolesTable.ajax.reload(); // Reload DataTables
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

        window.assignPermissions = function(id) {
            let roleId = id;
            console.log(roleId);
            $('#role_id').val(roleId);
            $('#assignPermissionsModal').modal('show');

            // Load Permissions yang tersedia
            $.ajax({
                url: '/simrs/roles/permissions/list',
                method: 'GET',
                success: function(data) {
                    let options = '';
                    data.permissions.forEach(function(permission) {
                        options +=
                            `<option value="${permission.name}">${permission.name}</option>`;
                    });
                    $('#permissions').html(options).select2();
                }
            });

            // Load Permissions yang sudah dimiliki oleh Role
            $.ajax({
                url: `/simrs/roles/${roleId}/permissions`,
                method: 'GET',
                success: function(data) {
                    $('#permissions').val(data.assignedPermissions).trigger('change');
                }
            });
        }

        $('#assignPermissionsForm').on('submit', function(e) {
            e.preventDefault();

            let roleId = $('#role_id').val();
            let selectedPermissions = $('#permissions').val();
            console.log(selectedPermissions);

            $.ajax({
                url: `/simrs/roles/${roleId}/permissionsAttach`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    permissions: selectedPermissions
                },
                success: function(response) {
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

                    $('#assignPermissionsModal').modal('hide');
                }
            });
        });

    });
</script>
