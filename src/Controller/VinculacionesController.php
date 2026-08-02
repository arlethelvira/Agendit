<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use Cake\Utility\Text;
use Cake\Utility\Security;



/**
 * Controlador encargado del sistema
 * de vinculación entre especialistas y socios.
 */
class VinculacionesController extends AppController
{
        private $CodigoInvitacion;

    private $Vinculaciones;

    /**
     * Carga modelos adicionales.
     */
public function initialize(): void
{
    parent::initialize();


    /*
     * Cargamos el modelo CodigoInvitacion
     * y lo guardamos en una propiedad.
     */
    $this->CodigoInvitacion = 
        $this->fetchTable('CodigoInvitacion');


    /*
     * Cargamos el modelo Vinculaciones.
     */
    $this->Vinculaciones =
        $this->fetchTable('Vinculaciones');

}
    /**
 * Muestra el formulario para que un socio
 * ingrese un código de invitación.
 */
public function ingresarCodigo()
{

    /*
     * Este método solamente carga la vista.
     *
     * La validación se hará en validarCodigo().
     */

}
    /**
 * Genera un código de invitación para un especialista.
 *
 * El especialista comparte este código
 * con un socio para que pueda vincularse.
 */
public function generarCodigo()
{

    /*
     * Por ahora ponemos un especialista fijo
     * solo para probar.
     *
     * Después lo cambiaremos por el usuario
     * que inició sesión.
     */
    $idEspecialista = 1;


    /*
     * Generamos un código aleatorio.
     *
     * Ejemplo:
     * A7K92P
     */
  $codigoGenerado = strtoupper(
    substr(bin2hex(random_bytes(4)), 0, 6)
 );


    /*
     * Creamos una nueva entidad.
     *
     * Una entidad representa un registro nuevo
     * que todavía no está guardado.
     */
    $codigo = $this->CodigoInvitacion->newEmptyEntity();



    /*
     * Llenamos los datos que se guardarán
     * en la tabla codigo_invitacion.
     */
    $codigo->id_especialista = $idEspecialista;

    $codigo->codigo = $codigoGenerado;

    /*
     * Expira después de 7 días.
     */
    $codigo->fecha_expiracion =
        FrozenTime::now()->addDays(7);


    /*
     * Nadie lo ha usado todavía.
     */
    $codigo->usado = false;


    /*
     * Estado inicial.
     */
    $codigo->estado = 'ACTIVO';



    /*
     * Guardamos en PostgreSQL.
     */
    if ($this->CodigoInvitacion->save($codigo)) {


        $this->Flash->success(
            'Código generado correctamente.'
        );


        /*
         * Mandamos el código a la vista
         * para mostrarlo.
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
 * Valida un código de invitación
 * y crea la relación entre socio y especialista.
 */
public function validarCodigo()
{

    /*
     * Verificamos que la petición sea POST.
     *
     * No queremos que alguien ejecute esto
     * escribiendo la URL directamente.
     */
    if (!$this->request->is('post')) {

        return $this->redirect([
            'action' => 'ingresarCodigo'
        ]);

    }


    /*
     * Obtenemos los datos enviados
     * desde el formulario.
     *
     * Ejemplo:
     *
     * codigo = ABC123
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
     * Si no existe el código
     */
    if (!$codigo) {

        $this->Flash->error(
            'El código no existe.'
        );

        return $this->redirect([
            'action'=>'ingresarCodigo'
        ]);

    }



    /*
     * Verificamos si ya fue utilizado.
     */
    if ($codigo->usado == true) {


        $this->Flash->error(
            'Este código ya fue utilizado.'
        );


        return $this->redirect([
            'action'=>'ingresarCodigo'
        ]);

    }



    /*
     * Verificamos que siga activo.
     */
    if ($codigo->estado !== 'ACTIVO') {


        $this->Flash->error(
            'Este código ya no está disponible.'
        );


        return $this->redirect([
            'action'=>'ingresarCodigo'
        ]);

    }



    /*
     * Revisamos si ya expiró.
     */
    if (
        FrozenTime::now()
        > $codigo->fecha_expiracion
    ) {


        /*
         * Actualizamos el estado.
         */
        $codigo->estado = 'EXPIRADO';


        $this->CodigoInvitacion
            ->save($codigo);



        $this->Flash->error(
            'El código expiró.'
        );


        return $this->redirect([
            'action'=>'ingresarCodigo'
        ]);

    }



    /*
     * ===================================
     *
     * AQUÍ YA SABEMOS QUE EL CÓDIGO SIRVE
     *
     * Ahora creamos la vinculación.
     *
     * ===================================
     */


    /*
     * Temporalmente ponemos un usuario fijo.
     *
     * Después será el usuario que inició sesión.
     */
    $idUsuario = 1;



    /*
     * Creamos una nueva vinculación.
     */
    $vinculacion =
        $this->Vinculaciones
        ->newEmptyEntity();



    /*
     * Guardamos la relación:
     *
     * Usuario
     *
     * +
     *
     * Especialista
     */
    $vinculacion->id_usuario =
        $idUsuario;


    $vinculacion->id_especialista =
        $codigo->id_especialista;


    $vinculacion->fecha_inicio =
        FrozenTime::now();


    $vinculacion->estado =
        'ACTIVA';



    /*
     * Guardamos la vinculación.
     */
    if (
        $this->Vinculaciones
        ->save($vinculacion)
    ) {



        /*
         * Marcamos el código como usado.
         */
        $codigo->usado = true;

        $codigo->estado = 'USADO';



        $this->CodigoInvitacion
            ->save($codigo);



        $this->Flash->success(
            'Te vinculaste correctamente.'
        );



       /* return $this->redirect([
            'action'=>'misSocios'
        ]);*/
        return $this->redirect([
    'action'=>'ingresarCodigo'
]);

    }



    /*
     * Si falla la creación.
     */
    $this->Flash->error(
        'No se pudo crear la vinculación.'
    );


}

}