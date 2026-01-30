@extends('layouts.admin')

@section('title', 'Панель администратора')

@section('content')
<div class="container-fluid">
    <!-- Заголовок и быстрые действия -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Панель администратора</h1>
        <div class="btn-group">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить товар
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                <i class="fas fa-folder-plus"></i> Добавить категорию
            </a>
            <a href="{{ route('admin.stocks.index') }}" class="btn btn-warning">
                <i class="fas fa-warehouse"></i> Управление складом
            </a>
        </div>
    </div>
    
    <!-- Статистика -->
    <div class="row">
        <!-- Новые заказы -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Новые заказы
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['new_orders'] }}</div>
                            <div class="mt-2">
                                <a href="{{ route('admin.orders.index') }}?status=новый" class="text-decoration-none small">
                                    Посмотреть <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Всего товаров -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Всего товаров
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                            <div class="mt-2">
                                <a href="{{ route('admin.products.index') }}" class="text-decoration-none small">
                                    Управление <i class="fas fa-edit ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Пользователи -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Пользователи
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                            <div class="mt-2">
                                <a href="{{ route('admin.users.index') }}" class="text-decoration-none small">
                                    Список <i class="fas fa-users ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Выручка -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Выручка (месяц)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['revenue'], 0, ',', ' ') }} ₽</div>
                            <div class="mt-2">
                                <a href="{{ route('admin.orders.index') }}?date={{ date('Y-m') }}" class="text-decoration-none small">
                                    Отчет <i class="fas fa-chart-line ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ruble-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Последние заказы -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Последние заказы</h6>
                    <div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Все заказы</a>

                            <i class="fas fa-plus"></i> Новый
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($latestOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Клиент</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestOrders as $order)
                                    <tr>
                                        <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td>
                                            <div>{{ $order->name }}</div>
                                            <small class="text-muted">{{ $order->email }}</small>
                                        </td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        <td><strong>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</strong></td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'новый') bg-primary
                                                @elseif($order->status == 'в_обработке') bg-info
                                                @elseif($order->status == 'отправлен') bg-warning
                                                @elseif($order->status == 'доставлен') bg-success
                                                @else bg-danger @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                   class="btn btn-info" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                                   class="btn btn-warning" title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Нет заказов</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Быстрые действия -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Управление каталогом</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-box me-2"></i>
                                Товары
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $stats['total_products'] }}</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tags me-2"></i>
                                Категории
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $stats['total_categories'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.stocks.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-warehouse me-2"></i>
                                Склад
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ $lowStock->count() }} мало</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-comments me-2"></i>
                                Отзывы
                            </div>
                            <span class="badge bg-info rounded-pill">{{ $stats['pending_reviews'] ?? 0 }} новых</span>
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Категории (быстрое редактирование) -->
                    <h6 class="font-weight-bold mb-3">Популярные категории</h6>
                    @if(isset($topCategories) && $topCategories->count() > 0)
                        <div class="list-group">
                            @foreach($topCategories as $category)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-folder me-2 text-primary"></i>
                                    {{ $category->name }}
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="btn btn-outline-primary btn-sm" title="Редактировать">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('category.show', $category->code) }}" 
                                       target="_blank" class="btn btn-outline-info btn-sm" title="Посмотреть на сайте">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Нет категорий</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Товары с низким запасом и последние отзывы -->
    <div class="row">
        <!-- Товары с низким запасом -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Товары с низким запасом
                    </h6>
                    <a href="{{ route('admin.stocks.low') }}" class="btn btn-sm btn-danger">Все</a>
                </div>
                <div class="card-body">
                    @if($lowStock->count() > 0)
                        <div class="list-group">
                            @foreach($lowStock as $product)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="mb-1">
                                        <h6 class="mb-1">{{ Str::limit($product->name, 40) }}</h6>
                                        <small class="text-muted">
                                            {{ $product->category->name ?? 'Без категории' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger mb-2">{{ $product->stock->quantity }} шт.</span>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-outline-primary" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.stocks.edit', $product->id) }}" 
                                               class="btn btn-outline-warning" title="Пополнить запас">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success">Все товары в достаточном количестве</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Последние отзывы -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">Последние отзывы</h6>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-info">Все отзывы</a>
                </div>
                <div class="card-body">
                    @if(isset($latestReviews) && $latestReviews->count() > 0)
                        <div class="list-group">
                            @foreach($latestReviews as $review)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $review->product->name ?? 'Товар удален' }}</h6>
                                    <small>{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-2">{{ Str::limit($review->comment, 100) }}</p>
                                <small class="text-muted">— {{ $review->user->name ?? 'Аноним' }}</small>
                                <div class="mt-2">
                                    @if(!$review->is_approved)
                                        <span class="badge bg-warning">На модерации</span>
                                    @endif
                                    <div class="btn-group btn-group-sm mt-1">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Одобрить">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Удалить" onclick="return confirm('Удалить отзыв?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Нет отзывов</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для быстрого добавления категории -->
<div class="modal fade" id="quickCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Быстрое добавление категории</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Название категории *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Код (slug) *</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                        <small class="text-muted">Латинские буквы, цифры и дефисы</small>
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Родительская категория</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">— Без родительской категории —</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Автогенерация кода из названия
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    if (nameInput && codeInput) {
        nameInput.addEventListener('input', function() {
            if (!codeInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                codeInput.value = slug;
            }
        });
    }
});
</script>
@endpush
@endsection