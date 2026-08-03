<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Habitos Controller
 *
 * Backend puro (sin vistas) - responde JSON.
 * Rutas por convención de CakePHP (fallbacks), ej:
 *   GET    /habitos              -> index (opcional ?id_usuario=4)
 *   GET    /habitos/view/10      -> view
 *   POST   /habitos/add          -> add
 *   POST   /habitos/edit/10      -> edit
 *   POST   /habitos/delete/10    -> delete
 */
class HabitosController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setClassName('Json');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // TEMPORAL: mientras se integra el login real, se permite sin
        // autenticación para poder probar el backend. Cuando el módulo
        // se conecte al flujo de sesión, quitar esta línea (o dejar
        // solo las acciones de lectura si aplica).
        $this->Authentication->allowUnauthenticated([
            'index', 'view', 'add', 'edit', 'delete',
        ]);

        // Permite recibir JSON/POST desde fetch sin token de formulario,
        // ya que este controller no usa vistas con FormHelper.
        $this->request->allowMethod(['get', 'post', 'put', 'delete']);
    }

    public function index()
    {
        $query = $this->Habitos->find();

        $idUsuario = $this->request->getQuery('id_usuario');
        if ($idUsuario !== null) {
            $query->where(['id_usuario' => (int)$idUsuario]);
        }

        $habitos = $query->orderBy(['id_habito' => 'DESC'])->all();

        $this->set(['ok' => true, 'data' => $habitos]);
        $this->viewBuilder()->setOption('serialize', ['ok', 'data']);
    }

    public function view($id = null)
    {
        $habito = $this->Habitos->find()
            ->where(['id_habito' => (int)$id])
            ->first();

        if (!$habito) {
            $this->response = $this->response->withStatus(404);
            $this->set(['ok' => false, 'error' => 'Hábito no encontrado']);
            $this->viewBuilder()->setOption('serialize', ['ok', 'error']);
            return;
        }

        $this->set(['ok' => true, 'data' => $habito]);
        $this->viewBuilder()->setOption('serialize', ['ok', 'data']);
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $habito = $this->Habitos->newEmptyEntity();
        $habito = $this->Habitos->patchEntity($habito, $this->request->getData());

        if ($this->Habitos->save($habito)) {
            $this->response = $this->response->withStatus(201);
            $this->set(['ok' => true, 'data' => $habito]);
            $this->viewBuilder()->setOption('serialize', ['ok', 'data']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set(['ok' => false, 'error' => $habito->getErrors()]);
        $this->viewBuilder()->setOption('serialize', ['ok', 'error']);
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['post', 'put']);

        $habito = $this->Habitos->find()
            ->where(['id_habito' => (int)$id])
            ->first();

        if (!$habito) {
            $this->response = $this->response->withStatus(404);
            $this->set(['ok' => false, 'error' => 'Hábito no encontrado']);
            $this->viewBuilder()->setOption('serialize', ['ok', 'error']);
            return;
        }

        $habito = $this->Habitos->patchEntity($habito, $this->request->getData());

        if ($this->Habitos->save($habito)) {
            $this->set(['ok' => true, 'data' => $habito]);
            $this->viewBuilder()->setOption('serialize', ['ok', 'data']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set(['ok' => false, 'error' => $habito->getErrors()]);
        $this->viewBuilder()->setOption('serialize', ['ok', 'error']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $habito = $this->Habitos->find()
            ->where(['id_habito' => (int)$id])
            ->first();

        if (!$habito) {
            $this->response = $this->response->withStatus(404);
            $this->set(['ok' => false, 'error' => 'Hábito no encontrado']);
            $this->viewBuilder()->setOption('serialize', ['ok', 'error']);
            return;
        }

        $eliminado = $this->Habitos->delete($habito);

        $this->set(['ok' => (bool)$eliminado]);
        $this->viewBuilder()->setOption('serialize', ['ok']);
    }
}