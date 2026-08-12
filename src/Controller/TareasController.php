<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\TareasTable;
use Cake\Event\EventInterface;
use Cake\Http\Response;


class TareasController extends AppController
{
    /**
     * Tabla Tareas.
     */
    private TareasTable $Tareas;


    /**
     * ==========================================================
     * INITIALIZE
     * ==========================================================
     */
    public function initialize(): void
    {
        parent::initialize();

        /*
         * Cargamos explícitamente la tabla.
         */
        $this->Tareas =
            $this->fetchTable('Tareas');


        /*
         * La mayoría de acciones devuelve JSON.
         *
         * Las vistas HTML activan autoRender
         * manualmente.
         */
        $this->autoRender = false;
    }


    /**
     * ==========================================================
     * SEGURIDAD
     * ==========================================================
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);


        $usuario =
            $this->request
                ->getSession()
                ->read('Usuario');


        if (!$usuario) {
            return;
        }


        $rol =
            $usuario['rol'] ?? null;


        $accionActual =
            $this->request
                ->getParam('action');


        /*
         * Acciones permitidas para especialista.
         */
        $accionesEspecialista = [
            'asignar',
            'eventosSocio',
            'editarAsignada',
            'eliminarAsignada'
        ];


        if (
            $rol === 'especialista' &&
            in_array(
                $accionActual,
                $accionesEspecialista,
                true
            )
        ) {

            /*
             * Permitido.
             */

        } elseif ($rol !== 'usuario') {

            $this->Flash->error(
                'No tienes permiso para acceder a las tareas.'
            );


            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        $this->request->allowMethod([
            'get',
            'post',
            'put',
            'delete'
        ]);
    }


    /**
     * ==========================================================
     * USUARIO ACTUAL
     * ==========================================================
     */
    private function getIdUsuarioActual(): int
    {
        $usuario =
            $this->request
                ->getSession()
                ->read('Usuario');


        return (int)(
            $usuario['id_usuario']
            ?? 0
        );
    }


