<?php
$this->assign('title', 'Dashboard');
?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Dashboard',
        'subTitle' => 'Agendit'
    ]) ?>

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h2>Bienvenida a Agendit </h2>

                    <p>La plantilla ya está funcionando con CakePHP.
                        -en src/controller agreguen sus controller..ej. UsuariosController.php


-en template/ agreguen nombre de los modulos 
es decir 
template/Usuarios/
Todavía vacías.

Solo para dejar el proyecto organizado.

-Cake php separa controller model y templates...es un controller model view


---templates/element/main-nav.php ES EL SIDEBAAR
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>