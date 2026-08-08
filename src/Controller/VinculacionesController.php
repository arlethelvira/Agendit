<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

/**
 * Controlador encargado del sistema
 * de vinculación entre especialistas y socios.
 *
 * Seguridad:
 *
 * ESPECIALISTA:
 * - generarCodigo()
 * - misSocios()
 *
 * USUARIO:
 * - ingresarCodigo()
 * - validarCodigo()
 */
class VinculacionesController extends AppController
{
    /**
     * Modelo encargado de los códigos de invitación.
     */
    private $CodigoInvitacion;

    /**
     * Modelo encargado de las vinculaciones
     * entre usuarios y especialistas.
     */
    private $Vinculaciones;


    /**
     * Inicialización del controlador.
     *
     * Cargamos los modelos que utilizaremos
     * durante las diferentes acciones.
     */
    public function initialize(): void
    {
        parent::initialize();

        /*
         * Modelo de códigos de invitación.
         */
        $this->CodigoInvitacion =
            $this->fetchTable('CodigoInvitacion');

        /*
         * Modelo de vinculaciones.
         */
        $this->Vinculaciones =
            $this->fetchTable('Vinculaciones');
    }


    /**
     * ==========================================================
     * INGRESAR CÓDIGO
     * ==========================================================
     *
     * Esta pantalla es para el USUARIO.
     *
     * El usuario escribe el código que recibió
     * de un especialista.
     */
    public function ingresarCodigo()
    {
        /*
         * Obtenemos los datos del usuario
         * que inició sesión.
         */
        $usuario = $this->usuarioActual();

        /*
         * Verificamos que el usuario tenga
         * el rol correcto.
         *
         * Esta acción solamente pertenece
         * al usuario normal.
         */
        if ($usuario['rol'] !== 'usuario') {

            $this->Flash->error(
                'No tienes permiso para ingresar códigos de invitación.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        /*
         * Esta acción solamente muestra
         * el formulario.
         *
         * La validación del código se realiza
         * en validarCodigo().
         */
    }


    /**
     * ==========================================================
     * GENERAR CÓDIGO
     * ==========================================================
     *
     * Esta acción solamente puede ser utilizada
     * por un ESPECIALISTA.
     *
     * El especialista genera un código que
     * posteriormente compartirá con un usuario.
     */
public function generarCodigo()
{
    /*
     * Obtenemos el usuario que inició sesión.
     */
    $usuario = $this->usuarioActual();


    /*
     * SEGURIDAD:
     *
     * Solamente un especialista puede
     * acceder a esta sección.
     */
    if ($usuario['rol'] !== 'especialista') {

        $this->Flash->error(
            'No tienes permiso para generar códigos.'
        );

        return $this->redirect([
            'controller' => 'Dashboard',
            'action' => 'index'
        ]);
    }


    /*
     * Si solamente entramos a la página,
     * mostramos la vista sin generar código.
     */
    if (!$this->request->is('post')) {
        return;
    }


    /*
     * Obtenemos el ID del usuario
     * que inició sesión.
     */
    $idUsuario = $usuario['id_usuario'];


    /*
     * Buscamos el especialista relacionado
     * con el usuario.
     */
    $especialista = $this
        ->fetchTable('Especialistas')
        ->find()
        ->where([
            'id_usuario' => $idUsuario
        ])
        ->first();


    /*
     * Verificamos que exista el perfil
     * de especialista.
     */
    if (!$especialista) {

        $this->Flash->error(
            'No se encontró el perfil de especialista.'
        );

        return $this->redirect([
            'controller' => 'Dashboard',
            'action' => 'index'
        ]);
    }


    /*
     * Obtenemos el ID del especialista.
     */
    $idEspecialista = $especialista->id_especialista;


    /*
     * Generamos un código aleatorio de 6 caracteres.
     */
    $codigoGenerado = strtoupper(
        substr(bin2hex(random_bytes(4)), 0, 6)
    );


    /*
     * Creamos la entidad.
     */
    $codigo = $this->CodigoInvitacion
        ->newEmptyEntity();


    /*
     * Relacionamos el código
     * con el especialista.
     */
    $codigo->id_especialista = $idEspecialista;


    /*
     * Guardamos el código.
     */
    $codigo->codigo = $codigoGenerado;


    /*
     * El código expira en 7 días.
     */
    $codigo->fecha_expiracion =
        FrozenTime::now()->addDays(7);


    /*
     * Todavía no ha sido utilizado.
     */
    $codigo->usado = false;


    /*
     * Estado inicial.
     */
    $codigo->estado = 'ACTIVO';


    /*
     * Guardamos en la base de datos.
     */
    if ($this->CodigoInvitacion->save($codigo)) {

        $this->Flash->success(
            'Código generado correctamente.'
        );

        /*
         * Mandamos el código a la vista.
         */
        $this->set([
            'codigo' => $codigoGenerado
        ]);

    } else {

        $this->Flash->error(
            'No se pudo generar el código.'
        );
    }
}


    /**
     * ==========================================================
     * VALIDAR CÓDIGO
     * ==========================================================
     *
     * Esta acción solamente puede ser ejecutada
     * por un USUARIO.
     *
     * Recibe el código enviado desde el formulario,
     * comprueba que sea válido y crea la vinculación.
     */
    public function validarCodigo()
    {
        /*
         * Obtenemos el usuario actual.
         */
        $usuario = $this->usuarioActual();


        /*
         * SEGURIDAD:
         *
         * Solamente un usuario normal puede
         * validar un código de invitación.
         */
        if ($usuario['rol'] !== 'usuario') {

            $this->Flash->error(
                'No tienes permiso para validar códigos.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        /*
         * Esta acción debe recibir información
         * mediante POST.
         *
         * Así evitamos que alguien simplemente
         * escriba la URL para intentar ejecutar
         * el proceso.
         */
        if (!$this->request->is('post')) {

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }


        /*
         * Obtenemos el código enviado
         * desde el formulario.
         */
        $codigoIngresado =
            $this->request->getData('codigo');


        /*
         * Buscamos el código en la tabla
         * codigo_invitacion.
         */
        $codigo = $this->CodigoInvitacion
            ->find()
            ->where([
                'codigo' => $codigoIngresado
            ])
            ->first();


        /*
         * Si el código no existe.
         */
        if (!$codigo) {

            $this->Flash->error(
                'El código no existe.'
            );

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }


        /*
         * Verificamos si el código
         * ya fue utilizado.
         */
        if ($codigo->usado == true) {

            $this->Flash->error(
                'Este código ya fue utilizado.'
            );

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }


        /*
         * Verificamos que el código
         * continúe activo.
         */
        if ($codigo->estado !== 'ACTIVO') {

            $this->Flash->error(
                'Este código ya no está disponible.'
            );

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }


        /*
         * Verificamos que el código
         * no haya expirado.
         */
        if (FrozenTime::now() > $codigo->fecha_expiracion) {

            /*
             * Cambiamos el estado
             * del código a EXPIRADO.
             */
            $codigo->estado = 'EXPIRADO';

            $this->CodigoInvitacion
                ->save($codigo);


            $this->Flash->error(
                'El código expiró.'
            );

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }


        /*
         * ======================================================
         *
         * EL CÓDIGO ES VÁLIDO
         *
         * Ahora creamos la vinculación.
         *
         * ======================================================
         */


        /*
         * Obtenemos el ID del usuario
         * que inició sesión.
         */
        $idUsuario =
            $usuario['id_usuario'];


        /*
         * Creamos una nueva vinculación.
         */
        $vinculacion =
            $this->Vinculaciones
            ->newEmptyEntity();


        /*
         * Guardamos el usuario que está
         * utilizando el código.
         */
        $vinculacion->id_usuario =
            $idUsuario;


        /*
         * Guardamos el especialista dueño
         * del código.
         */
        $vinculacion->id_especialista =
            $codigo->id_especialista;


        /*
         * Fecha en la que se realizó
         * la vinculación.
         */
        $vinculacion->fecha_inicio =
            FrozenTime::now();


        /*
         * Estado inicial de la vinculación.
         */
        $vinculacion->estado =
            'ACTIVA';


        /*
         * Guardamos la vinculación.
         */
        if ($this->Vinculaciones->save($vinculacion)) {

            /*
             * Marcamos el código como utilizado.
             */
            $codigo->usado = true;

            $codigo->estado = 'USADO';


            /*
             * Guardamos los cambios
             * del código.
             */
            $this->CodigoInvitacion
                ->save($codigo);


            /*
             * Mensaje de éxito.
             */
            $this->Flash->success(
                'Te vinculaste correctamente.'
            );


            /*
             * Regresamos al formulario
             * de ingreso de código.
             */
            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);

        } else {

            /*
             * Si ocurrió un problema
             * al guardar la vinculación.
             */
            $this->Flash->error(
                'No se pudo crear la vinculación.'
            );

            return $this->redirect([
                'action' => 'ingresarCodigo'
            ]);
        }
    }


    /**
     * ==========================================================
     * MIS SOCIOS
     * ==========================================================
     *
     * Esta sección solamente puede ser utilizada
     * por un ESPECIALISTA.
     *
     * Muestra los usuarios que están vinculados
     * con el especialista que inició sesión.
     */
    public function misSocios()
    {
        /*
         * Obtenemos el usuario actual.
         */
        $usuario = $this->usuarioActual();


        /*
         * SEGURIDAD:
         *
         * Solamente un especialista puede
         * consultar sus socios.
         */
        if ($usuario['rol'] !== 'especialista') {

            $this->Flash->error(
                'No tienes permiso para acceder a tus socios.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        /*
         * Obtenemos el ID del usuario
         * que inició sesión.
         */
        $idUsuario =
            $usuario['id_usuario'];


        /*
         * Buscamos el registro de especialista
         * relacionado con ese usuario.
         */
        $especialista = $this
            ->fetchTable('Especialistas')
            ->find()
            ->where([
                'id_usuario' => $idUsuario
            ])
            ->first();


        /*
         * Verificamos que realmente exista
         * el perfil de especialista.
         */
        if (!$especialista) {

            $this->Flash->error(
                'No se encontró el perfil de especialista.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }


        /*
         * Obtenemos el ID del especialista.
         */
        $idEspecialista =
            $especialista->id_especialista;


        /*
         * Buscamos todas las vinculaciones
         * pertenecientes al especialista.
         *
         * contain(['Usuarios']) hace que CakePHP
         * también obtenga la información del usuario
         * relacionado.
         */
        $socios = $this->Vinculaciones
            ->find()
            ->contain([
                'Usuarios'
            ])
            ->where([
                'Vinculaciones.id_especialista' =>
                    $idEspecialista,

                'Vinculaciones.estado' =>
                    'ACTIVA'
            ])
            ->all();


        /*
         * Enviamos los socios a la vista.
         */
        $this->set(compact('socios'));
    }
}