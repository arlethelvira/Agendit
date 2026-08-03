<?php
/**
 * Layout para autenticación.
 *
 * Este layout será utilizado únicamente
 * por las pantallas de:
 *
 * - Iniciar sesión
 * - Registro de usuario
 * - Registro de especialista
 *
 * A diferencia del layout principal,
 * aquí NO mostramos:
 *
 * - Menú lateral
 * - Barra superior
 * - Footer
 *
 * De esta forma las pantallas de acceso
 * lucen limpias y profesionales.
 */
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <?= $this->element('title-meta', [
        'title' => $this->fetch('title') ?: 'Agendit'
    ]) ?>

    <?= $this->element('head-css') ?>

</head>

<body class="authentication-bg">

    <!--
        Aquí únicamente se carga
        el contenido de la vista.
    -->
    <?= $this->fetch('content') ?>

    <?= $this->Flash->render() ?>

    <?= $this->element('vendor-scripts') ?>

</body>

</html>