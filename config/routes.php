<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);


    $routes->scope('/', function (RouteBuilder $builder): void {


        // Login
        $builder->connect('/login', [
            'controller' => 'Users',
            'action' => 'login'
        ]);


        // Logout
        $builder->connect('/logout', [
            'controller' => 'Users',
            'action' => 'logout'
        ]);


        // Dashboard principal
        $builder->connect('/', [
            'controller' => 'Dashboard',
            'action' => 'index'
        ]);


        /*
         * Rutas automáticas de CakePHP
         *
         * Esto permite:
         *
         * /vinculaciones/generar-codigo
         *
         * buscar:
         *
         * VinculacionesController
         * generarCodigo()
         * abajooo
         */

   // Páginas estáticas
$builder->connect('/pages/*', [
    'controller' => 'Pages',
    'action' => 'display'
]);
        $builder->fallbacks();

    });

};