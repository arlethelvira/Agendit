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
        $this->set('categorias', $this->Tareas->Categorias->find()->all());
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

        $tarea = $this->Tareas->newEmptyEntity();
        $tarea = $this->Tareas->patchEntity($tarea, [
            'id_usuario' => $this->getIdUsuarioActual(),
            'id_categoria' => $data['id_categoria'] ?: null,
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
        $tarea = $this->Tareas->patchEntity($tarea, [
            'id_categoria' => $data['id_categoria'] ?: null,
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

    public function beforeFilter(EventInterface $event){
    parent::beforeFilter($event);

    $usuario = $this->request->getSession()->read('Usuario');

    if (!$usuario) {
        return;
    }

    if (($usuario['rol'] ?? null) !== 'usuario') {
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
    $this->set('categorias', $this->Tareas->Categorias->find()->all());
}

// GET /tareas/eventos -> tareas del usuario en formato FullCalendar
public function eventos(): Response
{
    $idUsuario = $this->getIdUsuarioActual();

    $tareas = $this->Tareas->find()
        ->where(['id_usuario' => $idUsuario, 'estado' => 'activa'])
        ->contain(['Categorias'])
        ->all();

    $eventos = [];
    foreach ($tareas as $tarea) {
        $color = $tarea->categoria ? $tarea->categoria->color : '#6c757d';

        $evento = [
            'id' => $tarea->id_tarea,
            'title' => $tarea->titulo,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
                'completada' => !empty($tarea->fecha_completada),
                'notas' => $tarea->notas,
                'categoria' => $tarea->categoria ? $tarea->categoria->nombre : 'Sin categoría',
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
}