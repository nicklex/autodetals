@extends('layouts.master')

@section('title', 'Результаты поиска - AutoDetails')

@section('content')
<header class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <!-- Логотип -->
            <a href="{{ route('home') }}" class="navbar-brand text-white fw-bold d-flex align-items-center" style="font-size: 34px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="AutoDetails" width="85" height="85" style="margin-right: 10px;">
                <i>AutoDetails</i>
            </a>
    
            <!-- Навигация -->
            <nav class="d-none d-lg-flex align-items-center flex-wrap">
                <a href="#categories" class="text-white mx-2 my-1">Категории</a>
                <a href="#about" class="text-white mx-2 my-1">О компании</a>
                <a href="#delivery" class="text-white mx-2 my-1">Доставка и оплата</a>
                <a href="#features" class="text-white mx-2 my-1">Преимущества</a>
                <a href="#contacts" class="text-white mx-2 my-1">Контакты</a>
                @auth
                    <a href="{{ route('basket') }}" class="text-white mx-2 my-1 position-relative">
                        Корзина
                        @if(session('cart'))
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                {{ array_sum(array_column(session('cart'), 'quantity')) }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white mx-2 my-1">Корзина</a>
                @endauth
            </nav>

            <!-- Кнопки авторизации -->
            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i>Профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i>Мои заказы</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Админ-панель</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-light text-dark">Регистрация</a>
                @endauth
            </div>            
        </div>
    </div>
</header>
<div class="container py-5">
    <div class="row">
        <!-- Боковая панель с фильтрами -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Фильтры</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('catalog') }}" method="GET">
                        <!-- Поиск -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Поиск</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Название или артикул">
                        </div>
                        
                        <!-- Категория -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Категория</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Цена -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Ценовой диапазон</label>
                            <select class="form-select" id="price" name="price">
                                <option value="">Любая цена</option>
                                <option value="0-1000" {{ request('price') == '0-1000' ? 'selected' : '' }}>
                                    До 1,000 ₽
                                </option>
                                <option value="1000-5000" {{ request('price') == '1000-5000' ? 'selected' : '' }}>
                                    1,000 - 5,000 ₽
                                </option>
                                <option value="5000-10000" {{ request('price') == '5000-10000' ? 'selected' : '' }}>
                                    5,000 - 10,000 ₽
                                </option>
                                <option value="10000+" {{ request('price') == '10000+' ? 'selected' : '' }}>
                                    От 10,000 ₽
                                </option>
                            </select>
                        </div>
                        
                        <!-- Сортировка -->
                        <div class="mb-3">
                            <label for="sort" class="form-label">Сортировка</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                    Новинки
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Цена по возрастанию
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Цена по убыванию
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    Популярные
                                </option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-filter me-2"></i>Применить фильтры
                            </button>
                            <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Сбросить
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Основной контент -->
        <div class="col-lg-9">
            <!-- Заголовок с результатами поиска -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    @if(request('search'))
                        Результаты поиска: "{{ request('search') }}"
                    @else
                        Каталог товаров
                    @endif
                    <small class="text-muted">({{ $products->total() }} товаров)</small>
                </h1>
            </div>
            
            <!-- Сообщение о результатах поиска -->
            @if(request('search') && $products->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    По запросу "{{ request('search') }}" ничего не найдено.
                    <a href="{{ route('catalog') }}" class="alert-link">Посмотреть все товары</a>
                </div>
            @endif
            
            <!-- Список товаров -->
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <!-- Изображение товара -->
                                <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                                    <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                        @if($product->images)
                                            @php
                                                $images = json_decode($product->images);
                                                $firstImage = $images[0] ?? null;
                                            @endphp
                                            @if($firstImage)
                                                <img src="{{ asset('storage/' . $firstImage) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-100 h-100" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-image text-muted" style="font-size: 48px;"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 48px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                
                                <!-- Тело карточки -->
                                <div class="card-body d-flex flex-column">
                                    <!-- Категория -->
                                    <small class="text-muted mb-1">
                                        {{ $product->category->name ?? 'Без категории' }}
                                    </small>
                                    
                                    <!-- Название -->
                                    <h6 class="card-title">
                                        <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark">
                                            {{ Str::limit($product->name, 60) }}
                                        </a>
                                    </h6>
                                    
                                    <!-- Артикул -->
                                    <small class="text-muted mb-2">Арт: {{ $product->code }}</small>
                                    
                                    <!-- Цена и наличие -->
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="text-primary mb-0">
                                                {{ number_format($product->price, 0, ',', ' ') }} ₽
                                                @if($product->old_price)
                                                    <small class="text-muted text-decoration-line-through d-block">
                                                        {{ number_format($product->old_price, 0, ',', ' ') }} ₽
                                                    </small>
                                                @endif
                                            </h5>
                                            @if($product->stock && $product->stock->quantity > 0)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>В наличии
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Нет в наличии
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Кнопка добавления в корзину -->
                                        @if($product->stock && $product->stock->quantity > 0)
                                            <button class="btn btn-primary w-100 add-to-cart-btn" 
                                                    data-product-id="{{ $product->id }}">
                                                <i class="bi bi-cart-plus me-2"></i>В корзину
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="bi bi-cart-x me-2"></i>Нет в наличии
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Пагинация -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @elseif(!request('search'))
                <div class="text-center py-5">
                    <i class="bi bi-box-seam text-muted" style="font-size: 64px;"></i>
                    <h4 class="mt-3">Товары не найдены</h4>
                    <p class="text-muted">Попробуйте изменить параметры поиска</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка добавления в корзину
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const button = this;
            const originalHtml = button.innerHTML;
            
            // Показываем индикатор загрузки
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Добавление...';
            button.disabled = true;
            
            // Отправляем AJAX запрос
            fetch('/cart/add/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Показываем уведомление
                    showNotification('Товар добавлен в корзину!', 'success');
                    
                    // Обновляем счетчик корзины
                    if (data.cart_count) {
                        updateCartCounter(data.cart_count);
                    }
                    
                    // Меняем вид кнопки
                    button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Добавлено';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    
                    // Через 2 секунды возвращаем исходный вид
                    setTimeout(() => {
                        button.innerHTML = originalHtml;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.disabled = false;
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalHtml;
                button.disabled = false;
                showNotification('Ошибка при добавлении в корзину', 'danger');
            });
        });
    });
    
    // Функция для показа уведомлений
    function showNotification(message, type = 'success') {
        // Реализация уведомлений...
    }
    
    // Функция обновления счетчика корзины
    function updateCartCounter(count) {
        // Реализация обновления счетчика...
    }
});
</script>

@endsection