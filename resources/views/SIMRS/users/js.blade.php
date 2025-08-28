<script>
    $(document).ready(function() {
        // Table Users
        let usersTable = $('#fixed-header-datatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route("users.table") }}", // Sesuaikan dengan route Anda
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
            $('#signupForm')[0].reset();
            $('#info-header-modalLabel').text('ADD USERS');
            $('#submitUsers').text('Submit'); // Atau $('#submitUsers').html('Update');
            $('#password').closest('.mb-3').show();
            $('#confirm_password').closest('.mb-3').show().css('display', 'block');
        });

        // Submit Form
        $('#signupForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let userId = $('#userId').val(); // Ambil ID user jika ada
            let url = userId ? `/simrs/users/${userId}/update` : "{{ route("users.store") }}";
            let method = userId ? 'POST' : 'POST'; // Gunakan POST dan _method=PUT untuk update

            if (userId) {
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

                        $('#signupForm')[0].reset();
                        $('#userId').val(''); // Reset ID
                        usersTable.ajax.reload();
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

        // Edit Users
        window.editUsers = function(id) {
            const modal = $('#info-header-modal');
            modal.modal('show');

            $('#signupForm')[0].reset();
            $('#password_confirmation').hide(); // Sembunyikan password pada edit
            $('#userId').val(id); // Set ID user
            // console.log('id', id);
            $('#confirm_password').closest('.mb-3').hide();
            $('#password').closest('.mb-3').hide();

            $.ajax({
                url: `/simrs/users/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    console.log('dataUsers :', response);
                    $('#info-header-modalLabel').text('EDIT USER'); // Ubah judul
                    $('#submitUsers').text('Update'); // Atau $('#submitUsers').html('Update');
                    $('#name').val(response.name);
                    $('#email').val(response.email);
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

        // Delete Users
        window.deleteUsers = function(id) {
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
                        url: "{{ route("users.delete", ":id") }}".replace(':id',
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
                                usersTable.ajax.reload(); // Reload DataTables
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
                                'Terjadi kesalahan saat menghapus user.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        window.assignRole = function(id) {
            let userId = id;
            console.log(userId);
            $('#user_id').val(userId);
            $('#assignRolesModal').modal('show');

            // Load Permissions yang tersedia
            $.ajax({
                url: '{{ route("users.roles.list") }}',
                method: 'GET',
                success: function(data) {
                    let options = '';
                    data.roles.forEach(function(role) {
                        options +=
                            `<option value="${role.name}">${role.name}</option>`;
                    });
                    $('#roles').html(options).select2();
                }
            });

            // Load Permissions yang sudah dimiliki oleh Role
            $.ajax({
                url: `/simrs/users/${id}/roles`,
                method: 'GET',
                success: function(data) {
                    $('#roles').val(data.assignedRoles).trigger('change');
                }
            });
        }

        $('#assignRolesForm').on('submit', function(e) {
            e.preventDefault();

            let userId = $('#user_id').val();
            let selectedRoles = $('#roles').val();
            console.log(selectedRoles);

            $.ajax({
                url: `/simrs/users/${userId}/rolesAttach`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    roles: selectedRoles
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

                    $('#assignRolesModal').modal('hide');
                }
            });
        });

    });
</script>
