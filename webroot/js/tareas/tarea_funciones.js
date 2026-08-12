const URL_BASE = '/tareas';

document.addEventListener('DOMContentLoaded', () => {

    listarTareas();

    document
        .getElementById('btnNuevaTarea')
        .addEventListener('click', () => abrirModal());

    document
        .getElementById('formTarea')
        .addEventListener('submit', (e) => {
            e.preventDefault();
            guardarTarea();
        });

});


/* =========================================================
   LISTAR TAREAS
========================================================= */

function listarTareas() {

    fetch(`${URL_BASE}/index`)

        .then(res => res.json())

        .then(data => {

            if (data.exito) {

                renderizarTareas(data.datos);

            } else {

                console.error(data.mensaje);

            }

        })

        .catch(err => {

            console.error(
                'Error al listar tareas:',
                err
            );

        });

}


/* =========================================================
   RENDERIZAR TAREAS
========================================================= */

function renderizarTareas(tareas) {

    const contenedor =
        document.getElementById('listaTareas');

    contenedor.innerHTML = '';


    /* ==============================
       SIN TAREAS
    ============================== */

    if (tareas.length === 0) {

        contenedor.innerHTML = `
            <div class="text-center py-5">

                <div class="fs-1 mb-2">
                    📝
                </div>

                <h5 class="mb-1">
                    No tienes tareas registradas
                </h5>

                <p class="text-muted mb-0">
                    Crea una nueva tarea para comenzar a organizarte.
                </p>

            </div>
        `;

        return;
    }


    /* ==============================
       RECORRER TAREAS
    ============================== */

    tareas.forEach(tarea => {

        const asignadaPorEspecialista =
            tarea.id_especialista !== null;

        const completada =
            tarea.fecha_completada !== null;


        const colorCategoria =
            tarea.categoria
                ? tarea.categoria.color
                : '#6c757d';


        const item =
            document.createElement('div');


        item.className =
            'tarjeta-tarea card border-0 shadow-sm' +
            (completada ? ' completada' : '');


        item.style.borderLeft =
            `5px solid ${colorCategoria}`;


        /* ==============================
           FECHA
        ============================== */

        let estadoFecha = '';


        if (tarea.fecha_limite) {

            const fechaLimite =
                new Date(
                    `${tarea.fecha_limite}T00:00:00`
                );


            const hoy =
                new Date();


            hoy.setHours(
                0,
                0,
                0,
                0
            );


            const manana =
                new Date(hoy);


            manana.setDate(
                manana.getDate() + 1
            );


            const opcionesFecha = {
                day: '2-digit',
                month: 'short'
            };


            const fechaTexto =
                fechaLimite.toLocaleDateString(
                    'es-MX',
                    opcionesFecha
                );


            if (!completada) {

                /* VENCIDA */

                if (fechaLimite < hoy) {

                    estadoFecha = `
                        <span
                            class="
                                badge
                                bg-danger-subtle
                                text-danger
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-alert-triangle
                                    me-1
                                "
                            ></i>

                            Vencida · ${fechaTexto}

                        </span>
                    `;

                }

                /* HOY */

                else if (
                    fechaLimite.getTime()
                    ===
                    hoy.getTime()
                ) {

                    estadoFecha = `
                        <span
                            class="
                                badge
                                bg-warning-subtle
                                text-warning
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-calendar-event
                                    me-1
                                "
                            ></i>

                            Hoy · ${fechaTexto}

                        </span>
                    `;

                }

                /* MAÑANA */

                else if (
                    fechaLimite.getTime()
                    ===
                    manana.getTime()
                ) {

                    estadoFecha = `
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
                                    ti-calendar
                                    me-1
                                "
                            ></i>

                            Mañana · ${fechaTexto}

                        </span>
                    `;

                }

                /* PRÓXIMA */

                else {

                    estadoFecha = `
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
                                    ti-calendar
                                    me-1
                                "
                            ></i>

                            ${fechaTexto}

                        </span>
                    `;

                }

            }

        }


        /* ==============================
           HORA
        ============================== */

        let horaTexto = '';


        if (tarea.hora_limite) {

            const hora =
                tarea.hora_limite.substring(
                    0,
                    5
                );


            horaTexto = `
                <span
                    class="
                        text-muted
                        small
                    "
                >

                    <i
                        class="
                            ti
                            ti-clock
                            me-1
                        "
                    ></i>

                    ${hora}

                </span>
            `;

        }


        /* ==============================
           SUBTAREAS
        ============================== */

        let subtareasHtml = '';


        if (
            tarea.subtareas &&
            tarea.subtareas.length > 0
        ) {

            subtareasHtml = `

                <div class="mt-3">

                    <small
                        class="
                            text-muted
                            fw-medium
                        "
                    >
                        Subtareas
                    </small>


                    <div
                        class="
                            lista-subtareas
                            mt-2
                        "
                    >

                        ${tarea.subtareas.map(sub => `

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    gap-2
                                    mb-2
                                "
                            >

                                <input
                                    type="checkbox"

                                    class="
                                        form-check-input
                                        mt-0
                                    "

                                    ${sub.completada
                                        ? 'checked'
                                        : ''
                                    }

                                    onchange="
                                        marcarSubtareaCompletada(
                                            ${sub.id_subtarea},
                                            this.checked
                                        )
                                    "
                                >


                                <span
                                    class="
                                        ${sub.completada
                                            ? 'text-decoration-line-through text-muted'
                                            : ''
                                        }
                                    "
                                >

                                    ${escaparHtml(sub.titulo)}

                                </span>

                            </div>

                        `).join('')}

                    </div>

                </div>

            `;

        }


        /* ==============================
           HTML DE LA TARJETA
        ============================== */

        item.innerHTML = `

            <div class="card-body p-3 p-md-4">

                <div class="d-flex gap-3">


                    <!-- CHECK PRINCIPAL -->

                    <div class="pt-1">

                        <input
                            type="checkbox"

                            class="
                                form-check-input
                                tarea-check-principal
                            "

                            ${completada
                                ? 'checked'
                                : ''
                            }

                            onchange="
                                marcarCompletada(
                                    ${tarea.id_tarea},
                                    this.checked
                                )
                            "
                        >

                    </div>


                    <!-- INFORMACIÓN -->

                    <div class="flex-grow-1">

                        <div
                            class="
                                d-flex
                                flex-column
                                flex-md-row
                                justify-content-between
                                gap-3
                            "
                        >


                            <div class="flex-grow-1">

                                <h5
                                    class="
                                        mb-2

                                        ${completada
                                            ? 'text-decoration-line-through text-muted'
                                            : ''
                                        }
                                    "
                                >

                                    ${escaparHtml(tarea.titulo)}

                                </h5>


                                <!-- FECHA / ESTADO / HORA -->

                                <div
                                    class="
                                        d-flex
                                        flex-wrap
                                        align-items-center
                                        gap-2
                                        mb-2
                                    "
                                >

                                    ${
                                        completada

                                            ? `
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
                                                            ti-circle-check
                                                            me-1
                                                        "
                                                    ></i>

                                                    Completada

                                                </span>
                                            `

                                            : estadoFecha
                                    }


                                    ${horaTexto}

                                </div>


                                <!-- CATEGORÍA -->

                                <div
                                    class="
                                        d-flex
                                        flex-wrap
                                        gap-2
                                    "
                                >

                                    <span
                                        class="
                                            badge
                                            rounded-pill
                                        "

                                        style="
                                            background-color:
                                            ${colorCategoria}20;

                                            color:
                                            ${colorCategoria};

                                            border:
                                            1px solid
                                            ${colorCategoria}40;
                                        "
                                    >

                                        <span
                                            style="
                                                display:inline-block;
                                                width:7px;
                                                height:7px;
                                                border-radius:50%;
                                                background:
                                                ${colorCategoria};
                                                margin-right:5px;
                                            "
                                        ></span>

                                        ${
                                            tarea.categoria

                                                ? escaparHtml(
                                                    tarea.categoria.nombre
                                                )

                                                : 'Sin categoría'
                                        }

                                    </span>


                                    ${
                                        asignadaPorEspecialista

                                            ? `
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
                                                            ti-stethoscope
                                                            me-1
                                                        "
                                                    ></i>

                                                    Asignada por especialista

                                                </span>
                                            `

                                            : ''
                                    }

                                </div>


                                <!-- NOTAS -->

                                ${
                                    tarea.notas

                                        ? `
                                            <p
                                                class="
                                                    text-muted
                                                    small
                                                    mt-2
                                                    mb-0
                                                "
                                            >

                                                ${escaparHtml(tarea.notas)}

                                            </p>
                                        `

                                        : ''
                                }


                                ${subtareasHtml}

                            </div>


                            <!-- ACCIONES -->

                            ${
                                !asignadaPorEspecialista

                                    ? `
                                        <div
                                            class="
                                                d-flex
                                                align-items-start
                                                gap-2
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
                                                    editarTarea(
                                                        ${tarea.id_tarea}
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
                                                    eliminarTarea(
                                                        ${tarea.id_tarea}
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

                                                Borrar

                                            </button>

                                        </div>
                                    `

                                    : ''
                            }

                        </div>

                    </div>

                </div>

            </div>
        `;


        contenedor.appendChild(item);

    });

}


/* =========================================================
   ABRIR MODAL
========================================================= */

function abrirModal(tarea = null) {

    const formulario =
        document.getElementById('formTarea');


    formulario.reset();


    document
        .getElementById('idTarea')
        .value = '';


    document
        .getElementById('listaSubtareasForm')
        .innerHTML = '';


    /* ==============================
       EDITAR
    ============================== */

    if (tarea) {

        document
            .getElementById('modalTitulo')
            .innerText = 'Editar tarea';


        document
            .getElementById('idTarea')
            .value = tarea.id_tarea;


        document
            .getElementById('titulo')
            .value = tarea.titulo || '';


        document
            .getElementById('fechaLimite')
            .value = tarea.fecha_limite || '';


        document
            .getElementById('horaLimite')
            .value = normalizarHora(
                tarea.hora_limite
            );


        document
            .getElementById('notas')
            .value = tarea.notas || '';


        document
            .getElementById('horaRecordatorio')
            .value = normalizarHora(
                tarea.hora_recordatorio
            );


        document
            .getElementById('idCategoria')
            .value = tarea.id_categoria || '';


        if (
            tarea.subtareas &&
            tarea.subtareas.length > 0
        ) {

            tarea.subtareas.forEach(sub => {

                agregarCampoSubtarea(
                    sub.titulo
                );

            });

        }

    }

    /* ==============================
       NUEVA
    ============================== */

    else {

        document
            .getElementById('modalTitulo')
            .innerText = 'Nueva tarea';

    }


    document
        .getElementById('modalTarea')
        .style.display = 'flex';

}


/* =========================================================
   CERRAR MODAL
========================================================= */

function cerrarModal() {

    document
        .getElementById('modalTarea')
        .style.display = 'none';

}


/* =========================================================
   EDITAR TAREA
========================================================= */

function editarTarea(id_tarea) {

    fetch(
        `${URL_BASE}/ver/${id_tarea}`
    )

        .then(res => res.json())

        .then(data => {

            if (data.exito) {

                abrirModal(data.datos);

            } else {

                mostrarMensaje(
                    data.mensaje ||
                    'No fue posible obtener la tarea.',
                    'error'
                );

            }

        })

        .catch(err => {

            console.error(
                'Error al obtener tarea:',
                err
            );

            mostrarMensaje(
                'Ocurrió un error al obtener la tarea.',
                'error'
            );

        });

}


/* =========================================================
   GUARDAR TAREA
========================================================= */

function guardarTarea() {

    const id_tarea =
        document
            .getElementById('idTarea')
            .value;


    const url =
        id_tarea

            ? `${URL_BASE}/editar/${id_tarea}`

            : `${URL_BASE}/agregar`;


    const payload = {

        titulo:
            document
                .getElementById('titulo')
                .value
                .trim(),

        fecha_limite:
            document
                .getElementById('fechaLimite')
                .value,

        hora_limite:
            document
                .getElementById('horaLimite')
                .value,

        notas:
            document
                .getElementById('notas')
                .value
                .trim(),

        hora_recordatorio:
            document
                .getElementById('horaRecordatorio')
                .value,

        id_categoria:
            document
                .getElementById('idCategoria')
                .value,

        subtareas:
            obtenerSubtareasDelForm()

    };


    if (!payload.titulo) {

        mostrarMensaje(
            'El título de la tarea es obligatorio.',
            'warning'
        );

        return;
    }


    fetch(
        url,
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify(payload)
        }
    )

        .then(res => res.json())

        .then(data => {

            if (data.exito) {

                cerrarModal();

                listarTareas();

                mostrarMensaje(
                    id_tarea
                        ? 'Tarea actualizada correctamente.'
                        : 'Tarea creada correctamente.',
                    'success'
                );

            } else {

                mostrarMensaje(
                    data.mensaje ||
                    'No fue posible guardar la tarea.',
                    'error'
                );

            }

        })

        .catch(err => {

            console.error(
                'Error al guardar tarea:',
                err
            );

            mostrarMensaje(
                'Ocurrió un error al guardar la tarea.',
                'error'
            );

        });

}


/* =========================================================
   ELIMINAR TAREA
========================================================= */

/* =========================================================
   ELIMINAR TAREA
========================================================= */

let tareaPendienteEliminar = null;


function eliminarTarea(id_tarea) {

    /*
     * Guardamos temporalmente qué tarea
     * quiere eliminar el usuario.
     */
    tareaPendienteEliminar = id_tarea;


    /*
     * Mostramos nuestro modal personalizado.
     */
    document
        .getElementById('modalEliminarTarea')
        .style.display = 'flex';

}


/* =========================================================
   CERRAR MODAL DE ELIMINACIÓN
========================================================= */

function cerrarModalEliminar() {

    document
        .getElementById('modalEliminarTarea')
        .style.display = 'none';


    tareaPendienteEliminar = null;

}


/* =========================================================
   CONFIRMAR ELIMINACIÓN
========================================================= */

document
    .getElementById('btnConfirmarEliminar')
    .addEventListener('click', () => {

        /*
         * Si por alguna razón no existe
         * una tarea seleccionada, detenemos.
         */
        if (!tareaPendienteEliminar) {
            return;
        }


        const id_tarea =
            tareaPendienteEliminar;


        /*
         * Desactivamos el botón mientras
         * se realiza la petición.
         */
        const boton =
            document.getElementById(
                'btnConfirmarEliminar'
            );


        boton.disabled = true;


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


        fetch(
            `${URL_BASE}/eliminar/${id_tarea}`,
            {
                method: 'POST'
            }
        )

            .then(res => res.json())

            .then(data => {

                if (data.exito) {

                    /*
                     * Cerramos el modal.
                     */
                    cerrarModalEliminar();


                    /*
                     * Actualizamos la lista.
                     */
                    listarTareas();


                    /*
                     * Mostramos aviso bonito.
                     */
                    mostrarMensaje(
                        'Tarea eliminada correctamente.',
                        'success'
                    );

                } else {

                    mostrarMensaje(
                        data.mensaje ||
                        'No fue posible eliminar la tarea.',
                        'error'
                    );

                }

            })

            .catch(err => {

                console.error(
                    'Error al eliminar tarea:',
                    err
                );


                mostrarMensaje(
                    'Ocurrió un error al eliminar la tarea.',
                    'error'
                );

            })

            .finally(() => {

                /*
                 * Restauramos el botón.
                 */
                boton.disabled = false;


                boton.innerHTML = `
                    <i class="ti ti-trash me-1"></i>
                    Eliminar
                `;

            });

    });


/* =========================================================
   MARCAR TAREA COMO COMPLETADA
========================================================= */

function marcarCompletada(
    id_tarea,
    completada
) {

    fetch(
        `${URL_BASE}/marcar-completada/${id_tarea}`,
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify({
                    completada
                })
        }
    )

        .then(res => res.json())

        .then(data => {

            if (data.exito === false) {

                mostrarMensaje(
                    data.mensaje ||
                    'No fue posible actualizar la tarea.',
                    'error'
                );

            }

            listarTareas();

        })

        .catch(err => {

            console.error(
                'Error al marcar tarea:',
                err
            );

            mostrarMensaje(
                'Ocurrió un error al actualizar la tarea.',
                'error'
            );

        });

}


