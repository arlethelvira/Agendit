<div class="container-fluid">

    <!-- Título -->
    <div class="py-3 d-flex align-items-center justify-content-between">

        <div>
            <h4 class="fs-18 fw-semibold mb-1">
                Vincular especialista
            </h4>

            <p class="text-muted mb-0">
                Genera un código para compartirlo con tus socios.
            </p>
        </div>

    </div>


    <!-- Tarjeta -->
    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card">

                <div class="card-body text-center p-4">

                    <!-- Icono -->
                    <div class="mb-3">

                        <div
                            class="avatar-lg mx-auto d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle"
                        >
                            <i class="bx bx-link fs-36 text-primary"></i>
                        </div>

                    </div>


                    <h4 class="mb-2">
                        Código de vinculación
                    </h4>


                    <p class="text-muted mb-4">
                        Comparte este código con la persona que deseas
                        vincular como socio.
                    </p>


                    <?php if (!empty($codigo)): ?>

                        <!-- Código generado -->
                        <div class="mb-4">

                            <div
                                class="border rounded p-3 bg-light"
                            >

                                <span
                                    class="fs-32 fw-bold text-primary"
                                    style="letter-spacing: 8px;"
                                >
                                    <?= h($codigo) ?>
                                </span>

                            </div>

                        </div>


                        <!-- Información -->
                        <div class="alert alert-info text-start">

                            <i class="bx bx-info-circle me-1"></i>

                            Este código es válido durante
                            <strong>7 días</strong> y solamente puede
                            utilizarse una vez.

                        </div>


                    <?php else: ?>

                        <!-- No hay código todavía -->
                        <div class="alert alert-secondary">

                            Todavía no has generado un código.

                        </div>

                    <?php endif; ?>


                    <!-- Botón -->
                    <div class="mt-4">

                        <form
                            method="post"
                            action="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'generarCodigo'
                            ]) ?>"
                        >

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-plus me-1"></i>

                                Generar nuevo código

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>