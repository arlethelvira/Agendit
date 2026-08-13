<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\HabitosTable;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;

/**
 * Habitos Controller
 *
 * Controlador encargado de administrar
 * los hábitos del usuario.
 *
 * También permite que un especialista
 * asigne hábitos a sus socios vinculados.
 */
class HabitosController extends AppController
{
    /**
     * Tabla Habitos.
     */
    private HabitosTable $Habitos;


    /**
     * ==========================================================
     * INITIALIZE
     * ==========================================================
     */
    public function initialize(): void
    {
        parent::initialize();

        /*
         * Cargamos explícitamente la tabla Habitos.
         */
        $this->Habitos =
            $this->fetchTable('Habitos');


        /*
         * Estas acciones muestran una vista HTML.
         *
         * El resto funciona como JSON para
         * JavaScript/AJAX.
         */
        $accionesHtml = [
            'vista',
            'calendario',
            'asignar',
            'progreso'
        ];


        if (
            !in_array(
                $this->request->getParam('action'),
                $accionesHtml,
                true
            )
        ) {
            $this->viewBuilder()
                ->setClassName('Json');
        }
    }


    /**
     * ==========================================================
     * SEGURIDAD
     * ==========================================================
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);


        /*
         * Usuario de la sesión.
         */
        $usuario = $this->usuarioActual();


        /*
         * Si no existe sesión,
         * AppController ya se encarga.
         */
        if (!$usuario) {
            return;
        }


        $accion =
            $this->request->getParam('action');


