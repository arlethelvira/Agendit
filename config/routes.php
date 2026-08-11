<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    // Usamos URLs con guiones:
    // /registro-especialista
    // /generar-codigo
    // /mis-socios
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // =========================================================
        // LOGIN
        // =========================================================

        $builder->connect('/login', [
            'controller' => 'Users',
            'action' => 'login'
        ]);

        // =========================================================
        // LOGOUT
        // =========================================================

        $builder->connect('/logout', [
            'controller' => 'Users',
            'action' => 'logout'
        ]);

        // =========================================================
        // DASHBOARD PRINCIPAL
        // =========================================================

        $builder->connect('/', [
            'controller' => 'Dashboard',
            'action' => 'index'
        ]);

        // =========================================================
        // HÁBITOS / CALENDARIO
        // =========================================================

        $builder->connect('/habitos', [
            'controller' => 'Habitos',
            'action' => 'index'
        ]);

        $builder->connect('/habitos/{action}/*', [
            'controller' => 'Habitos'
        ]);

        // =========================================================
// TAREAS / CALENDARIO
// =========================================================

        $builder->connect('/tareas', [
    'controller' => 'Tareas',
    'action' => 'vista'
        ]);

        $builder->connect('/tareas/{action}/*', [
            'controller' => 'Tareas'
        ]);

        // =========================================================
        // VINCULACIONES
        // =========================================================
        //
        // Ejemplos:
        //
        // /vinculaciones/generar-codigo
        // /vinculaciones/ingresar-codigo
        // /vinculaciones/validar-codigo
        // /vinculaciones/mis-socios
        //

        $builder->connect('/vinculaciones/{action}/*', [
            'controller' => 'Vinculaciones'
        ]);

        // =========================================================
        // ADMINISTRADOR
        // =========================================================
        //
        // /admin
        // /admin/aceptar/5
        // /admin/rechazar/5
        //

        $builder->connect('/admin', [
            'controller' => 'Admin',
            'action' => 'index'
        ]);

        $builder->connect('/admin/{action}/*', [
            'controller' => 'Admin'
        ]);

        // =========================================================
        // PÁGINAS
        // =========================================================

        $builder->connect('/pages/*', [
            'controller' => 'Pages',
            'action' => 'display'
        ]);

        // =========================================================
        // FALLBACKS DE CAKEPHP
        // =========================================================

        $builder->fallbacks();

    });
};