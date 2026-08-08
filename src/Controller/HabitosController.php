<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Habitos Controller
 *
 * Controlador encargado de administrar
 * los hábitos del usuario.
 *
 * También contiene la vista del calendario.
 */
class HabitosController extends AppController
{
    /**
     * Inicialización del controlador.
     */
    public function initialize(): void
    {
        parent::initialize();

        /*
         * Todas las acciones son JSON,
         * excepto calendario, que muestra
         * una vista HTML.
         */
        if ($this->request->getParam('action') !== 'calendario') {
            $this->viewBuilder()->setClassName('Json');
        }
    }


    /**
     * Seguridad del controlador.
     *
     * Solamente los usuarios con rol
     * "usuario" pueden utilizar este módulo.
     */
    public function beforeFilter(EventInterface $event)
    {
        /*
         * IMPORTANTE:
         *
         * Llamamos al beforeFilter del AppController
         * para conservar la seguridad general
         * de sesión iniciada.
         */
        parent::beforeFilter($event);


        /*
         * Obtenemos el usuario actualmente
         * guardado en la sesión.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');


        /*
         * Si no existe sesión,
         * AppController ya se encargó
         * de redirigir al login.
         */
        if (!$usuario) {
            return;
        }


        /*
         * Verificamos el rol.
         *
         * El calendario y los hábitos
         * pertenecen al usuario normal.
         */
        if (($usuario['rol'] ?? null) !== 'usuario') {

            /*
             * Si es especialista o admin,
             * no tiene permiso para entrar
             * a este módulo.
             */
            $this->Flash->error(
                'No tienes permiso para acceder a los hábitos.'
            );

            /*
             * Regresamos al Dashboard.
             */
            $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);

            return;
        }


