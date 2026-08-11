<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\Event\EventInterface;


class TareasController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->autoRender = false;

        //$this->Authentication->addUnauthenticatedActions([
        
    }

    private function getIdUsuarioActual(): int
{
    $usuario = $this->request->getSession()->read('Usuario');
    return (int)($usuario['id_usuario'] ?? 0);
}

    private function json(array $data): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    // GET /tareas/index -> listar tareas activas del usuario (JSON)
    public function index(): Response
    {
        $idUsuario = $this->getIdUsuarioActual();
        $tareas = $this->Tareas->find('activasDeUsuario', idUsuario: $idUsuario)->toArray();

        return $this->json(['exito' => true, 'datos' => $tareas]);
    }

    // GET /tareas/vista -> la página HTML (renderiza la plantilla)
    public function vista(): void
    {
        $this->autoRender = true;
        $idUsuario = $this->getIdUsuarioActual();
        $this->set('categorias', $this->Tareas->Categorias->find('deUsuario', idUsuario: $idUsuario)->all());
    }

    // GET /tareas/ver/{id}
    public function ver(int $id): Response
    {
        $tarea = $this->Tareas->find()
            ->where(['id_tarea' => $id])
            ->contain(['Categorias', 'Subtareas'])
            ->first();

        if (!$tarea) {
            return $this->json(['exito' => false, 'mensaje' => 'Tarea no encontrada']);
        }

        return $this->json(['exito' => true, 'datos' => $tarea]);
    }

    // POST /tareas/agregar
    public function agregar(): Response
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

         $idCategoria = $data['id_categoria'] ?: null;

         if ($idCategoria) {
        $categoriaValida = $this->Tareas->Categorias->find()
            ->where(['id_categoria' => $idCategoria, 'id_usuario' => $this->getIdUsuarioActual()])
            ->first();

        if (!$categoriaValida) {
            return $this->json(['exito' => false, 'mensaje' => 'Categoría inválida']);
        }
    }

        $tarea = $this->Tareas->newEmptyEntity();
        $tarea = $this->Tareas->patchEntity($tarea, [
            'id_usuario' => $this->getIdUsuarioActual(),
            'id_categoria' => $idCategoria,
            'titulo' => trim($data['titulo'] ?? ''),
            'notas' => trim($data['notas'] ?? '') ?: null,
            'fecha_limite' => $data['fecha_limite'] ?: null,
            'hora_limite' => $data['hora_limite'] ?: null,
            'hora_recordatorio' => $data['hora_recordatorio'] ?: null,
        ]);

        if (!$this->Tareas->save($tarea)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al crear la tarea', 'errores' => $tarea->getErrors()]);
        }

        $this->guardarSubtareas($tarea->id_tarea, $data['subtareas'] ?? []);

        return $this->json(['exito' => true, 'mensaje' => 'Tarea creada correctamente']);
    }

    // POST /tareas/editar/{id}
    public function editar(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $idUsuario = $this->getIdUsuarioActual();
$tarea = $this->Tareas->find()
    ->where(['id_tarea' => $id, 'id_usuario' => $idUsuario])
    ->first();

if (!$tarea) {
    return $this->json(['exito' => false, 'mensaje' => 'Tarea no encontrada']);
}

        if (!empty($tarea->id_especialista)) {
            return $this->json(['exito' => false, 'mensaje' => 'Esta tarea fue asignada por tu especialista y no puede editarse']);
        }

        $data = $this->request->getData();

        $idCategoria = $data['id_categoria'] ?: null;

    if ($idCategoria) {
        $categoriaValida = $this->Tareas->Categorias->find()
            ->where(['id_categoria' => $idCategoria, 'id_usuario' => $idUsuario])
            ->first();

        if (!$categoriaValida) {
            return $this->json(['exito' => false, 'mensaje' => 'Categoría inválida']);
        }
    }
        $tarea = $this->Tareas->patchEntity($tarea, [
            'id_categoria' => $idCategoria,
            'titulo' => trim($data['titulo'] ?? ''),
            'notas' => trim($data['notas'] ?? '') ?: null,
            'fecha_limite' => $data['fecha_limite'] ?: null,
            'hora_limite' => $data['hora_limite'] ?: null,
            'hora_recordatorio' => $data['hora_recordatorio'] ?: null,
        ]);

        if (!$this->Tareas->save($tarea)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al actualizar la tarea', 'errores' => $tarea->getErrors()]);
        }

        $this->Tareas->Subtareas->deleteAll(['id_tarea' => $id]);
        $this->guardarSubtareas($id, $data['subtareas'] ?? []);

        return $this->json(['exito' => true, 'mensaje' => 'Tarea actualizada correctamente']);
    }

    // POST /tareas/eliminar/{id} -> borrado lógico
    public function eliminar(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $idUsuario = $this->getIdUsuarioActual();
$tarea = $this->Tareas->find()
    ->where(['id_tarea' => $id, 'id_usuario' => $idUsuario])
    ->first();

if (!$tarea) {
    return $this->json(['exito' => false, 'mensaje' => 'Tarea no encontrada']);
}

        if (!empty($tarea->id_especialista)) {
            return $this->json(['exito' => false, 'mensaje' => 'Esta tarea fue asignada por tu especialista y no puede eliminarse']);
        }

        $tarea->estado = 'inactiva';

        if (!$this->Tareas->save($tarea)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al eliminar la tarea']);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Tarea eliminada correctamente']);
    }

    // POST /tareas/marcar-completada/{id}
    public function marcarCompletada(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $completada = filter_var($this->request->getData('completada'), FILTER_VALIDATE_BOOLEAN);

        $tarea = $this->Tareas->get($id);
        $tarea->fecha_completada = $completada ? date('Y-m-d H:i:s') : null;

        if (!$this->Tareas->save($tarea)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al actualizar estado']);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Tarea actualizada']);
    }

    // POST /tareas/marcar-subtarea-completada/{id}
    public function marcarSubtareaCompletada(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $completada = filter_var($this->request->getData('completada'), FILTER_VALIDATE_BOOLEAN);

        $subtareasTable = $this->Tareas->Subtareas;
        $subtarea = $subtareasTable->get($id);
        $subtarea->completada = $completada;
        $subtarea->fecha_completada = $completada ? date('Y-m-d H:i:s') : null;
        $subtareasTable->save($subtarea);

        $idTarea = $subtarea->id_tarea;
        $total = $subtareasTable->find()->where(['id_tarea' => $idTarea])->count();
        $completadas = $subtareasTable->find()->where(['id_tarea' => $idTarea, 'completada' => true])->count();

        if ($total > 0) {
            $tarea = $this->Tareas->get($idTarea);
            $tarea->fecha_completada = ($total === $completadas) ? date('Y-m-d H:i:s') : null;
            $this->Tareas->save($tarea);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Subtarea actualizada']);
    }

    private function guardarSubtareas(int $idTarea, array $subtareas): void
    {
        $subtareasTable = $this->Tareas->Subtareas;
        foreach ($subtareas as $titulo) {
            $titulo = trim((string)$titulo);
            if ($titulo === '') continue;

            $subtarea = $subtareasTable->newEmptyEntity();
            $subtarea = $subtareasTable->patchEntity($subtarea, [
                'id_tarea' => $idTarea,
                'titulo' => $titulo,
            ]);
            $subtareasTable->save($subtarea);
        }
    }

    public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);

    $usuario = $this->request->getSession()->read('Usuario');

    if (!$usuario) {
        return;
    }

    $rol = $usuario['rol'] ?? null;
    $accionActual = $this->request->getParam('action');

    // Acciones que el especialista sí puede usar
    $accionesEspecialista = ['asignar', 'eventosSocio', 'editarAsignada', 'eliminarAsignada'];

    if ($rol === 'especialista' && in_array($accionActual, $accionesEspecialista, true)) {
        // Permitido: dejamos pasar sin bloquear
    } elseif ($rol !== 'usuario') {
        $this->Flash->error('No tienes permiso para acceder a las tareas.');
        $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        return;
    }

    $this->request->allowMethod(['get', 'post', 'put', 'delete']);
}

    // GET /tareas/calendario -> vista HTML del calendario
