<?php
declare(strict_types=1);

namespace App\Controller;


use App\Model\Table\UsuariosTable;

class UsersController extends AppController
{/**
 * Modelo de usuarios.
 *
 * Nos permite acceder a la tabla usuario.
 */
private UsuariosTable $Usuarios;
    /**
     * @var \Cake\Controller\Component|\Cake\ORM\Table|mixed|null
     */
    public function initialize(): void
{
    parent::initialize();
    

    /*
     * Cargamos la tabla usuario.
     *
     * Esto nos permitirá hacer:
     *
     * newEmptyEntity()
     * save()
     * find()
     */
    $this->Usuarios = $this->fetchTable('Usuarios');
}
   /* public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

$this->Authentication->allowUnauthenticated([
    'login',
    'register'
]);
}

    public function login()
    {
        $result = $this->Authentication->getResult();
        // If the user is logged in send them away.
        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/';
            return $this->redirect($target);
        }
        if ($this->request->is('post')) {
            $this->Flash->error('Invalid username or password');
        }
    }

public function logout()
{
    $this->Authentication->logout();

    return $this->redirect([
        'action' => 'login'
    ]);
}*/
   /**
 * Registro de un usuario normal.
 *
 * Este método muestra el formulario
 * y guarda un nuevo usuario cuando
 * se envía la información.
 */

   /**
 * Inicio de sesión.
 *
 * Verifica las credenciales del usuario
 * y crea la sesión correspondiente.
 */
public function login()
{
    $this->viewBuilder()->setLayout('auth');

    /*
     * Si únicamente se abrió la página,
     * solamente mostramos el formulario.
     */
    if (!$this->request->is('post')) {
        return;
    }


    /*
     * Obtenemos los datos enviados
     * desde el formulario.
     */
    $email = $this->request->getData('email');

    $contrasena = $this->request->getData('contrasena');


    /*
     * Buscamos el usuario por correo.
     */
    $usuario = $this->Usuarios
        ->find()
        ->where([
            'email' => $email
        ])
        ->first();


    /*
     * Si no existe.
     */
    if (!$usuario) {

        $this->Flash->error(
            'Correo o contraseña incorrectos.'
        );

        return;
    }


    /*
     * Verificamos la contraseña.
     */
    if (
        !password_verify(
            $contrasena,
            $usuario->contrasena
        )
    ) {

        $this->Flash->error(
            'Correo o contraseña incorrectos.'
        );

        return;

    }


    /*
     * Guardamos la información
     * del usuario en sesión.
     */
    $this->request
        ->getSession()
        ->write(
            'Usuario',
            [
                'id_usuario' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'rol' => $usuario->rol
            ]
        );


    /*
     * Redirigimos al Dashboard.
     *
     * Después podremos enviar
     * a cada rol a un panel distinto.
     */
    return $this->redirect('/');
}



/**
 * Cierra la sesión del usuario.
 */
/**
 * Cierra la sesión del usuario.
 */
public function logout()
{
    /*
     * Eliminamos toda la sesión.
     */
    $this->request
        ->getSession()
        ->destroy();

    $this->Flash->success(
        'Sesión cerrada correctamente.'
    );

    return $this->redirect([
        'action' => 'login'
    ]);
}
public function register()
{
    $this->viewBuilder()->setLayout('auth');
    /*
     * Creamos una entidad vacía.
     *
     * Una entidad representa un registro
     * de la tabla usuario.
     */
    $usuario = $this->Usuarios->newEmptyEntity();

    /*
     * Verificamos si el formulario
     * fue enviado mediante POST.
     */
    if ($this->request->is('post')) {

        /*
         * Obtenemos todos los datos
         * enviados desde el formulario.
         */
        $datos = $this->request->getData();

        /*
         * Copiamos los datos recibidos
         * hacia la entidad.
         *
         * Aquí también se ejecutan
         * las validaciones definidas
         * en UsuariosTable.
         */
        $usuario = $this->Usuarios->patchEntity(
            $usuario,
            $datos
        );

        /*
         * Asignamos el rol del usuario.
         *
         * No permitimos que el usuario
         * envíe este dato desde el formulario.
         */
        $usuario->rol = 'usuario';

        /*
         * Cuenta activa.
         */
        $usuario->status = 1;

        /*
         * Intentamos guardar.
         */
        if ($this->Usuarios->save($usuario)) {

            $this->Flash->success(
                'Cuenta creada correctamente.'
            );

            /*
             * Redirigimos al login.
             */
            return $this->redirect([
                'action' => 'login'
            ]);
        }

        /*
         * Si algo falla.
         */
        $this->Flash->error(
            'No fue posible crear la cuenta.'
        );
    }

    /*
     * Enviamos la entidad
     * a la vista.
     */
    $this->set(compact('usuario'));
}
/**
 * Registro de especialista.
 *
 * Este proceso crea:
 *
 * 1. Usuario
 * 2. Especialista relacionado
 *
 */
public function registroEspecialista()
{
    $this->viewBuilder()->setLayout('auth');

    /*
     * Creamos entidad vacía de usuario.
     *
     * El especialista primero debe existir
     * como usuario dentro del sistema.
     */
    $usuario = $this->Usuarios->newEmptyEntity();



    /*
     * Obtenemos los tipos de especialista
     * para llenar el select del formulario.
     *
     * Ejemplo:
     *
     * 1 => Nutriólogo
     * 2 => Coach
     * 3 => Psicólogo
     *
     */
    $tiposEspecialista = $this
        ->fetchTable('TipoEspecialistas')
        ->find('list', [
            'keyField'=>'id_tipo',
            'valueField'=>'nombre'
        ])
        ->toArray();



    /*
     * Cuando el formulario sea enviado.
     */
    if ($this->request->is('post')) {


        /*
         * Datos recibidos del formulario.
         */
        $datos = $this->request->getData();



        /*
         * Guardamos datos del usuario.
         */
        $usuario = $this->Usuarios->patchEntity(
            $usuario,
            $datos
        );



        /*
         * El rol NO viene del formulario.
         *
         * Lo asignamos nosotros.
         */
        $usuario->rol = 'especialista';



        /*
         * Cuenta pendiente mientras
         * esperamos validar credenciales.
         */
        $usuario->status = 1;



        /*
         * Guardamos usuario.
         */
        if ($this->Usuarios->save($usuario)) {



            /*
             * Ahora creamos el especialista.
             */
            $especialista = $this
                ->fetchTable('Especialistas')
                ->newEmptyEntity();



            /*
             * Relacionamos con el usuario creado.
             */
            $especialista->id_usuario =
                $usuario->id_usuario;



            /*
             * Datos profesionales.
             */
            $especialista->id_tipo =
                $datos['id_tipo'];


            $especialista->cedula_profesional =
                $datos['cedula_profesional'];



            /*
             * 0 = pendiente de aprobación
             */
            $especialista->status = 0;



            /*
             * Guardamos especialista.
             */
            if (
                $this
                ->fetchTable('Especialistas')
                ->save($especialista)
            ) {


                $this->Flash->success(
                    'Registro enviado correctamente. Espera aprobación.'
                );


                return $this->redirect([
                    'action'=>'login'
                ]);

            }


        }


        $this->Flash->error(
            'No fue posible registrar especialista.'
        );

    }



    /*
     * Mandamos datos a la vista.
     */
    $this->set(compact(
        'usuario',
        'tiposEspecialista'
    ));

}


}
