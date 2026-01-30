@extends('layouts.admin')

@section('title', 'Управление товарами')

@section('content')
<div class="container-fluid">
    <!-- Заголовок и кнопки -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление товарами</h1>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить товар
            </a>
        </div>
    </div>

    <!-- Поиск и фильтры -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Название товара..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Нет в наличии</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Найти
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица товаров -->
    <div class="card shadow">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Изображение</th>
                                <th>Название</th>
                                <th>Категория</th>
                                <th>Цена</th>
                                <th>Остаток</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->images && count(json_decode($product->images)) > 0)
                                        <img src="{{ Storage::url(json_decode($product->images)[0]) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ Str::limit($product->name, 40) }}</div>
                                    <small class="text-muted">{{ $product->code }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $product->category->name ?? 'Без категории' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                                    @if($product->old_price)
                                        <small class="text-muted text-decoration-line-through">
                                            {{ number_format($product->old_price, 0, ',', ' ') }} ₽
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->stock)
                                        @if($product->stock->quantity < 10)
                                            <span class="badge bg-danger">{{ $product->stock->quantity }} шт.</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock->quantity }} шт.</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">0 шт.</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($product->is_active) bg-success @else bg-secondary @endif">
                                        {{ $product->is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.show', $product->id) }}" 
                                           target="_blank" class="btn btn-info" title="Посмотреть на сайте">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-warning" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.stocks.edit', $product->id) }}" 
                                           class="btn btn-primary" title="Управление запасом">
                                            <i class="fas fa-warehouse"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    title="Удалить" 
                                                    onclick="return confirm('Удалить товар?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Пагинация -->
                {{ $products->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Товары не найдены</h5>
                    <p class="text-muted">Добавьте первый товар или измените параметры поиска</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить товар
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection