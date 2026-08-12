const HABITOS_URL = '/habitos';

let habitoPendienteEliminar = null;


/* =========================================================
   INICIO
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    cargarHabitos();


    const btnNuevo =
        document.getElementById('btnNuevoHabito');

    const formulario =
        document.getElementById('formHabito');


    if (btnNuevo) {

        btnNuevo.addEventListener(
            'click',
            () => abrirModalHabito()
        );

    }


    if (formulario) {

        formulario.addEventListener(
            'submit',
            (e) => {

                e.preventDefault();

                guardarHabito();

            }
        );

    }


    const btnEliminar =
        document.getElementById(
            'btnConfirmarEliminarHabito'
        );


    if (btnEliminar) {

        btnEliminar.addEventListener(
            'click',
            confirmarEliminarHabito
        );

    }

});


/* =========================================================
   CSRF DE CAKEPHP
========================================================= */

function getCsrfToken() {

    const match =
        document.cookie.match(
            /csrfToken=([^;]+)/
        );


    return match
        ? decodeURIComponent(match[1])
        : null;

}


/* =========================================================
   FETCH JSON
========================================================= */

function fetchJson(
    url,
    options = {}
) {

    const token =
        getCsrfToken();


    const headers = {

        'Accept': 'application/json',

        'Content-Type': 'application/json',

        ...(token
            ? {
                'X-CSRF-Token': token
            }
            : {}),

        ...(options.headers || {})

    };


    return fetch(
        url,
        {
            ...options,
            headers
        }
    )

        .then(async response => {

            const data =
                await response.json();


            if (!response.ok) {

                throw data;

            }


            return data;

        });

}


/* =========================================================
   CARGAR HÁBITOS
========================================================= */

function cargarHabitos() {

    /*
     * IMPORTANTE:
     *
     * /habitos ahora es la vista HTML.
     * /habitos/index devuelve el JSON.
     */
    fetchJson(
        `${HABITOS_URL}/index`
    )

        .then(data => {

            if (data.ok) {

                renderizarHabitos(
                    data.data || []
                );

            } else {

                mostrarMensajeHabito(
                    'No fue posible cargar los hábitos.',
                    'error'
                );

            }

        })

        .catch(error => {

            console.error(
                'Error al cargar hábitos:',
                error
            );


            mostrarMensajeHabito(
                'Ocurrió un error al cargar tus hábitos.',
                'error'
            );

        });

}


/* =========================================================
   RENDERIZAR HÁBITOS
========================================================= */

