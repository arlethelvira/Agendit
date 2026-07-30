<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => '404')) ?>

    <?= $this->element('head-css') ?>
</head>

<body>
    <!-- START Wrapper -->
    <div class="wrapper">
        <?= $this->element('menu') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">
            <!-- Start Container -->
            <div class="container-xxl">
                <?= $this->element('page-title', array('title' => '404', 'subTitle' => 'Pages')) ?>

                <div class="row justify-content-center">
                    <div class="col-xl-5">
                        <div class="card">
                            <div class="card-body px-3 py-5">
                                <div class="p-4">
                                    <div class="mx-auto mb-4 text-center">
                                        <h1 class="mb-3 fw-bold fs-60">
                                            404
                                        </h1>
                                        <h2 class="fs-22 lh-base">
                                            Page Not Found !
                                        </h2>
                                        <p class="text-muted mt-1 mb-4">
                                            The page you're trying to reach
                                            seems to have gone <br />
                                            missing in the digital
                                            wilderness.
                                        </p>

                                        <div class="text-center">
                                            <a href="/" class="btn btn-success">Back to Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- End Container -->

            <?= $this->element('footer') ?>
        </div>
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->
    </div>
    <!-- END Wrapper -->

    <?= $this->element('vendor-scripts') ?>
</body>

</html>