@extends('layouts.admin')

@section('title', 'Товары с низким запасом')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-danger">
            <i class="fas fa-exclamation-triangle"></i> Товары с низким запасом
        </h1>
        <div>
            <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Весь склад
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkRestockModal">
                <i class="fas fa-truck"></i> Массовое пополнение
            </button>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-muted">Всего мало</h6>
                    <h2 class="text-danger">{{ $lowStockCount }}</h2>
                    <small>товаров</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h6 class="text-muted">Менее 5 шт.</h6>
                    <h2 class="text-warning">{{ $criticalCount }}</h2>
                    <small>товаров</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h6 class="text-muted">Нет в наличии</h6>
                    <h2 class="text-info">{{ $outOfStockCount }}</h2>
                    <small>товаров</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted">Норма</h6>
                    <h2 class="text-success">{{ $normalCount }}</h2>
                    <small>товаров</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Товары с низким запасом -->
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
                                <th>Рекомендуемый минимум</th>
                                <th>Недостаток</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            @php
                                $current = $product->stock->quantity ?? 0;
                                $minimal = 10; // Можно сделать настройкой
                                $deficit = max(0, $minimal - $current);
                                $statusClass = $current <= 0 ? 'danger' : ($current < 5 ? 'warning' : 'info');
                            @endphp
                            <tr class="table-{{ $statusClass }}">
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
                                    <h5 class="mb-0 {{ $current <= 0 ? 'text-danger' : ($current < 5 ? 'text-warning' : 'text-info') }}">
                                        {{ $current }}
                                    </h5>
                                    <small>шт.</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $minimal }} шт.</span>
                                </td>
                                <td>
                                    @if($deficit > 0)
                                        <span class="badge bg-danger">-{{ $deficit }} шт.</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($current <= 0)
                                        <span class="badge bg-danger">Нет в наличии</span>
                                    @elseif($current < 5)
                                        <span class="badge bg-warning">Критически мало</span>
                                    @else
                                        <span class="badge bg-info">Мало</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <form action="{{ route('admin.stocks.update', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="operation" value="add">
                                            <input type="hidden" name="quantity" value="{{ $deficit > 0 ? $deficit : 10 }}">
                                            <button type="submit" class="btn btn-success" 
                                                    title="Пополнить до {{ $minimal }} шт.">
                                                <i class="fas fa-plus"></i> Пополнить
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.stocks.edit', $product->id) }}" 
                                           class="btn btn-primary" title="Подробно">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-warning" title="Редактировать товар">
                                            <i class="fas fa-box"></i>
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
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5 class="text-success">Все товары в достаточном количестве!</h5>
                    <p class="text-muted">Нет товаров с низким запасом.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Модальное окно массового пополнения -->
<div class="modal fade" id="bulkRestockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Массовое пополнение запасов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stocks.bulk-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Добавить ко всем товарам с низким запасом:</label>
                        <div class="input-group">
                            <input type="number" name="quantity" class="form-control" value="10" min="1" required>
                            <span class="input-group-text">шт.</span>
                        </div>
                        <small class="text-muted">Будет добавлено к текущему количеству</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Будет обновлено <strong>{{ $lowStockCount }}</strong> товаров с низким запасом.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Применить ко всем</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection