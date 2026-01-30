@extends('layouts.admin')

@section('title', 'Управление заказами')

@section('content')
<div class="container-fluid">
    <!-- Заголовок и кнопки -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление заказами</h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Фильтры
            </button>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="новый" {{ request('status') == 'новый' ? 'selected' : '' }}>Новый</option>
                        <option value="в_обработке" {{ request('status') == 'в_обработке' ? 'selected' : '' }}>В обработке</option>
                        <option value="отправлен" {{ request('status') == 'отправлен' ? 'selected' : '' }}>Отправлен</option>
                        <option value="доставлен" {{ request('status') == 'доставлен' ? 'selected' : '' }}>Доставлен</option>
                        <option value="отменен" {{ request('status') == 'отменен' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Дата от</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Дата до</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Поиск</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="ID, email, телефон..." value="{{ request('search') }}">
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Применить
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Сбросить
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица заказов -->
    <div class="card shadow">
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Клиент</th>
                                <th>Контакты</th>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>
                                    <div>{{ $order->name }}</div>
                                    @if($order->user)
                                        <small class="text-muted">ID: {{ $order->user->id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div><i class="fas fa-phone"></i> {{ $order->phone }}</div>
                                    <div><i class="fas fa-envelope"></i> {{ $order->email }}</div>
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
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    title="Удалить" 
                                                    onclick="return confirm('Удалить заказ?')">
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Показано {{ $orders->firstItem() }} - {{ $orders->lastItem() }} из {{ $orders->total() }}
                    </div>
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Заказы не найдены</h5>
                    <p class="text-muted">Попробуйте изменить параметры поиска</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection