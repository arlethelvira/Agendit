<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Apex Polar Area Charts')) ?>

    <?= $this->element('head-css') ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <?= $this->element('menu') ?>

        <!-- ==================================================== -->
        <!-- Start Page Content here -->
        <!-- ==================================================== -->
        <div class="page-content">
            <!-- Start Content-->
            <div class="container-xxl">
                <?= $this->element('page-title', array('title' => 'Polar Area Charts', 'subTitle' => 'Charts')) ?>

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-1" id="overview">
                                    Overview
                                </h5>

                                <p class="mb-0">
                                    <span class="fw-medium">Find the JS file for the following
                                        chart at:</span>
                                    <code>
                                        ../src/js/components/apexchart-polar-area.js</code>
                                </p>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="basic">
                                    Basic Polar Area Chart
                                </h4>
                                <div dir="ltr">
                                    <div id="basic-polar-area" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="monochrome">
                                    Monochrome Polar Area
                                </h4>
                                <div dir="ltr">
                                    <div id="monochrome-polar-area" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#overview" class="nav-link">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#basic" class="nav-link">Basic Polar Area Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#monochrome" class="nav-link">Monochrome Polar Area</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- container -->

            <?= $this->element('footer') ?>
        </div>
        <!-- ==================================================== -->
        <!-- End Page content -->
        <!-- ==================================================== -->
    </div>
    <!-- END wrapper -->

    <?= $this->element('vendor-scripts') ?>

    <!-- Apex Chart Polar Area Demo js -->
    <script src="/js/components/apexchart-polar-area.js"></script>
</body>

</html>