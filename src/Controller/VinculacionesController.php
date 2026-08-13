<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Controlador encargado del sistema
 * de vinculación entre especialistas y socios.
 *
 * Seguridad:
 *
 * ESPECIALISTA:
 * - generarCodigo()
 * - misSocios()
 * - agendaSocio()
 * - cambiarEstado()
 *
 * USUARIO:
 * - ingresarCodigo()
 * - validarCodigo()
 */
class VinculacionesController extends AppController
{
    /**
     * Modelo encargado de los códigos de invitación.
     */
    private $CodigoInvitacion;

    /**
     * Modelo encargado de las vinculaciones.
     */
    private $Vinculaciones;

    /**
     * Inicialización del controlador.
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Modelo de códigos de invitación.
         */
        $this->CodigoInvitacion =
            $this->fetchTable('CodigoInvitacion');

        /**
         * Modelo de vinculaciones.
         */
        $this->Vinculaciones =
            $this->fetchTable('Vinculaciones');
    }


    /**
     * ==========================================================
     * INGRESAR CÓDIGO
     * ==========================================================
     *
     * Esta pantalla es para el USUARIO.
     *
     * El usuario escribe el código que recibió
     * de un especialista.
     */
    public function ingresarCodigo()
    {
        /**
         * Obtenemos los datos del usuario
         * que inició sesión.
         */
        $usuario = $this->usuarioActual();

        /**
         * Verificamos que el usuario tenga
         * el rol correcto.
         */
        if ($usuario['rol'] !== 'usuario') {

            $this->Flash->error(
                'No tienes permiso para ingresar códigos de invitación.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * La vista solamente muestra
         * el formulario.
         */
    }


    /**
     * ==========================================================
     * GENERAR CÓDIGO
     * ==========================================================
     *
     * Esta acción solamente puede ser utilizada
     * por un ESPECIALISTA.
     */
    public function generarCodigo()
    {
        /**
         * Obtenemos el usuario que inició sesión.
         */
        $usuario = $this->usuarioActual();

        /**
         * SEGURIDAD:
         *
         * Solamente un especialista puede
         * generar códigos.
         */
        if ($usuario['rol'] !== 'especialista') {

            $this->Flash->error(
                'No tienes permiso para generar códigos.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Si solamente entramos a la página,
         * mostramos la vista sin generar código.
         */
        if (!$this->request->is('post')) {
            return;
        }

        /**
         * ID del usuario actual.
         */
        $idUsuario = $usuario['id_usuario'];

        /**
         * Buscamos el perfil de especialista.
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' => $idUsuario
            ])
            ->first();

        /**
         * Verificamos que exista el perfil.
         */
        if (!$especialista) {

            $this->Flash->error(
                'No se encontró el perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * ID del especialista.
         */
        $idEspecialista = $especialista->id_especialista;

        /**
         * Generamos un código aleatorio
         * de 6 caracteres.
         */
        $codigoGenerado = strtoupper(
            substr(bin2hex(random_bytes(4)), 0, 6)
        );

        /**
         * Creamos la entidad.
         */
        $codigo = $this->CodigoInvitacion
            ->newEmptyEntity();

        /**
         * Relacionamos el código
         * con el especialista.
         */
        $codigo->id_especialista = $idEspecialista;

        /**
         * Guardamos el código.
         */
        $codigo->codigo = $codigoGenerado;

        /**
         * El código expira en 7 días.
         */
        $codigo->fecha_expiracion =
            FrozenTime::now()->addDays(7);

        /**
         * Todavía no ha sido utilizado.
         */
        $codigo->usado = false;

        /**
         * Estado inicial.
         */
        $codigo->estado = 'ACTIVO';

        /**
         * Guardamos en la base de datos.
         */
        if ($this->CodigoInvitacion->save($codigo)) {

            $this->Flash->success(
                'Código generado correctamente.'
            );

            /**
             * Mandamos el código a la vista.
             */
            $this->set([
                'codigo' => $codigoGenerado
            ]);

        } else {

            $this->Flash->error(
                'No se pudo generar el código.'
            );
        }
    }


    /**
     * ==========================================================
     * VALIDAR CÓDIGO
     * ==========================================================
     *
     * Esta acción solamente puede ser ejecutada
     * por un USUARIO.
     *
     * Recibe el código enviado desde el formulario,
     * comprueba que sea válido y crea la vinculación.
     *
     * IMPORTANTE:
     *
     * En lugar de utilizar Flash + redirect para los
     * resultados de la validación, aquí enviamos
     * directamente una variable $alert a la vista.
     *
     * Esto permite utilizar SweetAlert2.
     */
    public function validarCodigo()
    {
        /**
         * Obtenemos el usuario actual.
         */
        $usuario = $this->usuarioActual();

        /**
         * SEGURIDAD:
         *
         * Solamente un usuario normal puede
         * validar un código.
         */
        if ($usuario['rol'] !== 'usuario') {

            $this->Flash->error(
                'No tienes permiso para validar códigos.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Esta acción solamente debe recibir
         * información mediante POST.
         */
        if (!$this->request->is('post')) {

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }

        /**
         * Obtenemos el código enviado.
         */
        $codigoIngresado = trim(
            (string)$this->request->getData('codigo')
        );

        /**
         * Convertimos el código a mayúsculas
         * para evitar problemas con minúsculas.
         */
        $codigoIngresado = strtoupper($codigoIngresado);

        /**
         * Validamos que el campo no esté vacío.
         */
        if ($codigoIngresado === '') {

            $this->set([
                'alert' => [
                    'icon' => 'warning',
                    'title' => 'Código requerido',
                    'text' => 'Por favor, ingresa un código de invitación.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * Validamos que tenga exactamente
         * 6 caracteres.
         */
        if (strlen($codigoIngresado) !== 6) {

            $this->set([
                'alert' => [
                    'icon' => 'warning',
                    'title' => 'Código inválido',
                    'text' => 'El código debe tener exactamente 6 caracteres.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * Buscamos el código en la tabla
         * codigo_invitacion.
         */
        $codigo = $this->CodigoInvitacion
            ->find()
            ->where([
                'codigo' => $codigoIngresado
            ])
            ->first();

        /**
         * ======================================================
         * CÓDIGO NO EXISTE
         * ======================================================
         */
        if (!$codigo) {

            $this->set([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Código no encontrado',
                    'text' => 'El código ingresado no existe. Verifica que lo hayas escrito correctamente.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * CÓDIGO YA UTILIZADO
         * ======================================================
         */
        if ($codigo->usado === true) {

            $this->set([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Código ya utilizado',
                    'text' => 'Este código ya fue utilizado por otro usuario y ya no puede volver a utilizarse.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * CÓDIGO INACTIVO
         * ======================================================
         */
        if ($codigo->estado !== 'ACTIVO') {

            $this->set([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'Código no disponible',
                    'text' => 'Este código ya no está disponible para realizar una vinculación.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * CÓDIGO EXPIRADO
         * ======================================================
         */
        if (
            $codigo->fecha_expiracion !== null &&
            FrozenTime::now() > $codigo->fecha_expiracion
        ) {

            /**
             * Cambiamos el estado del código.
             */
            $codigo->estado = 'EXPIRADO';

            $this->CodigoInvitacion->save($codigo);

            $this->set([
                'alert' => [
                    'icon' => 'warning',
                    'title' => 'Código expirado',
                    'text' => 'Este código de invitación ya expiró. Solicita uno nuevo a tu especialista.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * USUARIO ACTUAL
         * ======================================================
         */
        $idUsuario = $usuario['id_usuario'];

        /**
         * ======================================================
         * VERIFICAR VINCULACIÓN EXISTENTE
         * ======================================================
         *
         * Evitamos que el mismo usuario tenga
         * una segunda vinculación activa con
         * el mismo especialista.
         */
        $vinculacionExistente = $this->Vinculaciones
            ->find()
            ->where([
                'id_usuario' => $idUsuario,
                'id_especialista' => $codigo->id_especialista,
                'estado' => 'ACTIVA'
            ])
            ->first();

        if ($vinculacionExistente) {

            $this->set([
                'alert' => [
                    'icon' => 'info',
                    'title' => 'Ya estás vinculado',
                    'text' => 'Ya tienes una vinculación activa con este especialista.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * CREAR VINCULACIÓN
         * ======================================================
         */
        $vinculacion =
            $this->Vinculaciones->newEmptyEntity();

        /**
         * Usuario que está utilizando
         * el código.
         */
        $vinculacion->id_usuario =
            $idUsuario;

        /**
         * Especialista dueño
         * del código.
         */
        $vinculacion->id_especialista =
            $codigo->id_especialista;

        /**
         * Fecha de inicio.
         */
        $vinculacion->fecha_inicio =
            FrozenTime::now();

        /**
         * Estado inicial.
         */
        $vinculacion->estado =
            'ACTIVA';

        /**
         * Guardamos la vinculación.
         */
        if (!$this->Vinculaciones->save($vinculacion)) {

            /**
             * No modificamos el código
             * si la vinculación falló.
             */
            $this->set([
                'alert' => [
                    'icon' => 'error',
                    'title' => 'No se pudo vincular',
                    'text' => 'Ocurrió un problema al crear la vinculación. Inténtalo nuevamente.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * MARCAR CÓDIGO COMO UTILIZADO
         * ======================================================
         */
        $codigo->usado = true;
        $codigo->estado = 'USADO';

        /**
         * Si por alguna razón no se puede actualizar
         * el código, mostramos un error.
         */
        if (!$this->CodigoInvitacion->save($codigo)) {

            $this->set([
                'alert' => [
                    'icon' => 'warning',
                    'title' => 'Vinculación realizada',
                    'text' => 'La vinculación se creó correctamente, pero ocurrió un problema al actualizar el estado del código.'
                ]
            ]);

            return $this->render('ingresarCodigo');
        }

        /**
         * ======================================================
         * ÉXITO
         * ======================================================
         */
        $this->set([
            'alert' => [
                'icon' => 'success',
                'title' => '¡Vinculación exitosa!',
                'text' => 'Te vinculaste correctamente con tu especialista.'
            ]
        ]);

        return $this->render('ingresarCodigo');
    }


    /**
     * ==========================================================
     * MIS SOCIOS
     * ==========================================================
     *
     * Esta sección solamente puede ser utilizada
     * por un ESPECIALISTA.
     */
    public function misSocios()
    {
        /**
         * Obtenemos el usuario actual.
         */
        $usuario = $this->usuarioActual();

        /**
         * SEGURIDAD:
         *
         * Solamente un especialista puede
         * consultar sus socios.
         */
        if ($usuario['rol'] !== 'especialista') {

            $this->Flash->error(
                'No tienes permiso para acceder a tus socios.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * ID del usuario actual.
         */
        $idUsuario =
            $usuario['id_usuario'];

        /**
         * Buscamos el especialista relacionado.
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' => $idUsuario
            ])
            ->first();

        /**
         * Verificamos que exista el perfil.
         */
        if (!$especialista) {

            $this->Flash->error(
                'No se encontró el perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * ID del especialista.
         */
        $idEspecialista =
            $especialista->id_especialista;

        /**
         * Buscamos las vinculaciones
         * pertenecientes al especialista.
         */
        $socios = $this->Vinculaciones
            ->find()
            ->contain([
                'Usuarios'
            ])
            ->where([
                'Vinculaciones.id_especialista' =>
                    $idEspecialista
            ])
            ->all();

        /**
         * Enviamos los socios a la vista.
         */
        $this->set(compact('socios'));
    }


    /**
     * ==========================================================
     * AGENDA DEL SOCIO
     * ==========================================================
     *
     * Muestra el calendario del socio vinculado,
     * respetando privacidad.
     */
    public function agendaSocio($idUsuario = null)
    {
        /**
         * Usuario actual.
         */
        $usuario = $this->usuarioActual();

        /**
         * Seguridad.
         */
        if ($usuario['rol'] !== 'especialista') {

            $this->Flash->error(
                'No tienes permiso para acceder a esta sección.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Buscamos el especialista.
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' =>
                    $usuario['id_usuario']
            ])
            ->first();

        /**
         * Verificamos que exista.
         */
        if (!$especialista) {

            $this->Flash->error(
                'No se encontró el perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Verificamos que el socio realmente
         * esté vinculado con este especialista.
         */
        $vinculacion = $this->Vinculaciones
            ->find()
            ->contain([
                'Usuarios'
            ])
            ->where([
                'Vinculaciones.id_usuario' =>
                    (int)$idUsuario,

                'Vinculaciones.id_especialista' =>
                    $especialista->id_especialista,

                'Vinculaciones.estado' =>
                    'ACTIVA'
            ])
            ->first();

        /**
         * Si no existe la vinculación.
         */
        if (!$vinculacion) {

            $this->Flash->error(
                'Este socio no está vinculado contigo.'
            );

            return $this->redirect([
                'action' => 'misSocios'
            ]);
        }

        /**
         * Enviamos los datos a la vista.
         */
        $this->set([
            'idSocio' =>
                $vinculacion->id_usuario,

            'nombreSocio' =>
                $vinculacion->usuario->nombre .
                ' ' .
                $vinculacion->usuario->apellido_paterno,
        ]);
    }

    /**
     * ==========================================================
     * PROGRESO DEL SOCIO
     * ==========================================================
     *
     * El especialista solamente puede consultar
     * las tareas y hábitos que ÉL MISMO asignó
     * al socio.
     *
     * No mostramos actividades personales
     * creadas por el socio.
     */
    public function progresoSocio($idUsuario = null)
    {
        /*
         * ======================================================
         * USUARIO ACTUAL
         * ======================================================
         */
        $usuario = $this->usuarioActual();


        /*
         * ======================================================
         * SEGURIDAD
         * ======================================================
         */
        if (
            !$usuario ||
            $usuario['rol'] !== 'especialista'
        ) {

            $this->Flash->error(
                'No tienes permiso para consultar el progreso de un socio.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        /*
         * ======================================================
         * VALIDAR ID DEL SOCIO
         * ======================================================
         */
        if ($idUsuario === null) {

            $this->Flash->error(
                'No se especificó el socio.'
            );

            return $this->redirect([
                'action' => 'misSocios'
            ]);
        }


        $idUsuario = (int)$idUsuario;


        /*
         * ======================================================
         * BUSCAR ESPECIALISTA
         * ======================================================
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' =>
                    (int)$usuario['id_usuario']
            ])
            ->first();


        if (!$especialista) {

            $this->Flash->error(
                'No se encontró tu perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        $idEspecialista =
            (int)$especialista->id_especialista;


        /*
         * ======================================================
         * VERIFICAR VINCULACIÓN
         * ======================================================
         */
        $vinculacion = $this->Vinculaciones
            ->find()
            ->contain([
                'Usuarios'
            ])
            ->where([
                'Vinculaciones.id_usuario' =>
                    $idUsuario,

                'Vinculaciones.id_especialista' =>
                    $idEspecialista,

                'Vinculaciones.estado' =>
                    'ACTIVA'
            ])
            ->first();


        if (!$vinculacion) {

            $this->Flash->error(
                'Este socio no está vinculado contigo o la vinculación está inactiva.'
            );

            return $this->redirect([
                'action' => 'misSocios'
            ]);
        }


        /*
         * Datos del socio.
         */
        $socio =
            $vinculacion->usuario;


        /*
         * ======================================================
         * TAREAS ASIGNADAS POR ESTE ESPECIALISTA
         * ======================================================
         *
         * IMPORTANTE:
         *
         * No buscamos todas las tareas del socio.
         * Solo buscamos aquellas donde
         * id_especialista sea el especialista actual.
         */
        $Tareas =
            $this->fetchTable('Tareas');


        $tareas = $Tareas
            ->find()
            ->contain([
                'Categorias'
            ])
            ->where([
                'Tareas.id_usuario' =>
                    $idUsuario,

                'Tareas.id_especialista' =>
                    $idEspecialista,

                'Tareas.estado' =>
                    'activa'
            ])
            ->orderBy([
                'Tareas.fecha_limite' =>
                    'DESC',

                'Tareas.hora_limite' =>
                    'ASC'
            ])
            ->all();


        /*
         * ======================================================
         * ESTADÍSTICAS DE TAREAS
         * ======================================================
         */
        $totalTareas =
            $tareas->count();


        /*
         * Completadas.
         */
        $tareasCompletadas =
            $tareas->filter(
                function ($tarea) {

                    return
                        !empty(
                            $tarea->fecha_completada
                        );
                }
            );


        /*
         * Pendientes.
         */
        $tareasPendientes =
            $tareas->filter(
                function ($tarea) {

                    return
                        empty(
                            $tarea->fecha_completada
                        );
                }
            );


        /*
         * ======================================================
         * TAREAS VENCIDAS
         * ======================================================
         */
        $hoy =
            date('Y-m-d');


        $tareasVencidas =
            $tareas->filter(
                function ($tarea) use ($hoy) {

                    if (
                        !empty(
                            $tarea->fecha_completada
                        )
                    ) {

                        return false;
                    }


                    if (
                        empty(
                            $tarea->fecha_limite
                        )
                    ) {

                        return false;
                    }


                    return
                        $tarea
                            ->fecha_limite
                            ->format('Y-m-d')
                        < $hoy;
                }
            );


        /*
         * Totales.
         */
        $totalCompletadas =
            $tareasCompletadas->count();


        $totalPendientes =
            $tareasPendientes->count();


        $totalVencidas =
            $tareasVencidas->count();


        /*
         * Como TODAS las tareas cargadas
         * fueron asignadas por este especialista,
         * este total es igual al total de tareas.
         */
        $totalAsignadasPorMi =
            $totalTareas;


        /*
         * ======================================================
         * PORCENTAJE DE PROGRESO
         * ======================================================
         */
        $porcentajeTareas = 0;


        if ($totalTareas > 0) {

            $porcentajeTareas =
                (int)round(
                    (
                        $totalCompletadas
                        /
                        $totalTareas
                    )
                    * 100
                );
        }


        /*
         * ======================================================
         * TAREAS RECIENTES
         * ======================================================
         */
        $tareasRecientes =
            $tareas->take(5);


        /*
         * ======================================================
         * HÁBITOS ASIGNADOS POR ESTE ESPECIALISTA
         * ======================================================
         *
         * Tampoco mostramos hábitos personales
         * creados por el socio.
         */
        $Habitos =
            $this->fetchTable('Habitos');


        $habitos = $Habitos
            ->find()
            ->where([
                'Habitos.id_usuario' =>
                    $idUsuario,

                'Habitos.id_especialista' =>
                    $idEspecialista,

                'Habitos.creado_por' =>
                    'ESPECIALISTA'
            ])
            ->orderBy([
                'Habitos.fecha_creacion' =>
                    'DESC'
            ])
            ->all();


        $totalHabitos =
            $habitos->count();


        /*
         * Todos los hábitos cargados
         * fueron asignados por este especialista.
         */
        $totalHabitosAsignadosPorMi =
            $totalHabitos;


        /*
         * ======================================================
         * PORCENTAJE DE CUMPLIMIENTO DE HÁBITOS
         * ======================================================
         *
         * Igual que con tareas, solo consideramos
         * los hábitos que este especialista asignó.
         *
         * Por cada hábito calculamos cuántas
         * ocurrencias esperadas hay hasta hoy
         * (según su frecuencia), y cuántas de esas
         * están marcadas como completadas en
         * registro_habito.
         */
        $RegistroHabitos =
            $this->fetchTable('RegistroHabitos');

        $pasos = [
            'diaria' => 1,
            'cada 2 dias' => 2,
            'cada 3 dias' => 3,
            'semanal' => 7,
        ];

        $resumenHabitos = [];
        $totalEsperadasHabitos = 0;
        $totalCompletadasHabitos = 0;

        foreach ($habitos as $habito) {

            $fechaInicio = new \DateTime(
                $habito->fecha_creacion->format('Y-m-d')
            );

            $fechaHoy = new \DateTime($hoy);

            $frecuencia = strtolower($habito->frecuencia);
            $esperadas = [];

            if ($frecuencia === 'mensual') {

                $cursor = clone $fechaInicio;

                while ($cursor <= $fechaHoy) {
                    $esperadas[] = $cursor->format('Y-m-d');
                    $cursor->modify('+1 month');
                }

            } else {

                $paso = $pasos[$frecuencia] ?? 1;
                $cursor = clone $fechaInicio;

                while ($cursor <= $fechaHoy) {
                    $esperadas[] = $cursor->format('Y-m-d');
                    $cursor->modify("+{$paso} days");
                }
            }

            $completadas = $RegistroHabitos
                ->find()
                ->where([
                    'id_habito' => $habito->id_habito,
                    'fecha IN' => $esperadas,
                    'completado' => true
                ])
                ->count();

            $totalEsp = count($esperadas);
            $porcentajeHabito = $totalEsp > 0
                ? (int)round(($completadas / $totalEsp) * 100)
                : 0;

            $resumenHabitos[] = [
            'id_habito' => $habito->id_habito,
            'titulo' => $habito->titulo,
            'esperadas' => $totalEsp,
            'completadas' => $completadas,
            'porcentaje' => $porcentajeHabito
        ];

            $totalEsperadasHabitos += $totalEsp;
            $totalCompletadasHabitos += $completadas;
        }

        $porcentajeHabitos = $totalEsperadasHabitos > 0
            ? (int)round(($totalCompletadasHabitos / $totalEsperadasHabitos) * 100)
            : 0;


        /*
         * ======================================================
         * ENVIAR DATOS A LA VISTA
         * ======================================================
         */
        $this->set(compact(
            'socio',
            'vinculacion',
            'especialista',

            'tareas',
            'tareasCompletadas',
            'tareasPendientes',
            'tareasVencidas',
            'tareasRecientes',

            'totalTareas',
            'totalCompletadas',
            'totalPendientes',
            'totalVencidas',
            'totalAsignadasPorMi',
            'porcentajeTareas',

            'habitos',
            'totalHabitos',
            'totalHabitosAsignadosPorMi',

            'resumenHabitos',
            'porcentajeHabitos'
        ));
    }

    /**
     * ==========================================================
     * CAMBIAR ESTADO DE UN SOCIO
     * ==========================================================
     *
     * Permite al especialista activar o desactivar
     * una vinculación existente.
     */
    public function cambiarEstado($id = null)
    {
        /**
         * Obtenemos el usuario actual.
         */
        $usuario = $this->usuarioActual();

        /**
         * Seguridad.
         */
        if ($usuario['rol'] !== 'especialista') {

            $this->Flash->error(
                'No tienes permiso para realizar esta acción.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Verificamos que exista el ID.
         */
        if ($id === null) {

            $this->Flash->error(
                'No se especificó la vinculación.'
            );

            return $this->redirect([
                'action' => 'misSocios'
            ]);
        }

        /**
         * Buscamos el especialista actual.
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' =>
                    $usuario['id_usuario']
            ])
            ->first();

        /**
         * Verificamos que exista el perfil.
         */
        if (!$especialista) {

            $this->Flash->error(
                'No se encontró el perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /**
         * Buscamos la vinculación.
         *
         * IMPORTANTE:
         * También verificamos que pertenezca
         * al especialista actual.
         */
        $vinculacion = $this->Vinculaciones
            ->find()
            ->where([
                'Vinculaciones.id_vinculacion' =>
                    (int)$id,

                'Vinculaciones.id_especialista' =>
                    $especialista->id_especialista
            ])
            ->first();

        /**
         * Si no existe o no pertenece
         * al especialista.
         */
        if (!$vinculacion) {

            $this->Flash->error(
                'No se encontró la vinculación.'
            );

            return $this->redirect([
                'action' => 'misSocios'
            ]);
        }

        /**
         * Cambiamos el estado.
         */
        if ($vinculacion->estado === 'ACTIVA') {

            $vinculacion->estado = 'INACTIVA';

            $mensaje =
                'El socio ha sido dado de baja correctamente.';

        } else {

            $vinculacion->estado = 'ACTIVA';

            $mensaje =
                'El socio ha sido activado nuevamente.';
        }

        /**
         * Guardamos el cambio.
         */
        if ($this->Vinculaciones->save($vinculacion)) {

            $this->Flash->success($mensaje);

        } else {

            $this->Flash->error(
                'No se pudo actualizar el estado del socio.'
            );
        }

        /**
         * Regresamos a Mis socios.
         */
        return $this->redirect([
            'action' => 'misSocios'
        ]);
    }
}