function renderizarHabitos(habitos) {

    const contenedor =
        document.getElementById(
            'listaHabitos'
        );


    if (!contenedor) {
        return;
    }


    contenedor.innerHTML = '';


    /* =====================================================
       SIN HÁBITOS
    ====================================================== */

    if (habitos.length === 0) {

        contenedor.innerHTML = `

            <div
                class="text-center py-5"
                style="grid-column: 1 / -1;"
            >

                <div class="fs-1 mb-2">
                    🌱
                </div>

                <h5 class="mb-1">
                    Aún no tienes hábitos
                </h5>

                <p class="text-muted mb-0">
                    Crea uno para comenzar a construir tu rutina.
                </p>

            </div>

        `;

        return;

    }


    /* =====================================================
       TARJETAS
    ====================================================== */

    habitos.forEach(habito => {

        const asignadoEspecialista =
            habito.creado_por ===
            'ESPECIALISTA';


        const color =
            obtenerColorHabito(
                habito.color
            );


        const tarjeta =
            document.createElement(
                'div'
            );


        tarjeta.className =
            'tarjeta-habito card border-0 shadow-sm h-100';


        tarjeta.style.borderTop =
            `4px solid ${color}`;


        tarjeta.innerHTML = `

            <div class="card-body p-4">

                <div
                    class="
                        d-flex
                        justify-content-between
                        gap-3
                    "
                >

                    <!-- =====================================
                         INFORMACIÓN
                    ====================================== -->

                    <div
                        class="
                            d-flex
                            gap-3
                            flex-grow-1
                        "
                    >

                        <!-- Indicador de color -->

                        <div
                            class="habito-indicador mt-2"
                            style="
                                background-color: ${color};
                            "
                        ></div>


                        <div class="flex-grow-1">

                            <!-- Título -->

                            <h5 class="mb-2">

                                ${escaparHtmlHabito(
                                    habito.titulo
                                )}

                            </h5>


                            <!-- Etiquetas -->

                            <div
                                class="
                                    d-flex
                                    flex-wrap
                                    gap-2
                                    mb-3
                                "
                            >

                                <span
                                    class="
                                        badge
                                        bg-success-subtle
                                        text-success
                                    "
                                >

                                    <i
                                        class="
                                            ti
                                            ti-repeat
                                            me-1
                                        "
                                    ></i>

                                    ${escaparHtmlHabito(
                                        formatearFrecuencia(
                                            habito.frecuencia
                                        )
                                    )}

                                </span>


                                ${
                                    asignadoEspecialista

                                        ? `
                                            <span
                                                class="
                                                    badge
                                                    bg-info-subtle
                                                    text-info
                                                "
                                            >

                                                <i
                                                    class="
                                                        ti
                                                        ti-stethoscope
                                                        me-1
                                                    "
                                                ></i>

                                                Asignado por especialista

                                            </span>
                                        `

                                        : `
                                            <span
                                                class="
                                                    badge
                                                    bg-secondary-subtle
                                                    text-secondary
                                                "
                                            >

                                                <i
                                                    class="
                                                        ti
                                                        ti-user
                                                        me-1
                                                    "
                                                ></i>

                                                Creado por ti

                                            </span>
                                        `
                                }

                            </div>


                            <!-- Notas -->

                            ${
                                habito.notas

                                    ? `
                                        <p
                                            class="
                                                text-muted
                                                small
                                                mb-0
                                            "
                                        >

                                            ${escaparHtmlHabito(
                                                habito.notas
                                            )}

                                        </p>
                                    `

                                    : `
                                        <p
                                            class="
                                                text-muted
                                                small
                                                mb-0
                                            "
                                        >
                                            Sin notas adicionales.
                                        </p>
                                    `
                            }

                        </div>

                    </div>


                    <!-- =====================================
                         ACCIONES
                    ====================================== -->

                    ${
                        !asignadoEspecialista

                            ? `
                                <div
                                    class="
                                        d-flex
                                        gap-2
                                        align-items-start
                                        flex-wrap
                                    "
                                >

                                    <button
                                        type="button"

                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-secondary
                                        "

                                        onclick="
                                            editarHabito(
                                                ${habito.id_habito}
                                            )
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-pencil
                                                me-1
                                            "
                                        ></i>

                                        Editar

                                    </button>


                                    <button
                                        type="button"

                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-danger
                                        "

                                        onclick="
                                            eliminarHabito(
                                                ${habito.id_habito}
                                            )
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-trash
                                                me-1
                                            "
                                        ></i>

                                        Eliminar

                                    </button>

                                </div>
                            `

                            : ''
                    }

                </div>

            </div>

        `;


        contenedor.appendChild(
            tarjeta
        );

    });

}


/* =========================================================
   ABRIR MODAL NUEVO / EDITAR
========================================================= */

function abrirModalHabito(
    habito = null
) {

    const formulario =
        document.getElementById(
            'formHabito'
        );


    if (!formulario) {
        return;
    }


    formulario.reset();


    document
        .getElementById('idHabito')
        .value = '';


    /* =====================================================
       EDITAR
    ====================================================== */

    if (habito) {

        document
            .getElementById(
                'modalHabitoTitulo'
            )
            .innerText =
                'Editar hábito';


        document
            .getElementById(
                'idHabito'
            )
            .value =
                habito.id_habito;


        document
            .getElementById(
                'habitoTitulo'
            )
            .value =
                habito.titulo || '';


        document
            .getElementById(
                'habitoFrecuencia'
            )
            .value =
                habito.frecuencia || '';


        document
            .getElementById(
                'habitoColor'
            )
            .value =
                habito.color ||
                'bg-success';


        document
            .getElementById(
                'habitoNotas'
            )
            .value =
                habito.notas || '';

    }

    /* =====================================================
       NUEVO
    ====================================================== */

    else {

        document
            .getElementById(
                'modalHabitoTitulo'
            )
            .innerText =
                'Nuevo hábito';


        document
            .getElementById(
                'habitoColor'
            )
            .value =
                'bg-success';

    }


    document
        .getElementById(
            'modalHabito'
        )
        .style.display =
            'flex';

}


/* =========================================================
   CERRAR MODAL
========================================================= */

function cerrarModalHabito() {

    const modal =
        document.getElementById(
            'modalHabito'
        );


    if (modal) {

        modal.style.display =
            'none';

    }

}


/* =========================================================
   OBTENER HÁBITO PARA EDITAR
========================================================= */

function editarHabito(
    idHabito
) {

    fetchJson(
        `${HABITOS_URL}/view/${idHabito}`
    )

        .then(data => {

            if (data.ok) {

                /*
                 * Seguridad visual:
                 * los hábitos del especialista
                 * no deben editarse.
                 */
                if (
                    data.data.creado_por ===
                    'ESPECIALISTA'
                ) {

                    mostrarMensajeHabito(
                        'Este hábito fue asignado por tu especialista y no puede modificarse.',
                        'warning'
                    );

                    return;

                }


                abrirModalHabito(
                    data.data
                );

            }

            else {

                mostrarMensajeHabito(
                    'No fue posible obtener el hábito.',
                    'error'
                );

            }

        })

        .catch(error => {

            console.error(
                'Error al obtener hábito:',
                error
            );


            mostrarMensajeHabito(
                'Ocurrió un error al obtener el hábito.',
                'error'
            );

        });

}