public function calendario(): void
{
    $this->autoRender = true;
    $idUsuario = $this->getIdUsuarioActual();
     $this->set('categorias', $this->Tareas->Categorias->find('deUsuario', idUsuario: $idUsuario)->all());
}

// GET /tareas/eventos -> tareas del usuario en formato FullCalendar
public function eventos(): Response
{
    $idUsuario = $this->getIdUsuarioActual();

    $tareas = $this->Tareas->find()
        ->where(['Tareas.id_usuario' => $idUsuario, 'Tareas.estado' => 'activa'])
        ->contain(['Categorias', 'Especialistas.TipoEspecialistas'])
        ->all();

    $eventos = [];
    foreach ($tareas as $tarea) {

        if ($tarea->id_especialista && $tarea->especialista && $tarea->especialista->tipo_especialista) {
            $color = $tarea->especialista->tipo_especialista->color;
            $nombreCategoria = $tarea->especialista->tipo_especialista->nombre;
        } else {
            $color = $tarea->categoria ? $tarea->categoria->color : '#6c757d';
            $nombreCategoria = $tarea->categoria ? $tarea->categoria->nombre : 'Sin categoría';
        }

        $evento = [
            'id' => $tarea->id_tarea,
            'title' => $tarea->titulo,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
                'completada' => !empty($tarea->fecha_completada),
                'notas' => $tarea->notas,
                'categoria' => $nombreCategoria,
                'idCategoria' => $tarea->id_categoria,
                'asignadaPorEspecialista' => (bool)$tarea->id_especialista,
            ],
        ];

        if ($tarea->fecha_limite) {
            if ($tarea->hora_limite) {
                $evento['start'] = $tarea->fecha_limite->format('Y-m-d') . 'T' . $tarea->hora_limite->format('H:i:s');
                $evento['allDay'] = false;
            } else {
                $evento['start'] = $tarea->fecha_limite->format('Y-m-d');
                $evento['allDay'] = true;
            }
            $eventos[] = $evento;
        }
    }

    return $this->json(['exito' => true, 'datos' => $eventos]);
}

    // Obtiene el id_especialista del especialista en sesión, o null si no aplica
