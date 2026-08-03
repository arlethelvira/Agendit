<!doctype html>
<html lang="es">

<head>
    <?= $this->element('title-meta', ['title' => 'Registro']) ?>
    <?= $this->element('head-css') ?>
</head>

<body class="authentication-bg">

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-5">

                <div class="card auth-card">

                    <div class="card-body px-3 py-5">

                        <!-- Logo -->
                        <div class="mx-auto mb-4 text-center auth-logo">

                            <a href="/" class="logo-dark">
                                <img src="/images/logoAgendit.png" height="30" class="me-1" alt="logo">
                                <img src="/images/letrasAgendit.png" height="24" alt="logo">
                            </a>

                            <a href="/" class="logo-light">
                                <img src="/images/logogendit.png" height="30" class="me-1" alt="logo">
                                <img src="/images/letrasAgendit.png" height="24" alt="logo">
                            </a>

                        </div>

                        <h2 class="fw-bold text-center fs-18">
                            Únete a Agendit
                        </h2>

                        <p class="text-muted text-center mt-1 mb-4">
                            Crea tu cuenta para comenzar a organizar tus tareas,
                            hábitos y alcanzar tus objetivos.
                        </p>

                        <div class="px-4">

                            <?= $this->Form->create($usuario, [
                                'class' => 'authentication-form',
                            ]) ?>

                            <!-- Nombre -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Nombre
                                </label>

                                <?= $this->Form->control('nombre', [
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingresa tu nombre'
                                ]) ?>

                            </div>

                            <!-- Apellido paterno -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Apellido paterno
                                </label>

                                <?= $this->Form->control('apellido_paterno', [
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingresa tu apellido paterno'
                                ]) ?>

                            </div>

                            <!-- Apellido materno -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Apellido materno
                                </label>

                                <?= $this->Form->control('apellido_materno', [
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingresa tu apellido materno'
                                ]) ?>

                            </div>

                            <!-- Correo -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Correo electrónico
                                </label>

                                <?= $this->Form->control('email', [
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingresa tu correo electrónico'
                                ]) ?>

                            </div>

                            <!-- Contraseña -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Contraseña
                                </label>

                                <?= $this->Form->control('contrasena', [
                                    'type' => 'password',
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => 'Crea una contraseña'
                                ]) ?>

                            </div>

                            <!-- Botón -->
                            <div class="d-grid">

                                <?= $this->Form->button(
                                    'Crear mi cuenta',
                                    [
                                        'class' => 'btn btn-primary'
                                    ]
                                ) ?>

                            </div>

                            <?= $this->Form->end() ?>

                        </div>

                    </div>

                </div>

                <p class="text-white text-center mt-3 mb-1">

                    ¿Ya tienes una cuenta?

                    <a href="/users/login"
                       class="text-white fw-bold">

                        Iniciar sesión

                    </a>

                </p>

                <p class="text-white text-center">

                    ¿Eres especialista?

                    <a href="/users/registro-especialista"
                       class="text-white fw-bold">

                        Registrar cuenta de especialista

                    </a>

                </p>

            </div>

        </div>

    </div>
</div>

<?= $this->element('vendor-scripts') ?>

</body>
</html>