/* =========================================================
   GUARDAR HÁBITO
========================================================= */

function guardarHabito() {

    const idHabito =
        document
            .getElementById(
                'idHabito'
            )
            .value;


    const titulo =
        document
            .getElementById(
                'habitoTitulo'
            )
            .value
            .trim();


    const frecuencia =
        document
            .getElementById(
                'habitoFrecuencia'
            )
            .value;


    const color =
        document
            .getElementById(
                'habitoColor'
            )
            .value;


    const notas =
        document
            .getElementById(
                'habitoNotas'
            )
            .value
            .trim();


    /* =====================================================
       VALIDACIÓN
    ====================================================== */

    if (!titulo) {

        mostrarMensajeHabito(
            'El título del hábito es obligatorio.',
            'warning'
        );

        return;

    }


    if (!frecuencia) {

        mostrarMensajeHabito(
            'Selecciona una frecuencia.',
            'warning'
        );

        return;

    }


    const payload = {

        titulo,
        frecuencia,
        color,
        notas

    };


    const url =
        idHabito

            ? `${HABITOS_URL}/edit/${idHabito}`

            : `${HABITOS_URL}/add`;


    const metodo =
        idHabito

            ? 'PUT'

            : 'POST';


    const botonGuardar =
        document.querySelector(
            '#formHabito button[type="submit"]'
        );


    let contenidoAnterior =
        'Guardar';


    if (botonGuardar) {

        contenidoAnterior =
            botonGuardar.innerHTML;


        botonGuardar.disabled =
            true;


        botonGuardar.innerHTML = `

            <span
                class="
                    spinner-border
                    spinner-border-sm
                    me-1
                "
            ></span>

            Guardando...

        `;

    }


    /* =====================================================
       PETICIÓN
    ====================================================== */

    fetchJson(
        url,
        {
            method: metodo,

            body:
                JSON.stringify(
                    payload
                )
        }
    )

        .then(data => {

            if (data.ok) {

                cerrarModalHabito();

                cargarHabitos();


                mostrarMensajeHabito(

                    idHabito

                        ? 'Hábito actualizado correctamente.'

                        : 'Hábito creado correctamente.',

                    'success'

                );

            }

            else {

                mostrarMensajeHabito(
                    'No fue posible guardar el hábito.',
                    'error'
                );

            }

        })

        .catch(error => {

            console.error(
                'Error al guardar hábito:',
                error
            );


            mostrarMensajeHabito(
                obtenerMensajeErrorHabito(
                    error
                ),
                'error'
            );

        })

        .finally(() => {

            if (botonGuardar) {

                botonGuardar.disabled =
                    false;


                botonGuardar.innerHTML =
                    contenidoAnterior;

            }

        });

}


/* =========================================================
   ABRIR CONFIRMACIÓN DE ELIMINACIÓN
========================================================= */

function eliminarHabito(
    idHabito
) {

    habitoPendienteEliminar =
        idHabito;


    const modal =
        document.getElementById(
            'modalEliminarHabito'
        );


    if (modal) {

        modal.style.display =
            'flex';

    }

}


/* =========================================================
   CERRAR CONFIRMACIÓN DE ELIMINACIÓN
========================================================= */

function cerrarModalEliminarHabito() {

    const modal =
        document.getElementById(
            'modalEliminarHabito'
        );


    if (modal) {

        modal.style.display =
            'none';

    }


    habitoPendienteEliminar =
        null;

}


/* =========================================================
   CONFIRMAR ELIMINACIÓN
========================================================= */

function confirmarEliminarHabito() {

    if (!habitoPendienteEliminar) {
        return;
    }


    const idHabito =
        habitoPendienteEliminar;


    const boton =
        document.getElementById(
            'btnConfirmarEliminarHabito'
        );


    let contenidoAnterior =
        'Eliminar';


    if (boton) {

        contenidoAnterior =
            boton.innerHTML;


        boton.disabled =
            true;


        boton.innerHTML = `

            <span
                class="
                    spinner-border
                    spinner-border-sm
                    me-1
                "
            ></span>

            Eliminando...

        `;

    }


    fetchJson(
        `${HABITOS_URL}/delete/${idHabito}`,
        {
            method: 'DELETE'
        }
    )

        .then(data => {

            if (data.ok) {

                cerrarModalEliminarHabito();

                cargarHabitos();


                mostrarMensajeHabito(
                    'Hábito eliminado correctamente.',
                    'success'
                );

            }

            else {

                mostrarMensajeHabito(
                    'No fue posible eliminar el hábito.',
                    'error'
                );

            }

        })

        .catch(error => {

            console.error(
                'Error al eliminar hábito:',
                error
            );


            mostrarMensajeHabito(
                'Ocurrió un error al eliminar el hábito.',
                'error'
            );

        })

        .finally(() => {

            if (boton) {

                boton.disabled =
                    false;


                boton.innerHTML =
                    contenidoAnterior;

            }

        });

}