private function getEspecialistaActual(): ?int
{
    $usuario = $this->request->getSession()->read('Usuario');
    if (($usuario['rol'] ?? null) !== 'especialista') {
        return null;
    }

    $especialista = $this->fetchTable('Especialistas')
        ->find()
        ->where(['id_usuario' => $usuario['id_usuario']])
        ->first();

    return $especialista ? (int)$especialista->id_especialista : null;
}

// GET /tareas/eventos-socio/{idUsuario} -> agenda del socio, con privacidad
public function eventosSocio(int $idUsuario): Response
{
    $idEspecialista = $this->getEspecialistaActual();

    if (!$idEspecialista) {
        return $this->json(['exito' => false, 'mensaje' => 'No autorizado']);
    }

    // Verificamos vinculación activa antes de mostrar cualquier dato
    $vinculacion = $this->fetchTable('Vinculaciones')
        ->find()
        ->where([
            'id_usuario' => $idUsuario,
            'id_especialista' => $idEspecialista,
            'estado' => 'ACTIVA'
        ])
        ->first();

    if (!$vinculacion) {
        return $this->json(['exito' => false, 'mensaje' => 'Socio no vinculado']);
    }

    // Color del tipo de especialista (para las tareas que él mismo asignó)
    $especialista = $this->fetchTable('Especialistas')
        ->find()
        ->contain(['TipoEspecialistas'])
        ->where(['id_especialista' => $idEspecialista])
        ->first();
    $colorPropio = $especialista && $especialista->tipo_especialista
        ? $especialista->tipo_especialista->color
        : '#6c757d';

    $tareas = $this->Tareas->find()
        ->where(['id_usuario' => $idUsuario, 'estado' => 'activa'])
        ->all();

    $eventos = [];
    foreach ($tareas as $tarea) {
        if (!$tarea->fecha_limite) continue;

        $esMia = (int)$tarea->id_especialista === $idEspecialista;

        $evento = [
            'id' => $tarea->id_tarea,
            'title' => $esMia ? $tarea->titulo : 'Ocupado',
            'backgroundColor' => $esMia ? $colorPropio : '#adb5bd',
            'borderColor' => $esMia ? $colorPropio : '#adb5bd',
            'extendedProps' => [
                'editable' => $esMia,
                'notas' => $esMia ? $tarea->notas : null,
            ],
        ];

        if ($tarea->hora_limite) {
            $evento['start'] = $tarea->fecha_limite->format('Y-m-d') . 'T' . $tarea->hora_limite->format('H:i:s');
            $evento['allDay'] = false;
        } else {
            $evento['start'] = $tarea->fecha_limite->format('Y-m-d');
            $evento['allDay'] = true;
        }

        $eventos[] = $evento;
    }

    return $this->json(['exito' => true, 'datos' => $eventos]);
}

