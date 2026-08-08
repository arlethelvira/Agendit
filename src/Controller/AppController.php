<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        /*
         * Acciones que pueden abrirse
         * sin iniciar sesión.
         */
        $accionesPublicas = [
            'login',
            'register',
            'registroEspecialista'
        ];

        $accionActual = $this->request->getParam('action');

        /*
         * Si es una acción pública,
         * dejamos continuar.
         */
        if (in_array($accionActual, $accionesPublicas, true)) {
            return;
        }

        /*
         * Revisamos si existe sesión.
         */
        $usuario = $this->usuarioActual();

        /*
         * Si no hay sesión,
         * mandamos al login.
         */
        if (!$usuario) {

            $this->Flash->error(
                'Debes iniciar sesión.'
            );

            $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);

            return;
        }
    }

    /**
     * Obtener usuario actualmente autenticado.
     */
    protected function usuarioActual(): ?array
    {
        return $this->request
            ->getSession()
            ->read('Usuario');
    }

    /**
     * Verificar que el usuario tenga uno
     * de los roles permitidos.
     */
    protected function requiereRol(array $roles): ?ResponseInterface
    {
        $usuario = $this->usuarioActual();

        /*
         * Si no hay sesión.
         */
        if (!$usuario) {

            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }

        /*
         * Si el rol no está permitido.
         */
        if (!in_array($usuario['rol'], $roles, true)) {

            $this->Flash->error(
                'No tienes permiso para acceder a esta sección.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /*
         * Tiene permiso.
         */
        return null;
    }
}