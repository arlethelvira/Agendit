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


            /* -----------------------------
             * PROGRESO DE HÁBITOS
             * -----------------------------
             *
             * Misma lógica de expansión por frecuencia
             * que HabitosController::progreso(), pero
             * solo nos interesa el total combinado
             * para la tarjeta del Dashboard.
             */

            $registroHabitosTable = $this->fetchTable('RegistroHabitos');

            $pasosFrecuencia = [
                'diaria' => 1,
                'cada 2 dias' => 2,
                'cada 3 dias' => 3,
                'semanal' => 7,
            ];

            $totalHabitosEsperadas = 0;
            $totalHabitosCompletadas = 0;

            foreach ($habitos as $habito) {

                $fechaInicio = new \DateTime(
                    $habito->fecha_creacion->format('Y-m-d')
                );

                $fechaHoy = new \DateTime($hoy);

                $frecuencia = strtolower($habito->frecuencia);
                $esperadas = [];

                if ($frecuencia === 'mensual') {

                    $cursor = clone $fechaInicio;

                    while ($cursor <= $fechaHoy) {
                        $esperadas[] = $cursor->format('Y-m-d');
                        $cursor->modify('+1 month');
                    }

                } else {

                    $paso = $pasosFrecuencia[$frecuencia] ?? 1;
                    $cursor = clone $fechaInicio;

                    while ($cursor <= $fechaHoy) {
                        $esperadas[] = $cursor->format('Y-m-d');
                        $cursor->modify("+{$paso} days");
                    }
                }

                $completadasHabito = $registroHabitosTable
                    ->find()
                    ->where([
                        'id_habito' => $habito->id_habito,
                        'fecha IN' => $esperadas,
                        'completado' => true,
                    ])
                    ->count();

                $totalHabitosEsperadas += count($esperadas);
                $totalHabitosCompletadas += $completadasHabito;
            }

            $progresoHabitos = $totalHabitosEsperadas > 0
                ? round(
                    ($totalHabitosCompletadas / $totalHabitosEsperadas) * 100
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
                'progreso',
                'totalHabitosEsperadas',
                'totalHabitosCompletadas',
                'progresoHabitos'
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