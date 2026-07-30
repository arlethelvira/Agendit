<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Apex Boxplot Chart')) ?>

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
                <?= $this->element('page-title', array('title' => 'Boxplot Charts', 'subTitle' => 'Charts')) ?>

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="overview">
                                    Overview
                                </h5>

                                <p class="mb-0">
                                    <span class="fw-medium">Find the JS file for the following
                                        chart at:</span>
                                    <code>
                                        ../src//js/components/apexchart-boxplot.js</code>
                                </p>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="basic">
                                    Basic Boxplot
                                </h4>
                                <div dir="ltr">
                                    <div id="basic-boxplot" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="scatter">
                                    Scatter Boxplot
                                </h4>
                                <div dir="ltr">
                                    <div id="scatter-boxplot" class="apex-charts"></div>
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
                                    <a href="#basic" class="nav-link">Basic Boxplot</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#scatter" class="nav-link">Scatter Boxplot</a>
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

    <!-- Apex Chart Boxplot Demo js -->
    <script src="/js/components/apexchart-boxplot.js"></script>
</body>

</html>