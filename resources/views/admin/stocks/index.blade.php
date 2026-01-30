@extends('layouts.admin')

@section('title', 'Управление складом')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление складом</h1>
        <div>
            <a href="{{ route('admin.stocks.low') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> Мало товаров
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                <i class="fas fa-edit"></i> Массовое обновление
            </button>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.stocks.index') }}" class="row g-3">
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
                    <select name="stock_filter" class="form-select">
                        <option value="">Все товары</option>
                        <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Мало (менее 10)</option>
                        <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Нет в наличии</option>
                        <option value="enough" {{ request('stock_filter') == 'enough' ? 'selected' : '' }}>Достаточно (10+)</option>
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

    <!-- Таблица склада -->
    <div class="card shadow">
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Категория</th>
                                <th>Текущий запас</th>
                                <th>Зарезервировано</th>
                                <th>Доступно</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images && count(json_decode($product->images)) > 0)
                                            <img src="{{ Storage::url(json_decode($product->images)[0]) }}" 
                                                 alt="{{ $product->name }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                                 class="rounded me-3">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ Str::limit($product->name, 30) }}</div>
                                            <small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $product->category->name ?? 'Без категории' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.stocks.update', $product->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <input type="number" name="quantity" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $product->stock->quantity ?? 0 }}" 
                                                   min="0">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $product->stock->reserved ?? 0 }} шт.
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $available = ($product->stock->quantity ?? 0) - ($product->stock->reserved ?? 0);
                                    @endphp
                                    <span class="fw-bold {{ $available <= 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $available }} шт.
                                    </span>
                                </td>
                                <td>
                                    @if(($product->stock->quantity ?? 0) <= 0)
                                        <span class="badge bg-danger">Нет в наличии</span>
                                    @elseif(($product->stock->quantity ?? 0) < 10)
                                        <span class="badge bg-warning">Мало</span>
                                    @else
                                        <span class="badge bg-success">В наличии</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-warning" title="Редактировать товар">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.stocks.edit', $product->id) }}" 
                                           class="btn btn-primary" title="Подробно">
                                            <i class="fas fa-warehouse"></i>
                                        </a>
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
                    <i class="fas fa-warehouse fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Товары не найдены</h5>
                    <p class="text-muted">Попробуйте изменить параметры поиска</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Модальное окно массового обновления -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Массовое обновление запасов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stocks.bulk-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Категория</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="operation" class="form-label">Операция</label>
                        <select name="operation" id="operation" class="form-select">
                            <option value="add">Добавить количество</option>
                            <option value="set">Установить количество</option>
                            <option value="subtract">Вычесть количество</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Количество</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="0" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Эта операция применится ко всем товарам выбранной категории.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Применить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection