@extends('layouts.admin')

@section('title', 'Пользователь: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Пользователь: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 class="img-fluid rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 150px; height: 150px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $user->name }}</h4>
                        @if($user->admin)
                            <span class="badge bg-danger">Администратор</span>
                        @else
                            <span class="badge bg-secondary">Пользователь</span>
                        @endif
                        
                        @if($user->banned_at)
                            <span class="badge bg-danger mt-2">Заблокирован</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-envelope me-2"></i> Email</h6>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    
                    @if($user->phone)
                    <div class="mb-3">
                        <h6><i class="fas fa-phone me-2"></i> Телефон</h6>
                        <p class="mb-0">{{ $user->phone }}</p>
                    </div>
                    @endif
                    
                    @if($user->address)
                    <div class="mb-3">
                        <h6><i class="fas fa-map-marker-alt me-2"></i> Адрес</h6>
                        <p class="mb-0">{{ $user->address }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-calendar me-2"></i> Зарегистрирован</h6>
                        <p class="mb-0">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-clock me-2"></i> Последний вход</h6>
                        <p class="mb-0">
                            {{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : 'Никогда' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Статистика -->
        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Заказы</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->orders()->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Потрачено</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($user->orders()->sum('total_price') ?? 0, 0, ',', ' ') }} ₽
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-ruble-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Отзывы</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->reviews()->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Последние заказы -->
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние заказы</h5>
                    @if($user->orders()->count() > 0)
                        <a href="{{ route('admin.orders.index') }}?user_id={{ $user->id }}" 
                           class="btn btn-sm btn-primary">
                            Все заказы
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->orders()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders()->latest()->take(5)->get() as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'новый') bg-primary
                                                @elseif($order->status == 'доставлен') bg-success
                                                @else bg-secondary @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Нет заказов</p>
                    @endif
                </div>
            </div>
            
            <!-- Последние отзывы -->
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние отзывы</h5>
                    @if($user->reviews()->count() > 0)
                        <a href="{{ route('admin.reviews.index') }}?user_id={{ $user->id }}" 
                           class="btn btn-sm btn-primary">
                            Все отзывы
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->reviews()->count() > 0)
                        <div class="list-group">
                            @foreach($user->reviews()->with('product')->latest()->take(3)->get() as $review)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <a href="{{ route('admin.products.edit', $review->product_id) }}" 
                                           class="text-decoration-none">
                                            {{ $review->product->name ?? 'Товар удален' }}
                                        </a>
                                    </h6>
                                    <small>{{ $review->created_at->format('d.m.Y') }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-1">{{ Str::limit($review->comment, 150) }}</p>
                                <small class="text-muted">
                                    Статус: 
                                    @if($review->is_approved === true)
                                        <span class="text-success">Одобрен</span>
                                    @elseif($review->is_approved === false)
                                        <span class="text-danger">Отклонен</span>
                                    @else
                                        <span class="text-warning">На модерации</span>
                                    @endif
                                </small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Нет отзывов</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection