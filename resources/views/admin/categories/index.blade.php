@extends('layouts.admin')

@section('title', 'Управление категориями')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление категориями</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить категорию
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>URL код</th>
                                <th>Родительская</th>
                                <th>Товары</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $category->name }}</div>
                                    @if($category->image)
                                        <small class="text-muted">Есть изображение</small>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $category->code }}</code>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge bg-info">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($category->is_active) bg-success @else bg-secondary @endif">
                                        {{ $category->is_active ? 'Активна' : 'Неактивна' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('category.show', $category->code) }}" 
                                           target="_blank" class="btn btn-info" title="Посмотреть на сайте">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="btn btn-warning" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    title="Удалить" 
                                                    onclick="return confirm('Удалить категорию? Внимание: товары этой категории могут стать без категории!')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Подкатегории -->
                            @if($category->children->count() > 0)
                                @foreach($category->children as $child)
                                <tr class="bg-light">
                                    <td>{{ $child->id }}</td>
                                    <td>
                                        <div class="ms-3">
                                            <i class="fas fa-level-up-alt fa-rotate-90 text-muted me-1"></i>
                                            {{ $child->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $child->code }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $child->products_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($child->is_active) bg-success @else bg-secondary @endif">
                                            {{ $child->is_active ? 'Активна' : 'Неактивна' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('category.show', $child->code) }}" 
                                               target="_blank" class="btn btn-info btn-sm" title="Посмотреть на сайте">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $child->id) }}" 
                                               class="btn btn-warning btn-sm" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $child->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Удалить" 
                                                        onclick="return confirm('Удалить подкатегорию?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Категории не найдены</h5>
                    <p class="text-muted">Создайте первую категорию</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить категорию
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection