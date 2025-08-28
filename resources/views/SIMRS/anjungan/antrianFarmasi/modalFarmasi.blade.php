<!-- Modal -->
<div class="modal fade" id="farmasiModal" tabindex="-1" aria-labelledby="farmasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="farmasiModalLabel">
                    <i class="fas fa-ticket-alt me-2"></i> Ambil Nomor Antrian Farmasi
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body text-center">
                <div class="d-flex justify-content-center">
                    <i class="fas fa-ticket-alt fa-5x text-primary animate-ticket"></i>
                </div>

                <!-- Form Ambil Nomor Antrian -->
                <form id="ambilNomorFarmasi" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"> <!-- CSRF token Laravel -->

                    <!-- Input No Rawat dengan Tombol Keyboard -->
                    <div class="mb-3">
                        <label for="no_rawat" class="form-label">No Rawat</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-center fs-4" id="no_rawat" name="no_rawat"
                                readonly required>
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleKeyboard()">
                                <i class="fas fa-keyboard"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="barcodeScan()">
                                <i class="fas fa-barcode"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Keyboard Numerik -->
                <div id="customKeyboard" class="mt-3 d-none">
                    <div class="d-grid gap-2">
                        <div class="row g-2">
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(1)">1</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(2)">2</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(3)">3</button>
                        </div>
                        <div class="row g-2">
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(4)">4</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(5)">5</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(6)">6</button>
                        </div>
                        <div class="row g-2">
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(7)">7</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(8)">8</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(9)">9</button>
                        </div>
                        <div class="row g-2">
                            <button class="col btn btn-lg btn-warning" onclick="deleteNumber()">⌫</button>
                            <button class="col btn btn-lg btn-light" onclick="inputNumber(0)">0</button>
                            <button class="col btn btn-lg btn-success" onclick="submitFormFarmasi(event)">✔</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scan Barcode -->
<div class="modal fade" id="scanBarcodeModal" tabindex="-1" aria-labelledby="scanBarcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="scanBarcodeModalLabel">
                    <i class="fas fa-barcode me-2"></i> Scan Barcode / QR Code
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" class="border p-2"></div>
                <p class="mt-2 text-muted">Arahkan kamera ke barcode atau QR Code pasien.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Script jQuery & AJAX -->
@include("SIMRS.anjungan.antrianFarmasi.js")

{{-- tes --}}
