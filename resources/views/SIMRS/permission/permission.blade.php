@extends("template.partials._app")

@section("styles")
    @include("template.plugins.dataTables")
    @include("template.plugins.select2")
@endsection

@section("content")
    @include("SIMRS.permission.modal")
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                <h4 class="page-title">Permissions</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Permissions</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Data Permissions</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <!-- Button Trigger Modal -->
                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#info-header-modal">
                            <i class=" ri-user-add-fill"></i>
                        </button>
                    </div>
                    <br>
                    <h4 class="header-title">Data Permissions</h4>
                    <!-- Tambahkan table-responsive agar tabel bisa di-scroll jika lebarnya lebih besar dari layar -->
                    <div class="table-responsive">
                        <table id="fixed-header-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->

    {{-- tes --}}
    {{-- teslagi --}}
    {{-- teslagiwdw --}}
@endsection

@section("scripts")
    @include("SIMRS.permission.js")
@append