/* =========================================================
   MENSAJES BONITOS
========================================================= */

function mostrarMensajeHabito(
    mensaje,
    tipo = 'success'
) {

    let clase =
        'alert-success';


    let icono =
        'ti-circle-check';


    if (tipo === 'error') {

        clase =
            'alert-danger';

        icono =
            'ti-circle-x';

    }


    if (tipo === 'warning') {

        clase =
            'alert-warning';

        icono =
            'ti-alert-triangle';

    }


    const alerta =
        document.createElement(
            'div'
        );


    alerta.className = `
        alert
        ${clase}
        shadow-sm
        position-fixed
        fade
        show
    `;


    alerta.style.top =
        '85px';


    alerta.style.right =
        '25px';


    alerta.style.zIndex =
        '11000';


    alerta.style.minWidth =
        '300px';


    alerta.style.maxWidth =
        '420px';


    alerta.innerHTML = `

        <div
            class="
                d-flex
                align-items-center
                gap-2
            "
        >

            <i
                class="
                    ti
                    ${icono}
                    fs-20
                "
            ></i>


            <div
                class="flex-grow-1"
            >

                ${escaparHtmlHabito(
                    mensaje
                )}

            </div>


            <button
                type="button"
                class="btn-close"
                aria-label="Cerrar"
            ></button>

        </div>

    `;


    const botonCerrar =
        alerta.querySelector(
            '.btn-close'
        );


    if (botonCerrar) {

        botonCerrar.addEventListener(
            'click',
            () => alerta.remove()
        );

    }


    document.body.appendChild(
        alerta
    );


    setTimeout(
        () => {

            if (
                alerta.parentElement
            ) {

                alerta.remove();

            }

        },
        3500
    );

}


/* =========================================================
   COLOR DEL HÁBITO
========================================================= */

function obtenerColorHabito(
    clase
) {

    const colores = {

        'bg-success':
            '#198754',

        'bg-primary':
            '#0d6efd',

        'bg-warning':
            '#ffc107',

        'bg-danger':
            '#dc3545',

        'bg-purple':
            '#6f42c1',

        'bg-secondary':
            '#6c757d'

    };


    return colores[clase]
        || '#198754';

}


/* =========================================================
   MOSTRAR FRECUENCIA BONITA
========================================================= */

function formatearFrecuencia(
    frecuencia
) {

    if (!frecuencia) {

        return 'Sin frecuencia';

    }


    const valores = {

        'diaria':
            'Diaria',

        'cada 2 dias':
            'Cada 2 días',

        'cada 2 días':
            'Cada 2 días',

        'cada 3 dias':
            'Cada 3 días',

        'cada 3 días':
            'Cada 3 días',

        'semanal':
            'Semanal',

        'mensual':
            'Mensual'

    };


    const clave =
        frecuencia
            .toLowerCase()
            .trim();


    return valores[clave]
        || frecuencia;

}


/* =========================================================
   EXTRAER MENSAJES DE ERROR DE CAKEPHP
========================================================= */

function obtenerMensajeErrorHabito(
    error
) {

    if (!error) {

        return 'No fue posible guardar el hábito.';

    }


    if (
        typeof error.error ===
        'string'
    ) {

        return error.error;

    }


    /*
     * CakePHP puede devolver:
     *
     * {
     *   error: {
     *      titulo: {
     *         _empty: "El título..."
     *      }
     *   }
     * }
     */
    if (
        error.error &&
        typeof error.error ===
        'object'
    ) {

        for (
            const campo
            in error.error
        ) {

            const erroresCampo =
                error.error[campo];


            if (
                typeof erroresCampo ===
                'object'
            ) {

                const primerError =
                    Object.values(
                        erroresCampo
                    )[0];


                if (primerError) {

                    return String(
                        primerError
                    );

                }

            }

        }

    }


    return 'No fue posible guardar el hábito.';

}


/* =========================================================
   ESCAPAR HTML
========================================================= */

function escaparHtmlHabito(
    texto
) {

    if (
        texto === null ||
        texto === undefined
    ) {

        return '';

    }


    const div =
        document.createElement(
            'div'
        );


    div.textContent =
        String(texto);


    return div.innerHTML;

}