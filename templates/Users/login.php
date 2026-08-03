<!doctype html>
<html lang="es">

<head>

    <?= $this->element('title-meta', ['title' => 'Iniciar sesión']) ?>

    <?= $this->element('head-css') ?>

</head>

<body class="authentication-bg">

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10">

                <div class="card auth-card">

                    <div class="card-body p-0">

                        <div class="row align-items-center g-0">

                            <!-- Imagen lateral -->
                            <div class="col-lg-6 d-none d-lg-inline-block border-end">

                                <div class="auth-page-sidebar">

                                    <img
                                        src="/images/logoAgendit.png"
                                        class="img-fluid"
                                        alt="Login Agendit"
                                    >

                                </div>

                            </div>

                            <!-- Formulario -->
                            <div class="col-lg-6">

                                <div class="p-4">

                                    <!-- Logo -->
                                    <div class="mx-auto mb-4 text-center auth-logo">

                                        <a href="/" class="logo-dark">

                                            <img
                                                src="/images/logoAgendit.png"
                                                height="30"
                                                class="me-1"
                                                alt="Agendit"
                                            >

                                            <img
                                                src="/images/letrasAgendit.png"
                                                height="24"
                                                alt="Agendit"
                                            >

                                        </a>

                                        <a href="/" class="logo-light">

                                            <img
                                                src="/images/logoAgendit.png"
                                                height="30"
                                                class="me-1"
                                                alt="Agendit"
                                            >

                                            <img
                                                src="/images/letrasAgendit.png"
                                                height="24"
                                                alt="Agendit"
                                            >

                                        </a>

                                    </div>

                                    <!-- Título -->

                                    <h2 class="fw-bold text-center fs-18">

                                        Bienvenido a Agendit

                                    </h2>

                                    <p class="text-muted text-center mt-2 mb-4">

                                        Inicia sesión para continuar organizando
                                        tus tareas, hábitos y objetivos.

                                    </p>

                                    <div class="row justify-content-center">

                                        <div class="col-12 col-md-9">

                                            <?= $this->Flash->render() ?>

                                            <?= $this->Form->create(null, [

                                                'class' => 'authentication-form'

                                            ]) ?>

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

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Contraseña

                                                </label>

                                                <?= $this->Form->control('contrasena', [

                                                    'type' => 'password',

                                                    'label' => false,

                                                    'class' => 'form-control',

                                                    'placeholder' => 'Ingresa tu contraseña'

                                                ]) ?>

                                            </div>

                                            <!-- Recordarme -->

                                            <div class="mb-3">

                                                <div class="form-check">

                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        id="recordarme"
                                                    >

                                                    <label
                                                        class="form-check-label"
                                                        for="recordarme"
                                                    >

                                                        Recordarme

                                                    </label>

                                                </div>

                                            </div>

                                            <!-- Botón -->

                                            <div class="d-grid mb-3">

                                                <?= $this->Form->button(

                                                    'Iniciar sesión',

                                                    [

                                                        'class' => 'btn btn-primary'

                                                    ]

                                                ) ?>

                                            </div>

                                            <?= $this->Form->end() ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Crear cuenta -->

                <p class="text-white text-center mb-1">

                    ¿Aún no tienes una cuenta?

                    <a
                        href="/users/register"
                        class="text-white fw-bold ms-1"
                    >

                        Crear cuenta

                    </a>

                </p>

                <!-- Especialista -->

                <p class="text-white text-center">

                    ¿Eres especialista?

                    <a
                        href="/users/registro-especialista"
                        class="text-white fw-bold ms-1"
                    >

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