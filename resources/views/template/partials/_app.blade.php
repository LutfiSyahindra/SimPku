<!DOCTYPE html>
<html lang="en" data-layout-mode="detached">

    <head>
        @include("template.partials.head")
        @yield("styles")
        {{-- @vite(["resources/js/app.js"]) --}}
    </head>

    <body>
        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Topbar Start ========== -->
            @include("template.partials.topbar")
            <!-- ========== Topbar End ========== -->

            <!-- ========== Left Sidebar Start ========== -->
            @include("template.partials.leftbar")
            <!-- ========== Left Sidebar End ========== -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        @yield("content")
                    </div>
                    <!-- container -->

                </div>
                <!-- content -->

                <!-- Footer Start -->
                @include("template.partials.footer")
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        @include("template.partials.js")
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function closeSidebar() {
                    document.body.classList.remove('sidebar-enable');
                    document.querySelectorAll('.leftside-menu-bg, .sidebar-overlay, .offcanvas-backdrop')
                        .forEach(el => el.remove());
                }

                // Klik tombol X
                document.querySelectorAll('.button-close-fullsidebar').forEach(btn => {
                    btn.addEventListener('click', closeSidebar);
                });

                // Klik menu
                document.querySelectorAll('.side-nav-link').forEach(link => {
                    link.addEventListener('click', closeSidebar);
                });

                // Klik overlay (area gelap)
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('leftside-menu-bg') ||
                        e.target.classList.contains('sidebar-overlay') ||
                        e.target.classList.contains('offcanvas-backdrop')) {
                        closeSidebar();
                    }
                });
            });
        </script>

        @yield("scripts")

    </body>

</html>
