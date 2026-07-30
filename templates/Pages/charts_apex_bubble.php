<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Apex Bubble Charts')) ?>

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
                <?= $this->element('page-title', array('title' => 'Bubble Charts', 'subTitle' => 'Charts')) ?>

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
                                        ../src//js/components/apexchart-bubble.js</code>
                                </p>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title anchor" id="simple">
                                    Simple Bubble Chart
                                </h4>
                                <div dir="ltr">
                                    <div id="simple-bubble" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title anchor" id="3d-bubble">
                                    3D Bubble Chart
                                </h4>
                                <div dir="ltr">
                                    <div id="second-bubble" class="apex-charts"></div>
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
                                    <a href="#simple" class="nav-link">Simple Bubble Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#3d-bubble" class="nav-link">3D Bubble Chart</a>
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

    <!-- Apex Chart Bubble Demo js -->
    <script src="/js/components/apexchart-bubble.js"></script>
</body>

</html>