        /*
         * ======================================================
         * ASIGNAR
         *
         * Solo especialista.
         * ======================================================
         */
        if ($accion === 'asignar') {

            if (
                ($usuario['rol'] ?? null)
                !== 'especialista'
            ) {

                $this->Flash->error(
                    'No tienes permiso para asignar hábitos.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }

        }

        /*
         * ======================================================
         * PROGRESO
         *
         * Especialista (viendo a un socio) o
         * usuario/socio (viendo lo propio).
         * La validación fina ocurre dentro
         * de progreso().
         * ======================================================
         */
        elseif ($accion === 'progreso') {

            if (
                !in_array(
                    $usuario['rol'] ?? null,
                    ['usuario', 'especialista'],
                    true
                )
            ) {

                $this->Flash->error(
                    'No tienes permiso para acceder a esta sección.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }

        }

        /*
         * ======================================================
         * RESTO DEL MÓDULO
         *
         * Solo usuario/socio.
         * ======================================================
         */
        else {

            if (
                ($usuario['rol'] ?? null)
                !== 'usuario'
            ) {

                $this->Flash->error(
                    'No tienes permiso para acceder a los hábitos.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }
        }


        /*
         * Métodos utilizados por el módulo.
         */
        $this->request->allowMethod([
            'get',
            'post',
            'put',
            'delete'
        ]);
    }


    /**
     * ==========================================================
     * VISTA PRINCIPAL
     * ==========================================================
     *
     * /habitos
     *
     * La página obtiene los hábitos mediante
     * JavaScript desde /habitos/index.
     */
    public function vista(): void
    {
        /*
         * No necesitamos cargar datos aquí.
         */
    }


    /**
     * ==========================================================
     * CALENDARIO
     * ==========================================================
     */
    public function calendario(): void
    {
        /*
         * El calendario carga sus hábitos
         * mediante JavaScript.
         */
    }


    /**
     * ==========================================================
     * ASIGNAR HÁBITO A UN SOCIO
     * ==========================================================
     *
     * Exclusivo para especialistas.
     */
    public function asignar($idUsuario = null)
    {
        $this->request->allowMethod([
            'get',
            'post'
        ]);


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
                'controller' => 'Vinculaciones',
                'action' => 'misSocios'
            ]);
        }


        $idUsuario =
            (int)$idUsuario;


        /*
         * ======================================================
         * ESPECIALISTA EN SESIÓN
         * ======================================================
         */

        $usuario =
            $this->usuarioActual();


        $idUsuarioSesion =
            (int)$usuario['id_usuario'];


        /*
         * Buscamos el registro real
         * de especialista.
         */
        $especialista =
            TableRegistry::getTableLocator()
                ->get('Especialistas')
                ->find()
                ->where([
                    'id_usuario' =>
                        $idUsuarioSesion
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
            (int)$especialista
                ->id_especialista;


        /*
         * ======================================================
         * COLOR SEGÚN ESPECIALIDAD
         * ======================================================
         *
         * 1 = Nutriólogo
         * 2 = Coach
         * 3 = Psicólogo
         */

        $coloresPorTipo = [
            1 => 'bg-success',
            2 => 'bg-primary',
            3 => 'bg-purple'
        ];


        $colorHabito =
            $coloresPorTipo[
                (int)$especialista->id_tipo
            ]
            ?? 'bg-secondary';


        /*
         * ======================================================
         * VERIFICAR VINCULACIÓN
         * ======================================================
         */

        $vinculacion =
            TableRegistry::getTableLocator()
                ->get('Vinculaciones')
                ->find()
                ->where([
                    'id_especialista' =>
                        $idEspecialista,

                    'id_usuario' =>
                        $idUsuario,

                    'estado' =>
                        'ACTIVA'
                ])
                ->first();


        /*
         * Solo puede asignar hábitos
         * a socios vinculados activamente.
         */
        if (!$vinculacion) {

            $this->Flash->error(
                'Este socio no está vinculado contigo o la vinculación está inactiva.'
            );

            return $this->redirect([
                'controller' => 'Vinculaciones',
                'action' => 'misSocios'
            ]);
        }


        /*
         * ======================================================
         * GET
         * ======================================================
         *
         * Solo mostramos el formulario.
         */

        if ($this->request->is('get')) {

            $this->set([
                'idUsuario' =>
                    $idUsuario
            ]);

            return;
        }


        /*
         * ======================================================
         * POST
         * ======================================================
         *
         * Construimos primero TODOS los datos.
         *
         * Esto es importante porque HabitosTable
         * valida id_usuario, titulo, frecuencia
         * y color.
         */

        $datos =
            $this->request->getData();


        /*
         * Estos valores NO los decide
         * el formulario.
         *
         * Los controla el servidor.
         */
        $datos['id_usuario'] =
            $idUsuario;


        $datos['id_especialista'] =
            $idEspecialista;


        $datos['creado_por'] =
            'ESPECIALISTA';


        $datos['color'] =
            $colorHabito;


        /*
         * ======================================================
         * CREAR ENTIDAD
         * ======================================================
         */

        $habito =
            $this->Habitos
                ->newEmptyEntity();


        /*
         * Ahora patchEntity recibe también
         * los campos obligatorios que nosotros
         * acabamos de agregar.
         */
        $habito =
            $this->Habitos
                ->patchEntity(
                    $habito,
                    $datos
                );


        /*
         * ======================================================
         * GUARDAR
         * ======================================================
         */

if ($this->Habitos->save($habito)) {

    /*
     * Guardamos temporalmente el mensaje
     * para mostrar nuestra alerta personalizada.
     */
    $this->request
        ->getSession()
        ->write(
            'alerta_agendit',
            [
                'tipo' => 'success',
                'titulo' => '¡Hábito asignado!',
                'mensaje' => 'El hábito se asignó correctamente a tu socio.'
            ]
        );

    return $this->redirect([
        'controller' => 'Vinculaciones',
        'action' => 'agendaSocio',
        $idUsuario
    ]);
}


        /*
         * ======================================================
         * ERROR
         * ======================================================
         */

        $this->Flash->error(
            'No se pudo asignar el hábito. Revisa la información ingresada.'
        );


        /*
         * Dejamos disponible el ID para
         * volver a mostrar el formulario.
         */
        $this->set([
            'idUsuario' =>
                $idUsuario,

            /*
             * También mandamos la entidad
             * por si después queremos mostrar
             * errores directamente en la vista.
             */
            'habito' =>
                $habito
        ]);
    }


    /**
     * ==========================================================
     * INDEX JSON
     * ==========================================================
     *
     * Devuelve los hábitos del usuario
     * actualmente autenticado.
     */
    public function index()
    {
        $usuario =
            $this->usuarioActual();


        $idUsuario =
            (int)$usuario['id_usuario'];


        $habitos =
            $this->Habitos
                ->find()
                ->where([
                    'id_usuario' =>
                        $idUsuario
                ])
                ->orderBy([
                    'id_habito' =>
                        'DESC'
                ])
                ->all();


        $this->set([
            'ok' => true,
            'data' => $habitos
        ]);


        $this->viewBuilder()
            ->setOption(
                'serialize',
                [
                    'ok',
                    'data'
                ]
            );
    }


    /**
     * ==========================================================
     * VER UN HÁBITO
     * ==========================================================
     */
    public function view($id = null)
    {
        $usuario =
            $this->usuarioActual();


        $idUsuario =
            (int)$usuario['id_usuario'];


        /*
         * Verificamos también propiedad.
         */
        $habito =
            $this->Habitos
                ->find()
                ->where([
                    'id_habito' =>
                        (int)$id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$habito) {

            $this->response =
                $this->response
                    ->withStatus(404);


            $this->set([
                'ok' => false,
                'error' =>
                    'Hábito no encontrado.'
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'error'
                    ]
                );

            return;
        }


        $this->set([
            'ok' => true,
            'data' => $habito
        ]);


        $this->viewBuilder()
            ->setOption(
                'serialize',
                [
                    'ok',
                    'data'
                ]
            );
    }


    /**
     * ==========================================================
     * CREAR HÁBITO
     * ==========================================================
     *
     * Creado por el propio socio.
     */
    public function add()
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $usuario =
            $this->usuarioActual();


        $idUsuario =
            (int)$usuario['id_usuario'];


        /*
         * Igual que en asignar(),
         * agregamos primero los datos
         * controlados por el backend.
         */
        $datos =
            $this->request->getData();


        $datos['id_usuario'] =
            $idUsuario;


        $datos['id_especialista'] =
            null;


        $datos['creado_por'] =
            'SOCIO';


        /*
         * Creamos entidad.
         */
        $habito =
            $this->Habitos
                ->newEmptyEntity();


        $habito =
            $this->Habitos
                ->patchEntity(
                    $habito,
                    $datos
                );


        /*
         * Guardar.
         */
        if (
            $this->Habitos
                ->save($habito)
        ) {

            $this->response =
                $this->response
                    ->withStatus(201);


            $this->set([
                'ok' => true,
                'data' => $habito
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'data'
                    ]
                );

            return;
        }


        /*
         * Error de validación.
         */
        $this->response =
            $this->response
                ->withStatus(400);


        $this->set([
            'ok' => false,
            'error' =>
                $habito->getErrors()
        ]);


        $this->viewBuilder()
            ->setOption(
                'serialize',
                [
                    'ok',
                    'error'
                ]
            );
    }


    /**
     * ==========================================================
     * EDITAR HÁBITO
     * ==========================================================
     */
    public function edit($id = null)
    {
        $this->request
            ->allowMethod([
                'post',
                'put'
            ]);


        $usuario =
            $this->usuarioActual();


        $idUsuario =
            (int)$usuario['id_usuario'];


        $habito =
            $this->Habitos
                ->find()
                ->where([
                    'id_habito' =>
                        (int)$id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$habito) {

            $this->response =
                $this->response
                    ->withStatus(404);


            $this->set([
                'ok' => false,
                'error' =>
                    'Hábito no encontrado.'
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'error'
                    ]
                );

            return;
        }


        /*
         * IMPORTANTE:
         *
         * Un hábito asignado por un
         * especialista es solo lectura
         * para el socio.
         */
        if (
            $habito->creado_por ===
            'ESPECIALISTA'
        ) {

            $this->response =
                $this->response
                    ->withStatus(403);


            $this->set([
                'ok' => false,
                'error' =>
                    'No puedes modificar un hábito asignado por tu especialista.'
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'error'
                    ]
                );

            return;
        }


        /*
         * Solo permitimos modificar
         * los campos propios del hábito.
         */
        $habito =
            $this->Habitos
                ->patchEntity(
                    $habito,
                    $this->request->getData(),
                    [
                        'fields' => [
                            'titulo',
                            'notas',
                            'frecuencia',
                            'color'
                        ]
                    ]
                );


        if (
            $this->Habitos
                ->save($habito)
        ) {

            $this->set([
                'ok' => true,
                'data' => $habito
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'data'
                    ]
                );

            return;
        }


        $this->response =
            $this->response
                ->withStatus(400);


        $this->set([
            'ok' => false,
            'error' =>
                $habito->getErrors()
        ]);


        $this->viewBuilder()
            ->setOption(
                'serialize',
                [
                    'ok',
                    'error'
                ]
            );
    }


    /**
     * ==========================================================
     * ELIMINAR HÁBITO
     * ==========================================================
     */
    public function delete($id = null)
    {
        $this->request
            ->allowMethod([
                'post',
                'delete'
            ]);


        $usuario =
            $this->usuarioActual();


        $idUsuario =
            (int)$usuario['id_usuario'];


        $habito =
            $this->Habitos
                ->find()
                ->where([
                    'id_habito' =>
                        (int)$id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$habito) {

            $this->response =
                $this->response
                    ->withStatus(404);


            $this->set([
                'ok' => false,
                'error' =>
                    'Hábito no encontrado.'
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'error'
                    ]
                );

            return;
        }


        /*
         * Los hábitos asignados por
         * especialistas no pueden ser
         * eliminados por el socio.
         */
        if (
            $habito->creado_por ===
            'ESPECIALISTA'
        ) {

            $this->response =
                $this->response
                    ->withStatus(403);


            $this->set([
                'ok' => false,
                'error' =>
                    'No puedes eliminar un hábito asignado por tu especialista.'
            ]);


            $this->viewBuilder()
                ->setOption(
                    'serialize',
                    [
                        'ok',
                        'error'
                    ]
                );

            return;
        }


        $eliminado =
            $this->Habitos
                ->delete($habito);


        if (!$eliminado) {

            $this->response =
                $this->response
                    ->withStatus(500);
        }


        $this->set([
            'ok' =>
                (bool)$eliminado
        ]);


        $this->viewBuilder()
            ->setOption(
                'serialize',
                [
                    'ok'
                ]
            );
    }


    /**
     * ==========================================================
     * MARCAR HÁBITO COMO COMPLETADO
     * ==========================================================
     *
     * Solo el socio dueño del hábito puede
     * marcarlo/desmarcarlo en una fecha.
     */
    public function marcarCompletado($idHabito = null)
    {
        $this->request->allowMethod(['post']);

        $usuario = $this->usuarioActual();
        $idUsuario = (int)$usuario['id_usuario'];

        $habito = $this->Habitos
            ->find()
            ->where([
                'id_habito' => (int)$idHabito,
                'id_usuario' => $idUsuario
            ])
            ->first();

        if (!$habito) {

            $this->response = $this->response->withStatus(404);

            $this->set([
                'ok' => false,
                'error' => 'Hábito no encontrado.'
            ]);

            $this->viewBuilder()
                ->setOption('serialize', ['ok', 'error']);

            return;
        }

        $fecha = $this->request->getData('fecha');

        if (empty($fecha)) {

            $this->response = $this->response->withStatus(400);

            $this->set([
                'ok' => false,
                'error' => 'Falta la fecha.'
            ]);

            $this->viewBuilder()
                ->setOption('serialize', ['ok', 'error']);

            return;
        }

        $registroHabitos = TableRegistry::getTableLocator()
            ->get('RegistroHabitos');

        $registro = $registroHabitos
            ->find()
            ->where([
                'id_habito' => (int)$idHabito,
                'fecha' => $fecha
            ])
            ->first();

        if ($registro) {

            $registroHabitos->delete($registro);

            $this->set([
                'ok' => true,
                'completado' => false
            ]);

            $this->viewBuilder()
                ->setOption('serialize', ['ok', 'completado']);

            return;
        }

        $registro = $registroHabitos->newEmptyEntity();

        $registro = $registroHabitos->patchEntity($registro, [
            'id_habito' => (int)$idHabito,
            'fecha' => $fecha,
            'completado' => true
        ]);

        $registroHabitos->save($registro);

        $this->set([
            'ok' => true,
            'completado' => true
        ]);

        $this->viewBuilder()
            ->setOption('serialize', ['ok', 'completado']);
    }

    /**
     * ==========================================================
     * REGISTROS DE CUMPLIMIENTO (JSON)
     * ==========================================================
     *
     * Devuelve todas las fechas marcadas como completadas
     * de los hábitos del usuario en sesión. El JS del
     * calendario usa esto para pintar el check en las
     * ocurrencias correspondientes.
     */
    public function registrosCompletados()
    {
        $usuario = $this->usuarioActual();
        $idUsuario = (int)$usuario['id_usuario'];

        // Primero obtenemos los IDs de los hábitos del usuario.
        $idsHabitos = $this->Habitos
            ->find()
            ->select(['id_habito'])
            ->where(['id_usuario' => $idUsuario])
            ->all()
            ->extract('id_habito')
            ->toArray();

        $data = [];

        if (!empty($idsHabitos)) {

            $registros = TableRegistry::getTableLocator()
                ->get('RegistroHabitos')
                ->find()
                ->select(['id_habito', 'fecha'])
                ->where([
                    'id_habito IN' => $idsHabitos,
                    'completado' => true,
                ])
                ->all();

            foreach ($registros as $registro) {
                $data[] = [
                    'id_habito' => $registro->id_habito,
                    'fecha' => $registro->fecha->format('Y-m-d'),
                ];
            }
        }

        $this->set(['ok' => true, 'data' => $data]);
        $this->viewBuilder()->setOption('serialize', ['ok', 'data']);
    }

    /**
     * ==========================================================
     * PROGRESO
     * ==========================================================
     *
     * Si viene $idUsuario, un especialista consulta
     * el progreso de un socio vinculado.
     *
     * Si no viene, el socio consulta el suyo.
     */
    public function progreso($idUsuario = null)
    {
        $usuario = $this->usuarioActual();
        $rol = $usuario['rol'] ?? null;

        if ($idUsuario !== null) {

            if ($rol !== 'especialista') {

                $this->Flash->error(
                    'No tienes permiso para ver el progreso de este socio.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }

            $especialista = TableRegistry::getTableLocator()
                ->get('Especialistas')
                ->find()
                ->where(['id_usuario' => (int)$usuario['id_usuario']])
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

            $vinculacion = TableRegistry::getTableLocator()
                ->get('Vinculaciones')
                ->find()
                ->contain(['Usuarios'])
                ->where([
                    'id_usuario' => (int)$idUsuario,
                    'id_especialista' => $especialista->id_especialista,
                    'estado' => 'ACTIVA'
                ])
                ->first();

            if (!$vinculacion) {

                $this->Flash->error(
                    'Este usuario no está vinculado contigo.'
                );

                return $this->redirect([
                    'controller' => 'Vinculaciones',
                    'action' => 'misSocios'
                ]);
            }

            $idSocio = (int)$idUsuario;
            $nombreSocio = $vinculacion->usuario->nombre . ' ' . $vinculacion->usuario->apellido_paterno;
            $esPropio = false;

        } else {

            if ($rol !== 'usuario') {

                $this->Flash->error(
                    'No tienes permiso para acceder a esta sección.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }

            $idSocio = (int)$usuario['id_usuario'];
            $nombreSocio = null;
            $esPropio = true;
        }

        /*
         * Progreso de hábitos.
         */
        $habitos = $this->Habitos
            ->find()
            ->where(['id_usuario' => $idSocio])
            ->all();

        $registroHabitos = TableRegistry::getTableLocator()
            ->get('RegistroHabitos');

        $pasos = [
            'diaria' => 1,
            'cada 2 dias' => 2,
            'cada 3 dias' => 3,
            'semanal' => 7,
        ];

        $resumenHabitos = [];
        $totalEsperadas = 0;
        $totalCompletadas = 0;

        foreach ($habitos as $habito) {

            $inicio = $habito->fecha_creacion->format('Y-m-d');
            $hoy = FrozenTime::now()->format('Y-m-d');

            $fechaInicio = new \DateTime($inicio);
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

            $completadas = $registroHabitos
                ->find()
                ->where([
                    'id_habito' => $habito->id_habito,
                    'fecha IN' => $esperadas,
                    'completado' => true
                ])
                ->count();

            $totalEsp = count($esperadas);
            $porcentaje = $totalEsp > 0
                ? round(($completadas / $totalEsp) * 100)
                : 0;

            $resumenHabitos[] = [
                'titulo' => $habito->titulo,
                'esperadas' => $totalEsp,
                'completadas' => $completadas,
                'porcentaje' => $porcentaje
            ];

            $totalEsperadas += $totalEsp;
            $totalCompletadas += $completadas;
        }

        $porcentajeGeneralHabitos = $totalEsperadas > 0
            ? round(($totalCompletadas / $totalEsperadas) * 100)
            : 0;

        /*
         * Progreso de tareas.
         */
        $tareas = TableRegistry::getTableLocator()
            ->get('Tareas')
            ->find()
            ->where([
                'id_usuario' => $idSocio,
                'estado' => 'activa'
            ])
            ->all();

        $totalTareas = 0;
        $tareasCompletadas = 0;

        foreach ($tareas as $tarea) {
            $totalTareas++;

            if (!empty($tarea->fecha_completada)) {
                $tareasCompletadas++;
            }
        }

        $porcentajeTareas = $totalTareas > 0
            ? round(($tareasCompletadas / $totalTareas) * 100)
            : 0;

        $this->set([
            'esPropio' => $esPropio,
            'idSocio' => $idSocio,
            'nombreSocio' => $nombreSocio,
            'resumenHabitos' => $resumenHabitos,
            'porcentajeGeneralHabitos' => $porcentajeGeneralHabitos,
            'totalTareas' => $totalTareas,
            'tareasCompletadas' => $tareasCompletadas,
            'porcentajeTareas' => $porcentajeTareas
        ]);
    }
}