<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\DateTime;

class DashboardController extends AppController
{
    public function index()
    {
        $usuario = $this->usuarioActual();

        if (!$usuario) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }

        $rol = $usuario['rol'] ?? null;


        /*
         * =====================================================
         * DASHBOARD USUARIO
         * =====================================================
         */
        if ($rol === 'usuario') {

            $idUsuario = (int)$usuario['id_usuario'];

            $hoy = DateTime::now()->format('Y-m-d');


            /* -----------------------------
             * TAREAS
             * -----------------------------
             */

            $tareasTable = $this->fetchTable('Tareas');

            $tareas = $tareasTable
                ->find('activasDeUsuario', [
                    'idUsuario' => $idUsuario
                ])
                ->all();


            $tareasPendientes = $tareas->filter(
                fn($tarea) => empty($tarea->fecha_completada)
            );


            $tareasCompletadas = $tareas->filter(
                fn($tarea) => !empty($tarea->fecha_completada)
            );


            $tareasHoy = $tareas->filter(
                function ($tarea) use ($hoy) {

                    if (empty($tarea->fecha_limite)) {
                        return false;
                    }

                    return $tarea
                        ->fecha_limite
                        ->format('Y-m-d') === $hoy;
                }
            );


            $proximasTareas = $tareasTable
                ->find()
                ->where([
                    'Tareas.id_usuario' => $idUsuario,
                    'Tareas.estado' => 'activa',
                    'Tareas.fecha_limite >=' => $hoy,
                    'Tareas.fecha_completada IS' => null
                ])
                ->contain(['Categorias'])
                ->orderBy([
                    'Tareas.fecha_limite' => 'ASC',
                    'Tareas.hora_limite' => 'ASC'
                ])
                ->limit(5)
                ->all();


            /* -----------------------------
             * HÁBITOS
             * -----------------------------
             */

            $habitosTable = $this->fetchTable('Habitos');

            $habitos = $habitosTable
                ->find()
                ->where([
                    'Habitos.id_usuario' => $idUsuario
                ])
                ->orderBy([
                    'Habitos.fecha_creacion' => 'DESC'
                ])
                ->all();


            /* -----------------------------
             * ESTADÍSTICAS
             * -----------------------------
             */

            $totalTareas = $tareas->count();
            $totalPendientes = $tareasPendientes->count();
            $totalCompletadas = $tareasCompletadas->count();
            $totalTareasHoy = $tareasHoy->count();
            $totalHabitos = $habitos->count();


            $progreso = $totalTareas > 0
                ? round(
                    ($totalCompletadas / $totalTareas) * 100
                )
                : 0;


            $this->set(compact(
                'usuario',
                'rol',
                'tareas',
                'tareasPendientes',
                'tareasCompletadas',
                'tareasHoy',
                'proximasTareas',
                'habitos',
                'totalTareas',
                'totalPendientes',
                'totalCompletadas',
                'totalTareasHoy',
                'totalHabitos',
                'progreso'
            ));

            return;
        }


        /*
         * =====================================================
         * DASHBOARD ESPECIALISTA
         * =====================================================
         */
        if ($rol === 'especialista') {

            $idUsuario = (int)$usuario['id_usuario'];


            /*
             * Buscamos el perfil real del especialista.
             */
            $especialista = $this
                ->fetchTable('Especialistas')
                ->find()
                ->where([
                    'id_usuario' => $idUsuario
                ])
                ->first();


            if (!$especialista) {

                $this->Flash->error(
                    'No se encontró tu perfil de especialista.'
                );

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'logout'
                ]);
            }


            $idEspecialista =
                (int)$especialista->id_especialista;


            /*
             * Todas las vinculaciones del especialista.
             */
            $vinculacionesTable =
                $this->fetchTable('Vinculaciones');


            $socios = $vinculacionesTable
                ->find()
                ->contain([
                    'Usuarios'
                ])
                ->where([
                    'Vinculaciones.id_especialista' =>
                        $idEspecialista
                ])
                ->orderBy([
                    'Vinculaciones.id_vinculacion' =>
                        'DESC'
                ])
                ->all();


            /*
             * Socios activos.
             */
            $sociosActivos = $socios->filter(
                fn($vinculacion) =>
                    $vinculacion->estado === 'ACTIVA'
            );


            /*
             * Socios inactivos.
             */
            $sociosInactivos = $socios->filter(
                fn($vinculacion) =>
                    $vinculacion->estado === 'INACTIVA'
            );


            /*
             * Últimos socios.
             */
            $sociosRecientes =
                $socios
                    ->take(5);


            $totalSocios =
                $socios->count();


            $totalSociosActivos =
                $sociosActivos->count();


            $totalSociosInactivos =
                $sociosInactivos->count();


            $this->set(compact(
                'usuario',
                'rol',
                'especialista',
                'socios',
                'sociosActivos',
                'sociosInactivos',
                'sociosRecientes',
                'totalSocios',
                'totalSociosActivos',
                'totalSociosInactivos'
            ));

            return;
        }


        /*
         * =====================================================
         * ADMIN
         * =====================================================
         */
        if ($rol === 'admin') {

            return $this->redirect([
                'controller' => 'Admin',
                'action' => 'index'
            ]);
        }


        /*
         * Rol desconocido.
         */
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'logout'
        ]);
    }
}