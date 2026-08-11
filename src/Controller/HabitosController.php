<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

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
        if (!in_array($this->request->getParam('action'), ['calendario', 'asignar'], true)) {
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
         * Verificamos el rol según la acción.
         *
         * "asignar" es exclusiva del especialista;
         * el resto del módulo (calendario, CRUD)
         * es exclusivo del usuario normal.
         */
        $action = $this->request->getParam('action');

        if ($action === 'asignar') {

            if (($usuario['rol'] ?? null) !== 'especialista') {

                $this->Flash->error(
                    'No tienes permiso para acceder a esta acción.'
                );

                $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);

                return;
            }

        } else {

            if (($usuario['rol'] ?? null) !== 'usuario') {

                $this->Flash->error(
                    'No tienes permiso para acceder a los hábitos.'
                );

                $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);

                return;
            }
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
     * Permite que un especialista asigne
     * un hábito a uno de sus socios vinculados.
     *
     * Acceso exclusivo para especialistas.
     */
    public function asignar($idUsuario = null)
    {
        $this->request->allowMethod([
            'get',
            'post'
        ]);


        /*
         * Usuario actualmente en sesión
         * (debe ser un especialista).
         */
        $usuario = $this->request
            ->getSession()
            ->read('Usuario');

        $idUsuarioSesion = (int)$usuario['id_usuario'];


        /*
         * IMPORTANTE:
         *
         * id_especialista (tabla especialista) NO es
         * lo mismo que id_usuario (tabla usuario).
         * Buscamos el id_especialista real a partir
         * del id_usuario de la sesión.
         */
        $especialista = TableRegistry::getTableLocator()
            ->get('Especialistas')
            ->find()
            ->where(['id_usuario' => $idUsuarioSesion])
            ->first();

        if (!$especialista) {

            $this->Flash->error(
                'No se encontró tu perfil de especialista.'
            );

            $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);

            return;
        }

        $idEspecialista = (int)$especialista->id_especialista;


        /*
         * Determinamos el color del hábito según
         * la especialidad del especialista, usando
         * las mismas clases Bootstrap que maneja
         * el calendario (bg-success, bg-primary, bg-purple).
         */
        $coloresPorTipo = [
            1 => 'bg-success', // Nutriólogo
            2 => 'bg-primary', // Coach
            3 => 'bg-purple',  // Psicólogo
        ];

        $colorHabito = $coloresPorTipo[(int)$especialista->id_tipo] ?? 'bg-secondary';


        /*
         * Verificamos que el socio exista
         * y esté realmente vinculado (activo)
         * a este especialista antes de dejarlo
         * asignar nada.
         */
        $vinculacion = TableRegistry::getTableLocator()
            ->get('Vinculaciones')
            ->find()
            ->where([
                'id_especialista' => $idEspecialista,
                'id_usuario' => (int)$idUsuario,
                'estado' => 'ACTIVA'
            ])
            ->first();

        if (!$vinculacion) {

            $this->Flash->error(
                'Este usuario no está vinculado contigo.'
            );

            $this->redirect([
                'controller' => 'Vinculaciones',
                'action' => 'misSocios'
            ]);

            return;
        }


        /*
         * Si es GET, solo mostramos el formulario.
         */
        if ($this->request->is('get')) {

            $this->set([
                'idUsuario' => (int)$idUsuario
            ]);

            return;
        }


        /*
         * POST: creamos el hábito para el socio.
         */
        $habito = $this->Habitos->newEmptyEntity();

        $habito = $this->Habitos->patchEntity(
            $habito,
            $this->request->getData()
        );


        /*
         * El hábito pertenece al socio (usuario),
         * pero queda registrado quién lo creó
         * y qué especialista lo asignó.
         */
        $habito->id_usuario = (int)$idUsuario;
        $habito->id_especialista = $idEspecialista;
        $habito->creado_por = 'ESPECIALISTA';
        $habito->color = $colorHabito;

        if ($this->Habitos->save($habito)) {

            $this->Flash->success(
                'Hábito asignado correctamente.'
            );

            $this->redirect([
                'controller' => 'Vinculaciones',
                'action' => 'misSocios'
            ]);

            return;
        }


        /*
         * Error al guardar: regresamos
         * al formulario con los errores.
         */
        $this->Flash->error(
            'No se pudo asignar el hábito.'
        );

        $this->set([
            'idUsuario' => (int)$idUsuario
        ]);
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