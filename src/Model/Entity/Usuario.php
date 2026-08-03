<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Entity de Usuario.
 *
 * Representa un registro de la tabla usuario.
 */
class Usuario extends Entity
{

    /**
     * Campos que pueden ser modificados
     * mediante patchEntity().
     *
     * El * permite todos los campos,
     * excepto los que bloqueemos.
     */
    protected array $_accessible = [

        '*' => true,

        /*
         * El ID lo genera PostgreSQL,
         * por eso no debe modificarse
         * desde formularios.
         */
        'id_usuario' => false,
    ];


    /**
     * Encripta automáticamente
     * la contraseña antes de guardarla.
     *
     * Ejemplo:
     *
     * Entrada:
     * 123456
     *
     * Se guarda:
     * $2y$10$8fj39....
     *
     * Así nunca se almacena
     * la contraseña real.
     */
    protected function _setContrasena(string $contrasena): ?string
    {

        /*
         * Si el campo viene vacío
         * no hacemos nada.
         */
        if (strlen($contrasena) > 0) {


            return (new DefaultPasswordHasher())
                ->hash($contrasena);

        }


        return null;
    }

}