/* =========================================================
   AGREGAR CAMPO DE SUBTAREA
========================================================= */

function agregarCampoSubtarea(
    valor = ''
) {

    const contenedor =
        document
            .getElementById(
                'listaSubtareasForm'
            );


    const fila =
        document.createElement('div');


    fila.className =
        'fila-subtarea d-flex align-items-center gap-2 mb-2';


    fila.innerHTML = `

        <input
            type="text"

            class="
                input-subtarea
                form-control
            "

            value="${escaparAtributo(valor)}"

            placeholder="
                Título de la subtarea
            "

            maxlength="50"
        >


        <button
            type="button"

            class="
                btn
                btn-outline-danger
            "

            onclick="
                this.parentElement.remove()
            "

            title="
                Quitar subtarea
            "
        >

            <i class="ti ti-x"></i>

        </button>

    `;


    contenedor.appendChild(fila);

}


/* =========================================================
   OBTENER SUBTAREAS DEL FORMULARIO
========================================================= */

function obtenerSubtareasDelForm() {

    const inputs =
        document.querySelectorAll(
            '.input-subtarea'
        );


    const subtareas = [];


    inputs.forEach(input => {

        const valor =
            input.value.trim();


        if (valor !== '') {

            subtareas.push(valor);

        }

    });


    return subtareas;

}


