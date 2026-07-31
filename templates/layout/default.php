<?php
/**
 * Layout principal de Agendit
 * Todas las vistas heredarán esta estructura.
 */
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <?= $this->element('title-meta', ['title' => $this->fetch('title') ?: 'Agendit']) ?>

    <?= $this->element('head-css') ?>

</head>

<body>

    <div class="wrapper">

        <?= $this->element('menu') ?>

        <div class="page-content">

            <?= $this->fetch('content') ?>

            <?= $this->element('footer') ?>

        </div>

    </div>

    <?= $this->Flash->render() ?>

    <?= $this->element('vendor-scripts') ?>

</body>

</html>