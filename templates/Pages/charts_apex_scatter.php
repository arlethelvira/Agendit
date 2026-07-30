<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Apex Scatter Charts')) ?>

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
                <?= $this->element('page-title', array('title' => 'Scatter Charts', 'subTitle' => 'Charts')) ?>

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
                                        ../src/js/components/apexchart-scatter.js</code>
                                </p>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title anchor" id="basic">
                                    Scatter (XY) Chart
                                </h4>
                                <div dir="ltr">
                                    <div id="basic-scatter" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title anchor" id="datetime">
                                    Scatter Chart - Datetime
                                </h4>
                                <div dir="ltr">
                                    <div id="datetime-scatter" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title anchor" id="images">
                                    Scatter - Images
                                </h4>
                                <div dir="ltr">
                                    <div id="scatter-images" class="apex-charts scatter-images-chart"></div>
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
                                    <a href="#basic" class="nav-link">Scatter (XY) Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#datetime" class="nav-link">Scatter Chart - Datetime</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#images" class="nav-link">Scatter - Images</a>
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

    <!-- Apex Chart Scatter Demo js -->
    <script src="/js/components/apexchart-scatter.js"></script>
</body>

</html>