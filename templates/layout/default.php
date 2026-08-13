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
<script>
document.addEventListener('DOMContentLoaded', function () {

    const alertas =
        document.querySelectorAll('.agendit-alert');

    alertas.forEach(function (alerta) {

        setTimeout(function () {

            alerta.style.opacity = '0';
            alerta.style.transform = 'translateX(30px)';
            alerta.style.transition = '0.3s ease';

            setTimeout(function () {
                alerta.remove();
            }, 300);

        }, 4000);

    });

});
</script>
</body>

</html>