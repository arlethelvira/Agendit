<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class AdminController extends AppController
{
    private $Especialistas;

    public function initialize(): void
    {
        parent::initialize();

        // Cargamos la tabla especialista
        $this->Especialistas = $this->fetchTable('Especialistas');
    }

    /**
     * Panel principal del administrador.
     */
public function index()
{
    /*
     * Buscamos los especialistas que están
     * pendientes de aprobación.
     *
     * status = 0 significa:
     * pendiente
     */
    $especialistas = $this->Especialistas
        ->find()
        ->contain([
            'Usuarios',
            'TipoEspecialistas'
        ])
        ->where([
            'Especialistas.status' => 0
        ])
        ->all();

    /*
     * Mandamos la variable a la vista:
     *
     * templates/Admin/index.php
     */
    $this->set(compact('especialistas'));
}

    /**
     * Aceptar especialista.
     */
public function aceptar($id = null)
{
    $this->request->allowMethod(['post']);

    // Comprobamos que recibimos el ID
    if ($id === null) {
        $this->Flash->error('No se recibió el ID del especialista.');
        return $this->redirect(['action' => 'index']);
    }

    // Buscamos al especialista
    $especialista = $this->Especialistas
        ->find()
        ->where([
            'id_especialista' => (int)$id
        ])
        ->first();

    if (!$especialista) {
        $this->Flash->error('Especialista no encontrado.');
        return $this->redirect(['action' => 'index']);
    }

    // 1 = aceptado
    $especialista->status = 1;

    if ($this->Especialistas->save($especialista)) {

        $this->Flash->success(
            'Especialista aceptado correctamente.'
        );

    } else {

        $this->Flash->error(
            'No se pudo aceptar al especialista.'
        );
    }

    return $this->redirect([
        'action' => 'index'
    ]);
}

    /**
     * Rechazar especialista.
     */
public function rechazar($id = null)
{
    $this->request->allowMethod(['post']);

    if ($id === null) {
        $this->Flash->error('No se recibió el ID del especialista.');
        return $this->redirect(['action' => 'index']);
    }

    $especialista = $this->Especialistas
        ->find()
        ->where([
            'id_especialista' => (int)$id
        ])
        ->first();

    if (!$especialista) {
        $this->Flash->error('Especialista no encontrado.');
        return $this->redirect(['action' => 'index']);
    }

    // 2 = rechazado
    $especialista->status = 2;

    if ($this->Especialistas->save($especialista)) {

        $this->Flash->success(
            'Especialista rechazado correctamente.'
        );

    } else {

        $this->Flash->error(
            'No se pudo rechazar al especialista.'
        );
    }

    return $this->redirect([
        'action' => 'index'
    ]);
}
}