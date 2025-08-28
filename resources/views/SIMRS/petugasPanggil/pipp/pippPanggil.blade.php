@extends("template.partials._app")

@section("styles")
    @include("template.plugins.dataTables")
    @include("template.plugins.select2")
@endsection

@section("content")
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                <h4 class="page-title">Petugas Panggil</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Petugas Panggil</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">PIPP</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{ asset("dist/assets/images/users/avatar-1.jpg") }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                    <h4 class="mb-1 mt-2" id="nama-pasien"></h4>
                    <p class="text-muted" id="dokter"></p>
                    <p class="text-muted" id="poli"></p>
                    <h4 class="text-muted" id="reg"></h4>

                    <div class="text-start mt-3">
                        <p class="text-muted mb-2"><strong>Nama :</strong> <span class="ms-2" id="detail-nama"></span></p>
                        <p class="text-muted mb-2"><strong>Tgl Lahir :</strong> <span class="ms-2" id="detail-tgl"></span>
                        </p>
                        <p class="text-muted mb-2"><strong>Alamat :</strong> <span class="ms-2" id="detail-alamat"></span>
                        </p>
                        <p class="text-muted mb-1"><strong>Jns Kelamin :</strong> <span class="ms-2"
                                id="detail-jk"></span></p>
                        <p class="text-muted mb-1"><strong>No RM :</strong> <span class="ms-2" id="detail-rm"></span></p>
                        <p class="text-muted mb-1"><strong>No Rawat :</strong> <span class="ms-2"
                                id="detail-rawat"></span></p>
                    </div>
                </div> <!-- end card-body -->
                <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <!-- Button Trigger Modal -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#info-header-modal">
                                            <i class="ri-home-office-fill me-1"></i> Poli
                                        </button>
                                    </div>
                                    <br>
                                    <h4 class="header-title">Fixed Header</h4>
                                    <!-- Tambahkan table-responsive agar tabel bisa di-scroll jika lebarnya lebih besar dari layar -->
                                    <div class="table-responsive">
                                        <table id="fixed-header-datatable"
                                            class="table table-striped dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px; text-align: left;">Noreg</th>
                                                    <th style="text-align: left;">No RM</th>
                                                    <th style="text-align: left;">Nama</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataPoli">
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div> <!-- end row-->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->

    </div>
    <!-- end row-->
@endsection

@section("scripts")
    @include("SIMRS.petugasPanggil.pipp.script")
@append
