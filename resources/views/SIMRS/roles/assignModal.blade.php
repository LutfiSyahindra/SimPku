<div id="assignPermissionsModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignPermissionsForm">
                    <input type="hidden" id="role_id" name="role_id">
                    <label for="permissions">Pilih Permissions:</label>
                    <select id="permissions" name="permissions[]" class="form-control select2" multiple>
                        <!-- Permissions akan dimuat dengan AJAX -->
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="savePermissions">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
