<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.categories.index', compact('categories'));
    }
    
public function create()
{
    $categories = \App\Models\Category::all();
    return view('admin.categories.create', compact('categories'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|unique:categories,code|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_in_home' => 'boolean',
        'sort_order' => 'integer',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
    ]);

    $category = \App\Models\Category::create([
        'name' => $validated['name'],
        'code' => $validated['code'],
        'parent_id' => $validated['parent_id'] ?? null,
        'description' => $validated['description'] ?? null,
        'is_active' => $request->has('is_active'),
        'show_in_menu' => $request->has('show_in_menu'),
        'show_in_home' => $request->has('show_in_home'),
        'sort_order' => $validated['sort_order'] ?? 0,
        'meta_title' => $validated['meta_title'] ?? null,
        'meta_description' => $validated['meta_description'] ?? null,
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories', 'public');
        $category->image = $path;
        $category->save();
    }

    return redirect()->route('admin.categories.index')
        ->with('success', 'Категория успешно создана');
}
    
public function edit(Category $category)
{
    // Получаем все категории, кроме текущей (чтобы нельзялось сделать родителем саму себя)
    $categories = Category::where('id', '!=', $category->id)->get();
    
    // Загружаем счетчики
    $category->loadCount(['products', 'children']);
    
    return view('admin.categories.edit', compact('category', 'categories'));
}

public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|unique:categories,code,' . $category->id,
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048', // 2MB максимум
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_in_home' => 'boolean',
        'sort_order' => 'integer',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
    ]);

    // Обновляем основные данные
    $category->update([
        'name' => $request->name,
        'code' => $request->code,
        'parent_id' => $request->parent_id,
        'description' => $request->description,
        'is_active' => $request->has('is_active'),
        'show_in_menu' => $request->has('show_in_menu'),
        'show_in_home' => $request->has('show_in_home'),
        'sort_order' => $request->sort_order ?? 0,
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
    ]);

    // Обработка удаления изображения (через checkbox)
    if ($request->has('remove_image') && $request->remove_image == '1') {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
            $category->image = null;
            $category->save();
        }
    }

    // Обработка загрузки нового изображения
    if ($request->hasFile('image')) {
        // Сначала удаляем старое изображение (если есть и если не удалили через checkbox)
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        
        // Сохраняем новое изображение
        $path = $request->file('image')->store('categories', 'public');
        $category->image = $path;
        $category->save();
    }

    return redirect()->route('admin.categories.index')
        ->with('success', 'Категория успешно обновлена');
}
    
    public function destroy(Category $category)
    {
        // Проверяем, есть ли товары в категории
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить категорию, в которой есть товары');
        }
        
        // Удаляем изображение
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена');
    }
}