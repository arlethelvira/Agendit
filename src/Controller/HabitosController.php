<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Habitos Controller
 *
 * Backend JSON puro para el CRUD (index/view/add/edit/delete),
 * más la acción `calendario` que sí renderiza una vista HTML
 * (templates/Habitos/calendario.php).
 *
 * Rutas por convención de CakePHP (fallbacks), ej:
 *   GET    /habitos              -> index (opcional ?id_usuario=4)
 *   GET    /habitos/view/10      -> view
 *   POST   /habitos/add          -> add
 *   POST   /habitos/edit/10      -> edit
 *   POST   /habitos/delete/10    -> delete
 *   GET    /habitos/calendario   -> calendario (vista HTML)
 */
class HabitosController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Todas las acciones responden JSON, EXCEPTO calendario,
        // que sí necesita renderizar una vista HTML normal.
        if ($this->request->getParam('action') !== 'calendario') {
            $this->viewBuilder()->setClassName('Json');
        }
    }

    /**
     * Vista del calendario de hábitos (FullCalendar).
     * El JS de la página (webroot/js/pages/app-calendar.js) es quien
     * llama al resto de acciones (index/add/edit/delete) vía fetch.
     */
    public function calendario(): void
    {
        // Nada que preparar aquí: el JS carga los hábitos por su cuenta.
    }

    public function beforeFilter(EventInterface $event)
    {
        // OJO - a propósito NO se llama a parent::beforeFilter($event) aquí.
        // El AppController de este proyecto exige sesión iniciada
        // (redirige a /login) para cualquier acción que no esté en su
        // lista de $accionesPublicas. Este controller es un backend JSON
        // puro que todavía no está conectado al login real, así que nos
        // saltamos ese chequeo por ahora. Cuando el módulo se integre con
        // el flujo de sesión de Arleth, hay que:
        //   1. Volver a llamar parent::beforeFilter($event)
        //   2. Agregar las acciones necesarias a $accionesPublicas en
        //      AppController, o adaptar la validación de sesión aquí.

        if ($this->components()->has('Authentication')) {
            $this->Authentication->allowUnauthenticated([
                'index', 'view', 'add', 'edit', 'delete', 'calendario',
            ]);
        }

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