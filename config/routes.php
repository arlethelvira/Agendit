<?php
/**
 * Routes configuration.
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // Rutas de autenticación
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

        // Página principal del sistema (Agendit)
        $builder->connect('/', [
            'controller' => 'Dashboard',
            'action' => 'index'
        ]);

        // Mantiene las páginas de ejemplo de la plantilla
        $builder->connect('/*', [
            'controller' => 'Pages',
            'action' => 'root'
        ]);

        $builder->connect('/pages/*', 'Pages::display');

        // Rutas automáticas
        $builder->fallbacks();
    });

};