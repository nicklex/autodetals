@extends('layouts.admin')

@section('title', 'Управление запасом товара')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление запасом</h1>
        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к складу
        </a>
    </div>

    <div class="row">
        <!-- Информация о товаре -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Информация о товаре</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($product->images && count(json_decode($product->images)) > 0)
                            <img src="{{ Storage::url(json_decode($product->images)[0]) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px; object-fit: contain;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="mb-2">{{ $product->name }}</h5>
                    <div class="mb-2">
                        <small class="text-muted">Артикул:</small>
                        <div class="fw-bold">{{ $product->code }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Категория:</small>
                        <div>
                            <span class="badge bg-info">{{ $product->category->name ?? 'Без категории' }}</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Цена:</small>
                        <div class="fw-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Статус товара:</small>
                        <div>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Активен' : 'Неактивен' }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit"></i> Редактировать товар
                    </a>
                </div>
            </div>
        </div>

        <!-- Управление запасом -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Управление запасами</h5>
                </div>
                <div class="card-body">
                    <!-- Текущий статус -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Текущий запас</h6>
                                    <h2 class="{{ ($product->stock->quantity ?? 0) < 10 ? 'text-danger' : 'text-success' }}">
                                        {{ $product->stock->quantity ?? 0 }}
                                    </h2>
                                    <small>шт.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Зарезервировано</h6>
                                    <h2 class="text-warning">{{ $product->stock->reserved ?? 0 }}</h2>
                                    <small>шт.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Доступно</h6>
                                    @php
                                        $available = ($product->stock->quantity ?? 0) - ($product->stock->reserved ?? 0);
                                    @endphp
                                    <h2 class="{{ $available <= 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $available }}
                                    </h2>
                                    <small>шт.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Форма обновления запаса -->
                    <div class="mb-4">
                        <h5 class="mb-3">Обновить количество</h5>
                        <form action="{{ route('admin.stocks.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="operation" class="form-label">Операция</label>
                                    <select name="operation" id="operation" class="form-select" required>
                                        <option value="set">Установить точное количество</option>
                                        <option value="add">Добавить к текущему</option>
                                        <option value="subtract">Вычесть из текущего</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Количество</label>
                                    <input type="number" name="quantity" id="quantity" 
                                           class="form-control" min="0" required>
                                    <small class="text-muted">Введите количество для операции</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">Причина изменения</label>
                                <textarea name="reason" id="reason" 
                                          class="form-control" rows="2" 
                                          placeholder="Например: Поступление от поставщика, Продажа, Списание..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Обновить запас
                            </button>
                        </form>
                    </div>

                    <!-- История изменений -->
                    <div class="mb-4">
                        <h5 class="mb-3">История изменений</h5>
                        @if($stockHistory && $stockHistory->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Операция</th>
                                            <th>Изменение</th>
                                            <th>Новое значение</th>
                                            <th>Причина</th>
                                            <th>Пользователь</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stockHistory as $history)
                                        <tr>
                                            <td>{{ $history->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($history->operation == 'add')
                                                    <span class="badge bg-success">Добавление</span>
                                                @elseif($history->operation == 'subtract')
                                                    <span class="badge bg-danger">Списание</span>
                                                @else
                                                    <span class="badge bg-primary">Установка</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($history->operation == 'add')
                                                    <span class="text-success">+{{ $history->quantity_change }}</span>
                                                @elseif($history->operation == 'subtract')
                                                    <span class="text-danger">-{{ $history->quantity_change }}</span>
                                                @else
                                                    <span class="text-primary">= {{ $history->new_quantity }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $history->new_quantity }}</td>
                                            <td>{{ $history->reason ?? '-' }}</td>
                                            <td>{{ $history->user->name ?? 'Система' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> История изменений отсутствует.
                            </div>
                        @endif
                    </div>

                    <!-- Быстрые действия -->
                    <div class="mb-4">
                        <h5 class="mb-3">Быстрые действия</h5>
                        <div class="btn-group">
                            <form action="{{ route('admin.stocks.update', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="operation" value="add">
                                <input type="hidden" name="quantity" value="10">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-plus"></i> Добавить 10 шт.
                                </button>
                            </form>
                            <form action="{{ route('admin.stocks.update', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="operation" value="subtract">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="fas fa-minus"></i> Списать 1 шт.
                                </button>
                            </form>
                            <form action="{{ route('admin.stocks.update', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="operation" value="set">
                                <input type="hidden" name="quantity" value="0">
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Обнулить запас?')">
                                    <i class="fas fa-times"></i> Обнулить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Автозаполнение быстрых действий
    const operationSelect = document.getElementById('operation');
    const quantityInput = document.getElementById('quantity');
    
    if (operationSelect && quantityInput) {
        operationSelect.addEventListener('change', function() {
            if (this.value === 'set') {
                quantityInput.placeholder = 'Установить точное количество';
            } else if (this.value === 'add') {
                quantityInput.placeholder = 'Добавить к текущему';
            } else {
                quantityInput.placeholder = 'Вычесть из текущего';
            }
        });
    }
});
</script>
@endpush
@endsection