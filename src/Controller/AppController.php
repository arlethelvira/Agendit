<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Controlador base de toda la aplicación.
 *
 * Todos los demás controladores heredan
 * de esta clase.
 */
class AppController extends Controller
{

    /**
     * Inicialización general.
     */
    public function initialize(): void
    {
        parent::initialize();

        /*
         * Componente para mostrar mensajes.
         */
        $this->loadComponent('Flash');
    }


    /**
     * Se ejecuta antes de cada acción.
     *
     * Aquí verificaremos que exista
     * una sesión iniciada.
     */
    public function beforeFilter(EventInterface $event)
    {

        parent::beforeFilter($event);


        /*
         * Acciones públicas.
         *
         * Estas páginas pueden abrirse
         * sin iniciar sesión.
         */
        $accionesPublicas = [

            'login',

            'register',

            'registroEspecialista'

        ];


        /*
         * Acción actual.
         */
        $accionActual =
            $this->request
            ->getParam('action');


        /*
         * Si la acción es pública,
         * dejamos continuar.
         */
        if (in_array($accionActual, $accionesPublicas)) {

            return;

        }


        /*
         * Revisamos si existe la sesión.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');


        /*
         * Si no hay sesión,
         * regresamos al login.
         */
        if (!$usuario) {

            $this->Flash->error(
                'Debes iniciar sesión.'
            );

            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);

        }

    }

}