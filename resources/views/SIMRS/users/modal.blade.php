<div id="info-header-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="info-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="info-header-modalLabel"></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="signupForm">
                    @csrf
                    <!-- Name -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                            required>
                        <label for="name">Name</label>
                    </div>

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                        <label for="password">Password</label>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating mb-3">
                        <input id="confirm_password" class="form-control" name="password_confirmation" type="password">
                        <label for="confirm_password">Confirm password</label>
                    </div>

                    <input id="userId" class="form-control" name="userId" type="hidden">
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" id="submitUsers" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
