@extends('layouts.master')

@section('title', 'Корзина')
@section('header')
<header class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <a href="{{ route('home') }}" class="navbar-brand text-white fw-bold d-flex align-items-center" style="font-size: 34px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="AutoDetails" width="85" height="85" style="margin-right: 10px;">
                <i>AutoDetails</i>
            </a>
    
            <nav class="d-none d-lg-flex align-items-center flex-wrap">
                <a href="{{ route('home') }}" class="text-white mx-2 my-1">Главная</a>
                <a href="{{ route('categories.index') }}" class="text-white mx-2 my-1">Категории</a>
                <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a>
                <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1">Заказы</a>
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1 active">Корзина</a>
            </nav>

            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                @auth
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light me-2">{{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light me-2">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-light text-dark">Регистрация</a>
                @endauth
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Корзина</h1>
    
    @if(empty($cartItems))
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 80px; color: #c46f00;"></i>
            <h3 class="mt-3">Ваша корзина пуста</h3>
            <p class="text-muted">Добавьте товары из каталога</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-arrow-left me-2"></i>Перейти к покупкам
            </a>
        </div>
    @else
        <div class="row">
            <!-- Список товаров -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Товар</th>
                                        <th class="text-center">Цена</th>
                                        <th class="text-center">Количество</th>
                                        <th class="text-center">Сумма</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $productId => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($item['image']))
                                                    <img src="{{ asset($item['image']) }}" 
                                                         alt="{{ $item['name'] }}" 
                                                         style="width: 80px; height: 80px; object-fit: cover;" 
                                                         class="me-3 rounded">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                    <small class="text-muted">
                                                        @if(isset($item['stock_quantity']))
                                                            В наличии: {{ $item['stock_quantity'] }} шт.
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ number_format($item['price'], 0, ',', ' ') }} ₽
                                        </td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cart.update', $productId) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group input-group-sm" style="width: 140px;">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity({{ $productId }}, -1)">-</button>
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                           min="1" max="{{ $item['stock_quantity'] ?? 99 }}" 
                                                           class="form-control text-center" id="quantity-{{ $productId }}">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity({{ $productId }}, 1)">+</button>
                                                </div>
                                                <button type="submit" class="btn btn-link ms-2" title="Обновить">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center align-middle">
                                            <strong>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} ₽</strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger" title="Удалить">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Продолжить покупки
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Вы уверены, что хотите очистить корзину?')">
                            <i class="bi bi-trash me-2"></i>Очистить корзину
                        </button>
                    </form>
                </div>
            </div>

            <!-- Итоги и оформление -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Итоги заказа</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Товаров:</span>
                            <span>
                                @php
                                    $totalItems = 0;
                                    foreach ($cartItems as $item) {
                                        $totalItems += $item['quantity'];
                                    }
                                    echo $totalItems . ' шт.';
                                @endphp
                            </span>
                        </div>
                        
                        @if($total > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Стоимость товаров:</span>
                                <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Доставка:</span>
                                <span>0 ₽</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Итого к оплате:</strong>
                                <strong class="h5 text-primary">{{ number_format($total, 0, ',', ' ') }} ₽</strong>
                            </div>
                            
                            <hr>
                        @endif
                        
                        @auth
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="bi bi-bag-check me-2"></i>Оформить заказ
                            </a>
                            <small class="text-muted d-block text-center">
                                Нажимая кнопку, вы соглашаетесь с условиями покупки
                            </small>
                        @else
                            <div class="alert alert-info">
                                <p class="mb-3">Для оформления заказа необходимо войти в аккаунт</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Войти</a>
                                    <a href="{{ route('register') }}" class="btn btn-primary">Зарегистрироваться</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
                
                <!-- Акции и скидки -->
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-percent me-2"></i>Специальные предложения</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Бесплатная доставка от 5000 ₽</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Скидка 10% при заказе от 3 товаров</small>
                            </li>
                            <li>
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Накопительная скидка до 15%</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function changeQuantity(productId, change) {
    const input = document.getElementById('quantity-' + productId);
    let value = parseInt(input.value) + change;
    const max = parseInt(input.max);
    const min = parseInt(input.min);
    
    if (value > max) value = max;
    if (value < min) value = min;
    
    input.value = value;
}

// AJAX обновление количества
document.addEventListener('DOMContentLoaded', function() {
    // Обработка форм обновления количества через AJAX
    document.querySelectorAll('form[action*="cart/update"]').forEach(form => {
        const input = form.querySelector('input[name="quantity"]');
        const productId = form.action.split('/').pop();
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const quantity = input.value;
            
            fetch(`/api/cart/update/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем страницу для отображения изменений
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Если AJAX не сработал, отправляем форму обычным способом
                form.submit();
            });
        });
    });
    
    // Обработка удаления через AJAX
    document.querySelectorAll('form[action*="cart/remove"]').forEach(form => {
        const productId = form.action.split('/').pop();
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Вы уверены, что хотите удалить товар из корзины?')) {
                return;
            }
            
            fetch(`/api/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем страницу для отображения изменений
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Если AJAX не сработал, отправляем форму обычным способом
                form.submit();
            });
        });
    });
});
</script>
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