// POST /tareas/asignar -> el especialista asigna una tarea a un socio vinculado
public function asignar(): Response
{
    $this->request->allowMethod(['post']);
    $idEspecialista = $this->getEspecialistaActual();

    if (!$idEspecialista) {
        return $this->json(['exito' => false, 'mensaje' => 'No autorizado']);
    }

    $data = $this->request->getData();
    $idUsuario = (int)($data['id_usuario'] ?? 0);

    $vinculacion = $this->fetchTable('Vinculaciones')
        ->find()
        ->where([
            'id_usuario' => $idUsuario,
            'id_especialista' => $idEspecialista,
            'estado' => 'ACTIVA'
        ])
        ->first();

    if (!$vinculacion) {
        return $this->json(['exito' => false, 'mensaje' => 'Socio no vinculado']);
    }

    $tarea = $this->Tareas->newEmptyEntity();
    $tarea = $this->Tareas->patchEntity($tarea, [
        'id_usuario' => $idUsuario,
        'id_especialista' => $idEspecialista,
        'id_categoria' => null,
        'creado_por' => 'ESPECIALISTA', // nuevo
        'titulo' => trim($data['titulo'] ?? ''),
        'notas' => trim($data['notas'] ?? '') ?: null,
        'fecha_limite' => $data['fecha_limite'] ?: null,
        'hora_limite' => $data['hora_limite'] ?: null,
    ]);

    $resultado = $this->Tareas->save($tarea);

    return $this->json([
        'exito' => (bool)$resultado,
        'mensaje' => $resultado ? 'Tarea asignada correctamente' : 'Error al asignar la tarea',
        'errores' => $resultado ? null : $tarea->getErrors()
    ]);
}
// POST /tareas/editar-asignada/{id}
public function editarAsignada(int $id): Response
{
    $this->request->allowMethod(['post']);
    $idEspecialista = $this->getEspecialistaActual();

    if (!$idEspecialista) {
        return $this->json(['exito' => false, 'mensaje' => 'No autorizado']);
    }

    $tarea = $this->Tareas->find()
        ->where(['id_tarea' => $id, 'id_especialista' => $idEspecialista])
        ->first();

    if (!$tarea) {
        return $this->json(['exito' => false, 'mensaje' => 'Tarea no encontrada']);
    }

    $data = $this->request->getData();
    $tarea = $this->Tareas->patchEntity($tarea, [
        'titulo' => trim($data['titulo'] ?? ''),
        'notas' => trim($data['notas'] ?? '') ?: null,
        'fecha_limite' => $data['fecha_limite'] ?: null,
        'hora_limite' => $data['hora_limite'] ?: null,
    ]);

    if (!$this->Tareas->save($tarea)) {
        return $this->json(['exito' => false, 'mensaje' => 'Error al actualizar', 'errores' => $tarea->getErrors()]);
    }

    return $this->json(['exito' => true, 'mensaje' => 'Tarea actualizada correctamente']);
}

// POST /tareas/eliminar-asignada/{id}
public function eliminarAsignada(int $id): Response
{
    $this->request->allowMethod(['post']);
    $idEspecialista = $this->getEspecialistaActual();

    if (!$idEspecialista) {
        return $this->json(['exito' => false, 'mensaje' => 'No autorizado']);
    }

    $tarea = $this->Tareas->find()
        ->where(['id_tarea' => $id, 'id_especialista' => $idEspecialista])
        ->first();

    if (!$tarea) {
        return $this->json(['exito' => false, 'mensaje' => 'Tarea no encontrada']);
    }

    $tarea->estado = 'inactiva';

    if (!$this->Tareas->save($tarea)) {
        return $this->json(['exito' => false, 'mensaje' => 'Error al eliminar']);
    }

    return $this->json(['exito' => true, 'mensaje' => 'Tarea eliminada correctamente']);
}
}