    /**
     * ==========================================================
     * RESPUESTA JSON
     * ==========================================================
     */
    private function json(array $data): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode(
                    $data,
                    JSON_UNESCAPED_UNICODE
                )
            );
    }


    /**
     * ==========================================================
     * INDEX
     * ==========================================================
     *
     * GET /tareas/index
     *
     * Lista para la vista /tareas.
     */
    public function index(): Response
    {
        $idUsuario =
            $this->getIdUsuarioActual();


        $tareas =
            $this->Tareas
                ->find(
                    'activasDeUsuario',
                    idUsuario: $idUsuario
                )
                ->toArray();


        return $this->json([
            'exito' => true,
            'datos' => $tareas
        ]);
    }


    /**
     * ==========================================================
     * VISTA DE TAREAS
     * ==========================================================
     */
    public function vista(): void
    {
        $this->autoRender = true;


        $idUsuario =
            $this->getIdUsuarioActual();


        $categorias =
            $this->Tareas
                ->Categorias
                ->find(
                    'deUsuario',
                    idUsuario: $idUsuario
                )
                ->all();


        $this->set(
            'categorias',
            $categorias
        );
    }


    /**
     * ==========================================================
     * VER UNA TAREA
     * ==========================================================
     */
    public function ver(int $id): Response
    {
        $idUsuario =
            $this->getIdUsuarioActual();


        /*
         * También comprobamos propiedad.
         */
        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'Tareas.id_tarea' =>
                        $id,

                    'Tareas.id_usuario' =>
                        $idUsuario
                ])
                ->contain([
                    'Categorias',
                    'Subtareas'
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        return $this->json([
            'exito' => true,
            'datos' => $tarea
        ]);
    }


    /**
     * ==========================================================
     * AGREGAR TAREA
     * ==========================================================
     */
    public function agregar(): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $data =
            $this->request
                ->getData();


        $idUsuario =
            $this->getIdUsuarioActual();


        /*
         * IMPORTANTE:
         *
         * id_categoria puede no existir
         * en la petición.
         */
        $idCategoria =
            ($data['id_categoria'] ?? null)
            ?: null;


        /*
         * Si envió categoría,
         * comprobamos que sea suya.
         */
        if ($idCategoria) {

            $categoriaValida =
                $this->Tareas
                    ->Categorias
                    ->find()
                    ->where([
                        'id_categoria' =>
                            $idCategoria,

                        'id_usuario' =>
                            $idUsuario
                    ])
                    ->first();


            if (!$categoriaValida) {

                return $this->json([
                    'exito' => false,
                    'mensaje' =>
                        'Categoría inválida.'
                ]);
            }
        }


        /*
         * Construimos datos seguros.
         *
         * Todos los campos opcionales usan
         * ?? null para evitar warnings PHP.
         */
        $datosTarea = [

            'id_usuario' =>
                $idUsuario,

            'id_categoria' =>
                $idCategoria,

            'id_especialista' =>
                null,

            'creado_por' =>
                'SOCIO',

            'titulo' =>
                trim(
                    (string)(
                        $data['titulo']
                        ?? ''
                    )
                ),

            'notas' =>
                trim(
                    (string)(
                        $data['notas']
                        ?? ''
                    )
                )
                ?: null,

            'fecha_limite' =>
                ($data['fecha_limite'] ?? null)
                ?: null,

            'hora_limite' =>
                ($data['hora_limite'] ?? null)
                ?: null,

            /*
             * El calendario actualmente
             * no siempre manda este campo.
             */
            'hora_recordatorio' =>
                ($data['hora_recordatorio'] ?? null)
                ?: null
        ];


        $tarea =
            $this->Tareas
                ->newEmptyEntity();


        $tarea =
            $this->Tareas
                ->patchEntity(
                    $tarea,
                    $datosTarea
                );


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([
                'exito' => false,

                'mensaje' =>
                    'Error al crear la tarea.',

                'errores' =>
                    $tarea->getErrors()
            ]);
        }


        /*
         * Guardamos subtareas si existen.
         */
        $this->guardarSubtareas(
            (int)$tarea->id_tarea,
            $data['subtareas'] ?? []
        );


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea creada correctamente.',
            'datos' => [
                'id_tarea' =>
                    $tarea->id_tarea
            ]
        ]);
    }


    /**
     * ==========================================================
     * EDITAR TAREA
     * ==========================================================
     */
    public function editar(int $id): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idUsuario =
            $this->getIdUsuarioActual();


        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        /*
         * El socio no puede editar
         * tareas asignadas por especialista.
         */
        if (
            !empty(
                $tarea->id_especialista
            )
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Esta tarea fue asignada por tu especialista y no puede editarse.'
            ]);
        }


        $data =
            $this->request
                ->getData();


        $idCategoria =
            ($data['id_categoria'] ?? null)
            ?: null;


        if ($idCategoria) {

            $categoriaValida =
                $this->Tareas
                    ->Categorias
                    ->find()
                    ->where([
                        'id_categoria' =>
                            $idCategoria,

                        'id_usuario' =>
                            $idUsuario
                    ])
                    ->first();


            if (!$categoriaValida) {

                return $this->json([
                    'exito' => false,
                    'mensaje' =>
                        'Categoría inválida.'
                ]);
            }
        }


        $tarea =
            $this->Tareas
                ->patchEntity(
                    $tarea,
                    [

                        'id_categoria' =>
                            $idCategoria,

                        'titulo' =>
                            trim(
                                (string)(
                                    $data['titulo']
                                    ?? ''
                                )
                            ),

                        'notas' =>
                            trim(
                                (string)(
                                    $data['notas']
                                    ?? ''
                                )
                            )
                            ?: null,

                        'fecha_limite' =>
                            ($data['fecha_limite'] ?? null)
                            ?: null,

                        'hora_limite' =>
                            ($data['hora_limite'] ?? null)
                            ?: null,

                        'hora_recordatorio' =>
                            ($data['hora_recordatorio'] ?? null)
                            ?: null
                    ]
                );


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([
                'exito' => false,

                'mensaje' =>
                    'Error al actualizar la tarea.',

                'errores' =>
                    $tarea->getErrors()
            ]);
        }


        /*
         * Reemplazamos subtareas.
         */
        $this->Tareas
            ->Subtareas
            ->deleteAll([
                'id_tarea' =>
                    $id
            ]);


        $this->guardarSubtareas(
            $id,
            $data['subtareas'] ?? []
        );


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea actualizada correctamente.'
        ]);
    }


    /**
     * ==========================================================
     * ELIMINAR TAREA
     * ==========================================================
     *
     * Borrado lógico.
     */
    public function eliminar(int $id): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idUsuario =
            $this->getIdUsuarioActual();


        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        if (
            !empty(
                $tarea->id_especialista
            )
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Esta tarea fue asignada por tu especialista y no puede eliminarse.'
            ]);
        }


        $tarea->estado =
            'inactiva';


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Error al eliminar la tarea.'
            ]);
        }


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea eliminada correctamente.'
        ]);
    }


    /**
     * ==========================================================
     * MARCAR COMO COMPLETADA
     * ==========================================================
     */
    public function marcarCompletada(int $id): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idUsuario =
            $this->getIdUsuarioActual();


        /*
         * Antes usabas get($id), lo que permitía
         * intentar modificar tareas ajenas.
         */
        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $id,

                    'id_usuario' =>
                        $idUsuario
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        $completada =
            filter_var(
                $this->request
                    ->getData('completada'),
                FILTER_VALIDATE_BOOLEAN
            );


        $tarea->fecha_completada =
            $completada
                ? date('Y-m-d H:i:s')
                : null;


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Error al actualizar estado.'
            ]);
        }


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea actualizada.'
        ]);
    }


    /**
     * ==========================================================
     * MARCAR SUBTAREA
     * ==========================================================
     */
    public function marcarSubtareaCompletada(
        int $id
    ): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idUsuario =
            $this->getIdUsuarioActual();


        /*
         * Comprobamos que la subtarea pertenezca
         * a una tarea del usuario actual.
         */
        $subtarea =
            $this->Tareas
                ->Subtareas
                ->find()
                ->matching(
                    'Tareas',
                    function ($query) use ($idUsuario) {

                        return $query
                            ->where([
                                'Tareas.id_usuario' =>
                                    $idUsuario
                            ]);
                    }
                )
                ->where([
                    'Subtareas.id_subtarea' =>
                        $id
                ])
                ->first();


        if (!$subtarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Subtarea no encontrada.'
            ]);
        }


        $completada =
            filter_var(
                $this->request
                    ->getData('completada'),
                FILTER_VALIDATE_BOOLEAN
            );


        $subtarea->completada =
            $completada;


        $subtarea->fecha_completada =
            $completada
                ? date('Y-m-d H:i:s')
                : null;


        if (
            !$this->Tareas
                ->Subtareas
                ->save($subtarea)
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'No se pudo actualizar la subtarea.'
            ]);
        }


        $idTarea =
            (int)$subtarea->id_tarea;


        $total =
            $this->Tareas
                ->Subtareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $idTarea
                ])
                ->count();


        $completadas =
            $this->Tareas
                ->Subtareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $idTarea,

                    'completada' =>
                        true
                ])
                ->count();


        if ($total > 0) {

            $tarea =
                $this->Tareas
                    ->find()
                    ->where([
                        'id_tarea' =>
                            $idTarea,

                        'id_usuario' =>
                            $idUsuario
                    ])
                    ->first();


            if ($tarea) {

                $tarea->fecha_completada =
                    (
                        $total ===
                        $completadas
                    )
                        ? date('Y-m-d H:i:s')
                        : null;


                $this->Tareas
                    ->save($tarea);
            }
        }


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Subtarea actualizada.'
        ]);
    }


    /**
     * ==========================================================
     * GUARDAR SUBTAREAS
     * ==========================================================
     */
    private function guardarSubtareas(
        int $idTarea,
        array $subtareas
    ): void
    {
        foreach ($subtareas as $titulo) {

            $titulo =
                trim(
                    (string)$titulo
                );


            if ($titulo === '') {
                continue;
            }


            $subtarea =
                $this->Tareas
                    ->Subtareas
                    ->newEmptyEntity();


            $subtarea =
                $this->Tareas
                    ->Subtareas
                    ->patchEntity(
                        $subtarea,
                        [
                            'id_tarea' =>
                                $idTarea,

                            'titulo' =>
                                $titulo
                        ]
                    );


            $this->Tareas
                ->Subtareas
                ->save($subtarea);
        }
    }


    /**
     * ==========================================================
     * CALENDARIO
     * ==========================================================
     */
    public function calendario(): void
    {
        $this->autoRender =
            true;


        $idUsuario =
            $this->getIdUsuarioActual();


        $categorias =
            $this->Tareas
                ->Categorias
                ->find(
                    'deUsuario',
                    idUsuario: $idUsuario
                )
                ->all();


        $this->set(
            'categorias',
            $categorias
        );
    }


    /**
     * ==========================================================
     * EVENTOS DEL USUARIO
     * ==========================================================
     */
    public function eventos(): Response
    {
        $idUsuario =
            $this->getIdUsuarioActual();


        $tareas =
            $this->Tareas
                ->find()
                ->where([
                    'Tareas.id_usuario' =>
                        $idUsuario,

                    'Tareas.estado' =>
                        'activa'
                ])
                ->contain([
                    'Categorias',
                    'Especialistas.TipoEspecialistas'
                ])
                ->all();


        $eventos = [];


        foreach ($tareas as $tarea) {

            /*
             * Si la tarea fue asignada por
             * especialista, usamos su color.
             */
            if (
                $tarea->id_especialista &&
                $tarea->especialista &&
                $tarea
                    ->especialista
                    ->tipo_especialista
            ) {

                $color =
                    $tarea
                        ->especialista
                        ->tipo_especialista
                        ->color;


                $nombreCategoria =
                    $tarea
                        ->especialista
                        ->tipo_especialista
                        ->nombre;

            } else {

                $color =
                    $tarea->categoria
                        ? $tarea
                            ->categoria
                            ->color
                        : '#6c757d';


                $nombreCategoria =
                    $tarea->categoria
                        ? $tarea
                            ->categoria
                            ->nombre
                        : 'Sin categoría';
            }


            /*
             * Las tareas sin fecha no aparecen
             * en FullCalendar.
             */
            if (!$tarea->fecha_limite) {
                continue;
            }


            $evento = [

                'id' =>
                    $tarea->id_tarea,

                'title' =>
                    $tarea->titulo,

                'backgroundColor' =>
                    $color,

                'borderColor' =>
                    $color,

                'extendedProps' => [

                    'completada' =>
                        !empty(
                            $tarea->fecha_completada
                        ),

                    'notas' =>
                        $tarea->notas,

                    'categoria' =>
                        $nombreCategoria,

                    'idCategoria' =>
                        $tarea->id_categoria,

                    'asignadaPorEspecialista' =>
                        (bool)$tarea->id_especialista
                ]
            ];


            if ($tarea->hora_limite) {

                $evento['start'] =
                    $tarea
                        ->fecha_limite
                        ->format('Y-m-d')
                    .
                    'T'
                    .
                    $tarea
                        ->hora_limite
                        ->format('H:i:s');


                $evento['allDay'] =
                    false;

            } else {

                $evento['start'] =
                    $tarea
                        ->fecha_limite
                        ->format('Y-m-d');


                $evento['allDay'] =
                    true;
            }


            $eventos[] =
                $evento;
        }


        return $this->json([
            'exito' => true,
            'datos' => $eventos
        ]);
    }


    /**
     * ==========================================================
     * ESPECIALISTA ACTUAL
     * ==========================================================
     */
    private function getEspecialistaActual(): ?int
    {
        $usuario =
            $this->request
                ->getSession()
                ->read('Usuario');


        if (
            ($usuario['rol'] ?? null)
            !== 'especialista'
        ) {

            return null;
        }


        $especialista =
            $this
                ->fetchTable('Especialistas')
                ->find()
                ->where([
                    'id_usuario' =>
                        $usuario['id_usuario']
                ])
                ->first();


        return $especialista
            ? (int)$especialista
                ->id_especialista
            : null;
    }


    /**
     * ==========================================================
     * EVENTOS DEL SOCIO
     * ==========================================================
     *
     * Privacidad:
     *
     * El especialista ve únicamente el
     * contenido completo de sus propias tareas.
     *
     * Las demás aparecen como "Ocupado".
     */
    public function eventosSocio(
        int $idUsuario
    ): Response
    {
        $idEspecialista =
            $this->getEspecialistaActual();


        if (!$idEspecialista) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'No autorizado.'
            ]);
        }


        $vinculacion =
            $this
                ->fetchTable('Vinculaciones')
                ->find()
                ->where([
                    'id_usuario' =>
                        $idUsuario,

                    'id_especialista' =>
                        $idEspecialista,

                    'estado' =>
                        'ACTIVA'
                ])
                ->first();


        if (!$vinculacion) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Socio no vinculado.'
            ]);
        }


        $especialista =
            $this
                ->fetchTable('Especialistas')
                ->find()
                ->contain([
                    'TipoEspecialistas'
                ])
                ->where([
                    'id_especialista' =>
                        $idEspecialista
                ])
                ->first();


        $colorPropio =
            (
                $especialista &&
                $especialista
                    ->tipo_especialista
            )
                ? $especialista
                    ->tipo_especialista
                    ->color
                : '#6c757d';


        $tareas =
            $this->Tareas
                ->find()
                ->where([
                    'id_usuario' =>
                        $idUsuario,

                    'estado' =>
                        'activa'
                ])
                ->all();


        $eventos = [];


        foreach ($tareas as $tarea) {

            if (!$tarea->fecha_limite) {
                continue;
            }


            $esMia =
                (int)$tarea
                    ->id_especialista
                ===
                $idEspecialista;


            $evento = [

                'id' =>
                    $tarea->id_tarea,

                'title' =>
                    $esMia
                        ? $tarea->titulo
                        : 'Ocupado',

                'backgroundColor' =>
                    $esMia
                        ? $colorPropio
                        : '#adb5bd',

                'borderColor' =>
                    $esMia
                        ? $colorPropio
                        : '#adb5bd',

                'extendedProps' => [

                    'editable' =>
                        $esMia,

                    'notas' =>
                        $esMia
                            ? $tarea->notas
                            : null
                ]
            ];


            if ($tarea->hora_limite) {

                $evento['start'] =
                    $tarea
                        ->fecha_limite
                        ->format('Y-m-d')
                    .
                    'T'
                    .
                    $tarea
                        ->hora_limite
                        ->format('H:i:s');


                $evento['allDay'] =
                    false;

            } else {

                $evento['start'] =
                    $tarea
                        ->fecha_limite
                        ->format('Y-m-d');


                $evento['allDay'] =
                    true;
            }


            $eventos[] =
                $evento;
        }


        return $this->json([
            'exito' => true,
            'datos' => $eventos
        ]);
    }


    /**
     * ==========================================================
     * ASIGNAR TAREA
     * ==========================================================
     *
     * Especialista -> socio.
     */
    public function asignar(): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idEspecialista =
            $this->getEspecialistaActual();


        if (!$idEspecialista) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'No autorizado.'
            ]);
        }


        $data =
            $this->request
                ->getData();


        $idUsuario =
            (int)(
                $data['id_usuario']
                ?? 0
            );


        $vinculacion =
            $this
                ->fetchTable('Vinculaciones')
                ->find()
                ->where([
                    'id_usuario' =>
                        $idUsuario,

                    'id_especialista' =>
                        $idEspecialista,

                    'estado' =>
                        'ACTIVA'
                ])
                ->first();


        if (!$vinculacion) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Socio no vinculado.'
            ]);
        }


        $tarea =
            $this->Tareas
                ->newEmptyEntity();


        $tarea =
            $this->Tareas
                ->patchEntity(
                    $tarea,
                    [

                        'id_usuario' =>
                            $idUsuario,

                        'id_especialista' =>
                            $idEspecialista,

                        'id_categoria' =>
                            null,

                        'creado_por' =>
                            'ESPECIALISTA',

                        'titulo' =>
                            trim(
                                (string)(
                                    $data['titulo']
                                    ?? ''
                                )
                            ),

                        'notas' =>
                            trim(
                                (string)(
                                    $data['notas']
                                    ?? ''
                                )
                            )
                            ?: null,

                        'fecha_limite' =>
                            ($data['fecha_limite'] ?? null)
                            ?: null,

                        'hora_limite' =>
                            ($data['hora_limite'] ?? null)
                            ?: null,

                        'hora_recordatorio' =>
                            ($data['hora_recordatorio'] ?? null)
                            ?: null
                    ]
                );


        $resultado =
            $this->Tareas
                ->save($tarea);


        return $this->json([

            'exito' =>
                (bool)$resultado,

            'mensaje' =>
                $resultado
                    ? 'Tarea asignada correctamente.'
                    : 'Error al asignar la tarea.',

            'errores' =>
                $resultado
                    ? null
                    : $tarea->getErrors()
        ]);
    }


    /**
     * ==========================================================
     * EDITAR TAREA ASIGNADA
     * ==========================================================
     */
    public function editarAsignada(
        int $id
    ): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idEspecialista =
            $this->getEspecialistaActual();


        if (!$idEspecialista) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'No autorizado.'
            ]);
        }


        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $id,

                    'id_especialista' =>
                        $idEspecialista
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        $data =
            $this->request
                ->getData();


        $tarea =
            $this->Tareas
                ->patchEntity(
                    $tarea,
                    [

                        'titulo' =>
                            trim(
                                (string)(
                                    $data['titulo']
                                    ?? ''
                                )
                            ),

                        'notas' =>
                            trim(
                                (string)(
                                    $data['notas']
                                    ?? ''
                                )
                            )
                            ?: null,

                        'fecha_limite' =>
                            ($data['fecha_limite'] ?? null)
                            ?: null,

                        'hora_limite' =>
                            ($data['hora_limite'] ?? null)
                            ?: null,

                        'hora_recordatorio' =>
                            ($data['hora_recordatorio'] ?? null)
                            ?: null
                    ]
                );


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([

                'exito' =>
                    false,

                'mensaje' =>
                    'Error al actualizar la tarea.',

                'errores' =>
                    $tarea->getErrors()
            ]);
        }


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea actualizada correctamente.'
        ]);
    }


    /**
     * ==========================================================
     * ELIMINAR TAREA ASIGNADA
     * ==========================================================
     */
    public function eliminarAsignada(
        int $id
    ): Response
    {
        $this->request
            ->allowMethod([
                'post'
            ]);


        $idEspecialista =
            $this->getEspecialistaActual();


        if (!$idEspecialista) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'No autorizado.'
            ]);
        }


        $tarea =
            $this->Tareas
                ->find()
                ->where([
                    'id_tarea' =>
                        $id,

                    'id_especialista' =>
                        $idEspecialista
                ])
                ->first();


        if (!$tarea) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Tarea no encontrada.'
            ]);
        }


        $tarea->estado =
            'inactiva';


        if (
            !$this->Tareas
                ->save($tarea)
        ) {

            return $this->json([
                'exito' => false,
                'mensaje' =>
                    'Error al eliminar la tarea.'
            ]);
        }


        return $this->json([
            'exito' => true,
            'mensaje' =>
                'Tarea eliminada correctamente.'
        ]);
    }
}