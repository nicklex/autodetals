@extends('layouts.master')

@section('title', 'Заказ #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))
@section('header')
<header class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <a href="{{ route('home') }}" class="navbar-brand text-white fw-bold d-flex align-items-center" style="font-size: 34px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="Инструмент" width="85" height="85" style="margin-right: 10px;">
                <i>AutoDetails</i>
            </a>
    
            <nav class="d-none d-lg-flex align-items-center flex-wrap">
                <a href="{{ route('home') }}" class="text-white mx-2 my-1">Главная</a>
                <a href="{{ route('categories.index') }}" class="text-white mx-2 my-1">Категории</a>
                <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a>
                <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1 active">Мои заказы</a>
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
            </nav>

            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                <a href="{{ route('profile.index') }}" class="btn btn-outline-light me-2">{{ auth()->user()->name }}</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light me-2">Выйти</button>
                </form>
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Мои заказы</a></li>
            <li class="breadcrumb-item active">Заказ #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</li>
        </ol>
    </nav>
    
    <!-- Заголовок и статус -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Заказ #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
        <div>
            @php
                $statusColors = [
                    'новый' => 'primary',
                    'в_пути' => 'warning',
                    'ожидает_на_пункте' => 'info',
                    'получен' => 'success',
                    'отменен' => 'danger'
                ];
                $statusLabels = [
                    'новый' => 'Новый',
                    'в_пути' => 'В пути',
                    'ожидает_на_пункте' => 'Ожидает на пункте',
                    'получен' => 'Получен',
                    'отменен' => 'Отменен'
                ];
            @endphp
            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                {{ $statusLabels[$order->status] ?? $order->status }}
            </span>
        </div>
    </div>
    
    <div class="row">
        <!-- Информация о заказе -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->images)
                                                @php
                                                    $images = json_decode($item->product->images);
                                                    $firstImage = $images[0] ?? null;
                                                @endphp
                                                @if($firstImage)
                                                    <img src="{{ asset('storage/' . $firstImage) }}" 
                                                         alt="{{ $item->product->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         class="me-3 rounded">
                                                @endif
                                            @endif
                                            <div>
                                                <div>{{ $item->product->name }}</div>
                                                <small class="text-muted">Артикул: {{ $item->product->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 0, ',', ' ') }} ₽</td>
                                    <td>{{ $item->quantity }} шт.</td>
                                    <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- История статусов -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">История заказа</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $timeline = [
                                ['status' => 'новый', 'date' => $order->created_at, 'label' => 'Заказ создан'],
                                ['status' => 'в_пути', 'date' => $order->updated_at, 'label' => 'Заказ отправлен'],
                                ['status' => 'ожидает_на_пункте', 'date' => null, 'label' => 'Ожидает на пункте выдачи'],
                                ['status' => 'получен', 'date' => null, 'label' => 'Заказ получен'],
                            ];
                        @endphp
                        
                        @foreach($timeline as $item)
                            @php
                                $isActive = array_search($order->status, array_column($timeline, 'status')) >= 
                                          array_search($item['status'], array_column($timeline, 'status'));
                            @endphp
                            <div class="timeline-item d-flex">
                                <div class="timeline-marker">
                                    <div class="marker {{ $isActive ? 'bg-primary' : 'bg-light border' }}"></div>
                                    @if(!$loop->last)
                                        <div class="line {{ $isActive ? 'bg-primary' : 'bg-light' }}"></div>
                                    @endif
                                </div>
                                <div class="timeline-content ms-3 mb-4">
                                    <h6 class="mb-1 {{ $isActive ? 'text-primary' : 'text-muted' }}">
                                        {{ $item['label'] }}
                                    </h6>
                                    @if($item['date'])
                                        <small class="text-muted">
                                            {{ $item['date']->format('d.m.Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Информация о доставке и оплате -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Информация о заказе</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Номер заказа:</dt>
                        <dd class="col-sm-7">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</dd>
                        
                        <dt class="col-sm-5">Дата создания:</dt>
                        <dd class="col-sm-7">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                        
                        <dt class="col-sm-5">Последнее обновление:</dt>
                        <dd class="col-sm-7">{{ $order->updated_at->format('d.m.Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Доставка</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Получатель:</dt>
                        <dd class="col-sm-7">{{ $order->name }}</dd>
                        
                        <dt class="col-sm-5">Телефон:</dt>
                        <dd class="col-sm-7">{{ $order->phone }}</dd>
                        
                        <dt class="col-sm-5">Адрес:</dt>
                        <dd class="col-sm-7">{{ $order->address }}</dd>
                    </dl>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Оплата</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Товары:</span>
                        <span>{{ number_format($order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        }), 0, ',', ' ') }} ₽</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Доставка:</span>
                        <span>350 ₽</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Итого:</strong>
                        <strong class="h5 text-primary">
                            {{ number_format($order->total_price, 0, ',', ' ') }} ₽
                        </strong>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Вернуться к заказам
                </a>
                @if($order->status === 'новый')
                    <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Вы уверены, что хотите отменить заказ?')">
                            <i class="bi bi-x-circle me-2"></i>Отменить заказ
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.marker {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    z-index: 1;
}
.line {
    width: 2px;
    flex-grow: 1;
    margin-top: 4px;
}
</style>
@endsection

@section('footer')
<footer class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="text-center text-white">
            <p class="mb-0">&copy; 2025 AutoDetails. Все права защищены.</p>
        </div>
    </div>
</footer>
@endsection