        /*
         * Permitimos únicamente los métodos
         * que realmente utiliza este controlador.
         */
        $this->request->allowMethod([
            'get',
            'post',
            'put',
            'delete'
        ]);
    }


    /**
     * Muestra el calendario de hábitos.
     *
     * Solamente usuarios normales
     * pueden llegar hasta aquí.
     */
    public function calendario(): void
    {
        /*
         * No necesitamos hacer nada más.
         *
         * La vista calendario.php carga
         * los hábitos mediante JavaScript.
         */
    }


    /**
     * Devuelve los hábitos del usuario
     * actualmente autenticado.
     *
     * IMPORTANTE:
     * Ya NO recibimos id_usuario desde
     * la URL para decidir qué hábitos mostrar.
     */
    public function index()
    {
        /*
         * Obtenemos el usuario de la sesión.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');


        /*
         * Obtenemos su ID.
         */
        $idUsuario = (int)$usuario['id_usuario'];


        /*
         * Buscamos únicamente sus hábitos.
         */
        $habitos = $this->Habitos
            ->find()
            ->where([
                'id_usuario' => $idUsuario
            ])
            ->orderBy([
                'id_habito' => 'DESC'
            ])
            ->all();


        /*
         * Enviamos la respuesta JSON.
         */
        $this->set([
            'ok' => true,
            'data' => $habitos
        ]);

        $this->viewBuilder()
            ->setOption('serialize', [
                'ok',
                'data'
            ]);
    }


    /**
     * Muestra un hábito específico.
     */
    public function view($id = null)
    {
        /*
         * Usuario actual.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');

        $idUsuario = (int)$usuario['id_usuario'];


        /*
         * Buscamos el hábito por ID,
         * PERO también verificamos que
         * pertenezca al usuario actual.
         */
        $habito = $this->Habitos
            ->find()
            ->where([
                'id_habito' => (int)$id,
                'id_usuario' => $idUsuario
            ])
            ->first();


        /*
         * Si no existe o no pertenece
         * al usuario, devolvemos 404.
         */
        if (!$habito) {

            $this->response =
                $this->response->withStatus(404);

            $this->set([
                'ok' => false,
                'error' => 'Hábito no encontrado'
            ]);

            $this->viewBuilder()
                ->setOption('serialize', [
                    'ok',
                    'error'
                ]);

            return;
        }


        /*
         * Hábito encontrado.
         */
        $this->set([
            'ok' => true,
            'data' => $habito
        ]);

        $this->viewBuilder()
            ->setOption('serialize', [
                'ok',
                'data'
            ]);
    }


    /**
     * Crea un nuevo hábito.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);


        /*
         * Obtenemos el usuario de la sesión.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');


        $idUsuario = (int)$usuario['id_usuario'];


        /*
         * Creamos un nuevo hábito.
         */
        $habito = $this->Habitos
            ->newEmptyEntity();


        /*
         * Copiamos los datos enviados
         * desde el formulario.
         */
        $habito = $this->Habitos
            ->patchEntity(
                $habito,
                $this->request->getData()
            );


        /*
         * MUY IMPORTANTE:
         *
         * El usuario NO decide a qué usuario
         * pertenece el hábito.
         *
         * Lo obtenemos de la sesión.
         */
        $habito->id_usuario = $idUsuario;


        /*
         * Guardamos.
         */
        if ($this->Habitos->save($habito)) {

            $this->response =
                $this->response->withStatus(201);

            $this->set([
                'ok' => true,
                'data' => $habito
            ]);

            $this->viewBuilder()
                ->setOption('serialize', [
                    'ok',
                    'data'
                ]);

            return;
        }


        /*
         * Error al guardar.
         */
        $this->response =
            $this->response->withStatus(400);

        $this->set([
            'ok' => false,
            'error' => $habito->getErrors()
        ]);

        $this->viewBuilder()
            ->setOption('serialize', [
                'ok',
                'error'
            ]);
    }


    /**
     * Edita un hábito existente.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod([
            'post',
            'put'
        ]);


        /*
         * Usuario actual.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');

        $idUsuario = (int)$usuario['id_usuario'];


        /*
         * Buscamos el hábito verificando
         * que pertenezca al usuario actual.
         */
        $habito = $this->Habitos
            ->find()
            ->where([
                'id_habito' => (int)$id,
                'id_usuario' => $idUsuario
            ])
            ->first();


        /*
         * Si no existe o pertenece
         * a otro usuario.
         */
        if (!$habito) {

            $this->response =
                $this->response->withStatus(404);

            $this->set([
                'ok' => false,
                'error' => 'Hábito no encontrado'
            ]);

            $this->viewBuilder()
                ->setOption('serialize', [
                    'ok',
                    'error'
                ]);

            return;
        }


        /*
         * Actualizamos los datos.
         */
        $habito = $this->Habitos
            ->patchEntity(
                $habito,
                $this->request->getData()
            );


        /*
         * Guardamos.
         */
        if ($this->Habitos->save($habito)) {

            $this->set([
                'ok' => true,
                'data' => $habito
            ]);

            $this->viewBuilder()
                ->setOption('serialize', [
                    'ok',
                    'data'
                ]);

            return;
        }


        /*
         * Error.
         */
        $this->response =
            $this->response->withStatus(400);

        $this->set([
            'ok' => false,
            'error' => $habito->getErrors()
        ]);

        $this->viewBuilder()
            ->setOption('serialize', [
                'ok',
                'error'
            ]);
    }


    /**
     * Elimina un hábito.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod([
            'post',
            'delete'
        ]);


        /*
         * Usuario actual.
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');

        $idUsuario = (int)$usuario['id_usuario'];


        /*
         * Buscamos el hábito verificando
         * que pertenezca al usuario actual.
         */
        $habito = $this->Habitos
            ->find()
            ->where([
                'id_habito' => (int)$id,
                'id_usuario' => $idUsuario
            ])
            ->first();


        /*
         * Si no existe o pertenece
         * a otro usuario.
         */
        if (!$habito) {

            $this->response =
                $this->response->withStatus(404);

            $this->set([
                'ok' => false,
                'error' => 'Hábito no encontrado'
            ]);

            $this->viewBuilder()
                ->setOption('serialize', [
                    'ok',
                    'error'
                ]);

            return;
        }


        /*
         * Eliminamos el hábito.
         */
        $eliminado =
            $this->Habitos->delete($habito);


        /*
         * Respondemos con JSON.
         */
        $this->set([
            'ok' => (bool)$eliminado
        ]);

        $this->viewBuilder()
            ->setOption('serialize', [
                'ok'
            ]);
    }
}