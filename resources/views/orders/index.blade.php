@extends('layouts.master')

@section('title', 'Мои заказы')
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
    <h1 class="mb-4">Мои заказы</h1>
    
    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size: 80px; color: #c46f00;"></i>
            <h3 class="mt-3">У вас еще нет заказов</h3>
            <p class="text-muted">Совершите свою первую покупку!</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-3">Перейти к покупкам</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>№ Заказа</th>
                        <th>Дата</th>
                        <th>Товары</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($order->items->isNotEmpty())
                                    @php
                                        $firstItem = $order->items->first();
                                        $product = $firstItem->product;
                                        $images = $product->images ? json_decode($product->images) : [];
                                        $firstImage = $images[0] ?? null;
                                    @endphp
                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}" 
                                             alt="{{ $product->name }}"
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             class="me-2 rounded">
                                    @endif
                                    <div>
                                        <div>{{ $product->name }}</div>
                                        <small class="text-muted">
                                            + еще {{ $order->items->count() - 1 }} товар(ов)
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</td>
                        <td>
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
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Подробнее
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Пагинация -->
        @if($orders->hasPages())
            <nav class="mt-4">
                {{ $orders->links() }}
            </nav>
        @endif
    @endif
</div>
@endsection

@section('footer')
<footer class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Контакты:</h5>
                <ul class="list-unstyled text-white">
                    <li><span>📞</span> +7 (800) 123-45-67</li>
                    <li><span>✉️</span> info@autodetails.com</li>
                    <li><span>📍</span> г. Дзержинск • ул. Циолковского д. 1, корп. 2</li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Навигация:</h5>
                <ul class="list-unstyled">
                    <a href="{{ route('home') }}" class="text-white mx-2 my-1">Главная</a> <br>
                    <a href="{{ route('categories.index') }}" class="text-white mx-2 my-1">Категории</a> <br>
                    <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a> <br>
                    <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1">Заказы</a>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Мы в соцсетях:</h5>
                <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i> Facebook</a><br>
                <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i> Instagram</a><br>
                <a href="#" class="text-white"><i class="bi bi-vk"></i> ВКонтакте</a>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center text-white">
            <p class="mb-0">&copy; 2025 AutoDetails. Все права защищены.</p>
        </div>
    </div>
</footer>
@endsection