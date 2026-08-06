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

        $builder->connect('/', [
            'controller' => 'Pages', 
            'action' => 'display', 'index']);

        $builder->connect('/habitos', [
            'controller' => 'Habitos', 
            'action' => 'index']); 

        $builder->connect('/habitos/{action}/*', 
        ['controller' => 'Habitos']);

        $builder->connect('/*', [
            'controller' => 'Pages', 
            'action' => 'root']);  


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