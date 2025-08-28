<div id="assignRolesModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignRolesForm">
                    <input type="hidden" id="user_id" name="user_id">
                    <label for="roles">Pilih Role:</label>
                    <select id="roles" name="roles[]" class="form-control select2" multiple>
                        <!-- Permissions akan dimuat dengan AJAX -->
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="saveRoles">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
