<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Welcome')) ?>

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
                <?= $this->element('page-title', array('title' => 'Welcome', 'subTitle' => 'Pages')) ?>

                <!-- Start here.... -->
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