/* =========================================================
   MARCAR SUBTAREA COMO COMPLETADA
========================================================= */

function marcarSubtareaCompletada(
    id_subtarea,
    completada
) {

    fetch(
        `${URL_BASE}/marcar-subtarea-completada/${id_subtarea}`,
        {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify({
                    completada
                })
        }
    )

        .then(res => res.json())

        .then(data => {

            if (data.exito === false) {

                mostrarMensaje(
                    data.mensaje ||
                    'No fue posible actualizar la subtarea.',
                    'error'
                );

            }

            listarTareas();

        })

        .catch(err => {

            console.error(
                'Error al marcar subtarea:',
                err
            );

            mostrarMensaje(
                'Ocurrió un error al actualizar la subtarea.',
                'error'
            );

        });

}


/* =========================================================
   MENSAJES BONITOS
========================================================= */

/*
 * Esta función crea una alerta visual
 * dentro de la página.
 *
 * No utiliza alert() del navegador.
 */
function mostrarMensaje(
    mensaje,
    tipo = 'success'
) {

    let clase = 'alert-success';
    let icono = 'ti-circle-check';


    if (tipo === 'error') {

        clase = 'alert-danger';
        icono = 'ti-circle-x';

    }


    if (tipo === 'warning') {

        clase = 'alert-warning';
        icono = 'ti-alert-triangle';

    }


    const alerta =
        document.createElement('div');


    alerta.className = `
        alert
        ${clase}
        alert-dismissible
        fade
        show
        shadow-sm
        position-fixed
    `;


    alerta.style.top = '85px';
    alerta.style.right = '25px';
    alerta.style.zIndex = '10000';
    alerta.style.minWidth = '300px';
    alerta.style.maxWidth = '420px';


    alerta.innerHTML = `

        <div
            class="
                d-flex
                align-items-center
            "
        >

            <i
                class="
                    ti
                    ${icono}
                    fs-20
                    me-2
                "
            ></i>

            <div class="flex-grow-1">

                ${escaparHtml(mensaje)}

            </div>


            <button
                type="button"
                class="btn-close"
                aria-label="Cerrar"
            ></button>

        </div>

    `;


    alerta
        .querySelector('.btn-close')
        .addEventListener(
            'click',
            () => alerta.remove()
        );


    document.body.appendChild(
        alerta
    );


    setTimeout(
        () => {

            if (alerta.parentElement) {

                alerta.remove();

            }

        },
        3500
    );

}


/* =========================================================
   FUNCIONES AUXILIARES
========================================================= */

/*
 * CakePHP devuelve las horas como:
 *
 * 09:49:00
 *
 * Pero input[type="time"] funciona mejor
 * con:
 *
 * 09:49
 */
function normalizarHora(hora) {

    if (!hora) {
        return '';
    }

    return hora.substring(0, 5);

}


/*
 * Escapa texto antes de insertarlo en HTML.
 *
 * Evita problemas si el usuario escribe
 * caracteres como:
 *
 * < > & "
 */
function escaparHtml(texto) {

    if (
        texto === null ||
        texto === undefined
    ) {

        return '';

    }


    const div =
        document.createElement('div');


    div.textContent =
        String(texto);


    return div.innerHTML;

}


/*
 * Escape especial para valores
 * dentro de atributos HTML.
 */
function escaparAtributo(texto) {

    return escaparHtml(texto)
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}