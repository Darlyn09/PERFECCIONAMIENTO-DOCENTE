<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Mostrar el listado de categorías con paginación.
     */
    public function index(Request $request)
    {
        $query = Categoria::query();

        // Búsqueda
        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where('nom_categoria', 'like', "%{$search}%");
        }

        $categorias = $query->orderBy('nom_categoria')->paginate(10);
        
        // Totales globales
        $totalCategorias = Categoria::count();
        
        return view('admin.categorias.index', compact('categorias', 'totalCategorias'));
    }

    /**
     * Mostrar el formulario para crear una nueva categoría.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nom_categoria')->paginate(10);
        $totalCategorias = Categoria::count();
        
        return view('admin.categorias.create', compact('categorias', 'totalCategorias'));
    }

    /**
     * Almacenar una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_categoria' => 'required|string|max:255|unique:categoria,nom_categoria',
        ], [
            'nom_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nom_categoria.unique' => 'Ya existe una categoría con este nombre.',
        ]);

        $categoria = new Categoria();
        $categoria->nom_categoria = $validated['nom_categoria'];
        $categoria->save();

        return redirect()->route('admin.categorias.create')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Mostrar el formulario para editar una categoría.
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nom_categoria' => 'required|string|max:255|unique:categoria,nom_categoria,' . $id . ',cur_categoria',
        ], [
            'nom_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nom_categoria.unique' => 'Ya existe una categoría con este nombre.',
        ]);

        $categoria->nom_categoria = $validated['nom_categoria'];
        $categoria->save();

        return redirect()->route('admin.categorias.create')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        
        // Verificar si tiene cursos asociados
        if ($categoria->cursos()->count() > 0) {
            return redirect()->route('admin.categorias.create')->with('error', 'No se puede eliminar la categoría porque tiene cursos asociados.');
        }
        
        $categoria->delete();

        return redirect()->route('admin.categorias.create')->with('success', 'Categoría eliminada exitosamente.');
    }
}
