<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class CategoriasController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->autoRender = false;
    }

    private function getIdUsuarioActual(): int
    {
        $usuario = $this->request->getSession()->read('Usuario');
        return (int)($usuario['id_usuario'] ?? 0);
    }

    private function json(array $data): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    // GET /categorias/index -> categorías del usuario en sesión
    public function index(): Response
    {
        $idUsuario = $this->getIdUsuarioActual();
        $categorias = $this->Categorias->find('deUsuario', idUsuario: $idUsuario)->toArray();

        return $this->json(['exito' => true, 'datos' => $categorias]);
    }

    // POST /categorias/agregar
    public function agregar(): Response
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        $categoria = $this->Categorias->newEmptyEntity();
        $categoria = $this->Categorias->patchEntity($categoria, [
            'id_usuario' => $this->getIdUsuarioActual(),
            'nombre' => trim($data['nombre'] ?? ''),
            'color' => trim($data['color'] ?? ''),
        ]);

        if (!$this->Categorias->save($categoria)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al crear la categoría', 'errores' => $categoria->getErrors()]);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Categoría creada correctamente', 'datos' => $categoria]);
    }

    // POST /categorias/editar/{id}
    public function editar(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $idUsuario = $this->getIdUsuarioActual();

        $categoria = $this->Categorias->find()
            ->where(['id_categoria' => $id, 'id_usuario' => $idUsuario])
            ->first();

        if (!$categoria) {
            return $this->json(['exito' => false, 'mensaje' => 'Categoría no encontrada']);
        }

        $data = $this->request->getData();
        $categoria = $this->Categorias->patchEntity($categoria, [
            'nombre' => trim($data['nombre'] ?? ''),
            'color' => trim($data['color'] ?? ''),
        ]);

        if (!$this->Categorias->save($categoria)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al actualizar', 'errores' => $categoria->getErrors()]);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Categoría actualizada correctamente']);
    }

    // POST /categorias/eliminar/{id}
    public function eliminar(int $id): Response
    {
        $this->request->allowMethod(['post']);
        $idUsuario = $this->getIdUsuarioActual();

        $categoria = $this->Categorias->find()
            ->where(['id_categoria' => $id, 'id_usuario' => $idUsuario])
            ->first();

        if (!$categoria) {
            return $this->json(['exito' => false, 'mensaje' => 'Categoría no encontrada']);
        }

        // Si hay tareas usando esta categoría, las dejamos sin categoría en vez de bloquear el borrado
        $this->Categorias->Tareas->updateAll(
            ['id_categoria' => null],
            ['id_categoria' => $id]
        );

        if (!$this->Categorias->delete($categoria)) {
            return $this->json(['exito' => false, 'mensaje' => 'Error al eliminar']);
        }

        return $this->json(['exito' => true, 'mensaje' => 'Categoría eliminada correctamente']);
    }
}