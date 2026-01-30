@extends('layouts.master')

@section('title', 'Главная - AutoDetails')
@section('content')
<style>
.d {
    outline: none;
    border: none;
}

.card {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-link {
    text-decoration: none;
    color: inherit;
    transition: color 0.3s ease;
}

.product-link:hover {
    color: #c46f00;
}

.btn-primary {
    background-color: #c46f00;
    border-color: #c46f00;
}

.btn-primary:hover {
    background-color: #a55c00;
    border-color: #a55c00;
}

/* Анимации */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(-20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.slide-in {
    animation: slideIn 0.5s ease-out;
}

/* Стили для корзины */
.cart-count {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Карусель */
.carousel-item img {
    height: 400px;
    object-fit: cover;
}

/* Иконки преимуществ */
.feature-icon {
    font-size: 2.5rem;
    color: #c46f00;
    margin-bottom: 1rem;
}
</style>

<!-- ХЕДЕР -->
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

<!-- ГЛАВНЫЙ БАННЕР -->
<section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ asset('images/garage-bg.jpg') }}') center/cover;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white fade-in">
                <h1 class="display-4 fw-bold mb-4">Автозапчасти высшего качества</h1>
                <p class="lead mb-4">Более 10,000 запчастей в наличии. Гарантия качества, быстрая доставка и профессиональная консультация.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#categories" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-eye me-2"></i>Смотреть каталог
                    </a>
                    <a href="#delivery" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-truck me-2"></i>Условия доставки
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="card border-0 shadow-lg slide-in">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">Быстрый поиск запчастей</h4>
                        <form action="{{ route('catalog') }}" method="GET">
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg" placeholder="Название детали или артикул" name="search">
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <select class="form-select" name="category">
                                        <option value="">Все категории</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="price">
                                        <option value="">Ценовой диапазон</option>
                                        <option value="0-1000">До 1,000 ₽</option>
                                        <option value="1000-5000">1,000 - 5,000 ₽</option>
                                        <option value="5000-10000">5,000 - 10,000 ₽</option>
                                        <option value="10000+">От 10,000 ₽</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search me-2"></i>Найти запчасть
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ПРЕИМУЩЕСТВА -->
<section id="features" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Почему выбирают AutoDetails?</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5>Гарантия качества</h5>
                <p class="text-muted">Все товары проходят строгий контроль качества перед отправкой</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <h5>Быстрая доставка</h5>
                <p class="text-muted">Доставка по России от 1 дня. Более 100 пунктов выдачи</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="bi bi-currency-exchange"></i>
                </div>
                <h5>Выгодные цены</h5>
                <p class="text-muted">Прямые поставки от производителей позволяют держать низкие цены</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h5>Поддержка 24/7</h5>
                <p class="text-muted">Наши специалисты всегда готовы помочь с выбором запчастей</p>
            </div>
        </div>
    </div>
</section>

<!-- ПОПУЛЯРНЫЕ ТОВАРЫ -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Популярные товары</h2>
        </div>
        
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <!-- Бейдж популярности -->
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-danger">Популярный</span>
                            </div>
                            
                            <!-- Изображение товара -->
                            <a href="{{ route('product.show', $product->id) }}" class="product-link">
                                <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
                                    @if(!empty($product->images))
                                        @php
                                            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
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
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('product.show', $product->id) }}" class="product-link text-decoration-none">
                                        {{ Str::limit($product->name, 50) }}
                                    </a>
                                </h6>
                                
                                <!-- Рейтинг -->
                                <div class="d-flex align-items-center mb-2">
                                    @php
                                        $avgRating = $product->reviews->avg('rating') ?? 0;
                                        $reviewCount = $product->reviews->count();
                                    @endphp
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= floor($avgRating) ? '-fill' : ($i <= $avgRating ? '-half' : '') }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">({{ $reviewCount }})</small>
                                </div>
                                
                                <!-- Цена и наличие -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-primary mb-0">{{ number_format($product->price, 0, ',', ' ') }} ₽</h5>
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
                                        
                                        <!-- Скрытая форма для fallback -->
                                        <form id="cart-form-{{ $product->id }}" 
                                              action="{{ route('basket-add', $product->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                        </form>
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
        @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam text-muted" style="font-size: 64px;"></i>
                <p class="mt-3">Популярные товары появятся здесь</p>
            </div>
        @endif
    </div>
</section>

<!-- КАТЕГОРИИ ТОВАРОВ -->
<section id="categories" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Категории товаров</h2>
        
        @if($categories->count() > 0)
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="{{ route('category.show', $category->code) }}" class="product-link text-decoration-none">
                                <!-- Изображение категории -->
                                <div class="card-img-top position-relative" style="height: 150px; overflow: hidden;">
                                    @if($category->image)
                                        @php
                                            $imagePath = public_path('storage/' . $category->image);
                                            $imageUrl = asset('storage/' . $category->image);
                                        @endphp
                                        @if(file_exists($imagePath))
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="w-100 h-100" 
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-tags text-muted" style="font-size: 48px;"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                            <i class="bi bi-tags text-muted" style="font-size: 48px;"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Тело карточки -->
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-2">{{ $category->name }}</h5>
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($category->description ?? 'Автозапчасти', 80) }}
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="badge bg-primary">
                                            {{ $category->products_count ?? 0 }} товаров
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Все категории
                </a>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags text-muted" style="font-size: 64px;"></i>
                <p class="mt-3">Категории будут добавлены в ближайшее время</p>
            </div>
        @endif
    </div>
</section>

<!-- О КОМПАНИИ -->
<section id="about" class="py-5" style="background-color: #c46f00;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="{{ asset('images/about.jpg') }}" alt="О компании AutoDetails" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-lg-6 text-white">
                <h2 class="mb-4">О компании AutoDetails</h2>
                <p class="lead mb-4">Более 10 лет мы обеспечиваем автомобилистов качественными запчастями по доступным ценам.</p>
                
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-3 me-3">
                                <i class="bi bi-check-circle-fill text-primary" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">10,000+</h5>
                                <small>Товаров в каталоге</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-3 me-3">
                                <i class="bi bi-people-fill text-primary" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">50,000+</h5>
                                <small>Довольных клиентов</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-3 me-3">
                                <i class="bi bi-truck text-primary" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">134</h5>
                                <small>Города доставки</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-3 me-3">
                                <i class="bi bi-award-fill text-primary" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">10 лет</h5>
                                <small>На рынке</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('about') }}" class="btn btn-light btn-lg mt-3">
                    <i class="bi bi-info-circle me-2"></i>Подробнее о нас
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ДОСТАВКА И ОПЛАТА -->
<section id="delivery" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Доставка и оплата</h2>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="bi bi-truck text-primary me-2"></i>Быстрая доставка по всей России
                        </h4>
                        <p class="card-text">
                            Мы доставляем товары в любой город России. Срок доставки зависит от выбранного способа и составляет от 1 до 7 дней.
                        </p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-geo-alt" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <h5>Пункты выдачи</h5>
                                        <p class="text-muted mb-0">Более 1000 пунктов выдачи по всей стране</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="bg-success text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-house-door" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <h5>Курьерская доставка</h5>
                                        <p class="text-muted mb-0">До двери в удобное для вас время</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-primary border-2">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Способы оплаты</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            <div class="text-center">
                                <i class="bi bi-credit-card-2-front text-primary" style="font-size: 32px;"></i>
                                <p class="small mb-0 mt-2">Банковские карты</p>
                            </div>
                            <div class="text-center">
                                <i class="bi bi-phone text-primary" style="font-size: 32px;"></i>
                                <p class="small mb-0 mt-2">Электронные кошельки</p>
                            </div>
                            <div class="text-center">
                                <i class="bi bi-cash text-primary" style="font-size: 32px;"></i>
                                <p class="small mb-0 mt-2">Наложенный платеж</p>
                            </div>
                        </div>
                        <a href="{{ route('payment') }}" class="btn btn-outline-primary w-100">
                            Подробнее об оплате
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ФУТЕР -->
<footer id="contacts" class="py-5" style="background-color: #343a40;">
    <div class="container">
        <div class="row">
            <!-- Контакты -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="text-white mb-4">Контакты</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone text-primary me-3"></i>
                            <div>
                                <p class="text-white mb-0">+7 (800) 123-45-67</p>
                                <small class="text-muted">Бесплатный звонок по России</small>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-3"></i>
                            <div>
                                <p class="text-white mb-0">info@autodetails.com</p>
                                <small class="text-muted">Электронная почта</small>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt text-primary me-3 mt-1"></i>
                            <div>
                                <p class="text-white mb-0">г. Дзержинск</p>
                                <p class="text-white mb-0">ул. Циолковского д. 1, корп. 2</p>
                                <small class="text-muted">Пн-Пт: 9:00-18:00</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Быстрые ссылки -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="text-white mb-4">Магазин</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('catalog') }}" class="text-decoration-none text-white-50">Каталог товаров</a></li>
                    <li class="mb-2"><a href="{{ route('categories.index') }}" class="text-decoration-none text-white-50">Категории</a></li>
                    <li class="mb-2"><a href="{{ route('delivery') }}" class="text-decoration-none text-white-50">Доставка</a></li>
                    <li class="mb-2"><a href="{{ route('payment') }}" class="text-decoration-none text-white-50">Оплата</a></li>
                    <li><a href="{{ route('contacts') }}" class="text-decoration-none text-white-50">Контакты</a></li>
                </ul>
            </div>
            
            <!-- Информация -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="text-white mb-4">Информация</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-decoration-none text-white-50">О компании</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}" class="text-decoration-none text-white-50">Условия использования</a></li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" class="text-decoration-none text-white-50">Политика конфиденциальности</a></li>
                    <li><a href="#" class="text-decoration-none text-white-50">Гарантия и возврат</a></li>
                </ul>
            </div>
            
            <!-- Соцсети и подписка -->
            <div class="col-lg-4 col-md-4">
                <h5 class="text-white mb-4">Мы в соцсетях</h5>
                <div class="d-flex gap-3 mb-4">
                    <a href="#" class="text-white fs-4"><i class="bi bi-vk"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                </div>
                
                <h5 class="text-white mb-3">Подписка на новости</h5>
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Ваш email">
                    <button class="btn btn-primary" type="button">Подписаться</button>
                </div>
            </div>
        </div>
        
        <hr class="bg-secondary my-5">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-white-50 mb-0">
                    &copy; 2025 AutoDetails. Все права защищены.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <img src="{{ asset('images/visa.png') }}" alt="Visa" height="30" class="me-2">
                <img src="{{ asset('images/mastercard.png') }}" alt="MasterCard" height="30" class="me-2">
                <img src="{{ asset('images/mir.png') }}" alt="МИР" height="30">
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript для AJAX корзины -->
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для показа уведомления
    function showNotification(message, type = 'success') {
        // Создаем элемент уведомления
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 350px;';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Добавляем на страницу
        document.body.appendChild(notification);
        
        // Автоматически скрываем через 3 секунды
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
    
    // Обработка добавления в корзину через AJAX
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
            fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    product_id: productId 
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Обновляем счетчик корзины в хедере
                    updateCartCounter(data.count);
                    
                    // Показываем успешное уведомление
                    showNotification(data.message, 'success');
                    
                    // Меняем вид кнопки на успешное добавление
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
                } else {
                    // Показываем ошибку
                    showNotification(data.message, 'danger');
                    
                    // Восстанавливаем кнопку
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Если AJAX не сработал, показываем сообщение
                showNotification('Ошибка соединения. Попробуйте еще раз.', 'danger');
                
                // Пробуем отправить обычную форму
                const form = document.getElementById('cart-form-' + productId);
                if (form) {
                    form.submit();
                } else {
                    // Если формы нет, просто восстанавливаем кнопку
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }
            });
        });
    });
    
    // Функция обновления счетчика корзины
    function updateCartCounter(count) {
        // Обновляем все счетчики на странице
        document.querySelectorAll('.cart-count').forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'block' : 'none';
        });
        
        // Обновляем текст в ссылке корзины
        document.querySelectorAll('a[href*="basket"]').forEach(link => {
            const textSpan = link.querySelector('.cart-text');
            if (textSpan) {
                textSpan.textContent = count > 0 ? `Корзина (${count})` : 'Корзина';
            }
        });
    }
    
    // Инициализируем счетчик корзины при загрузке страницы
    function initCartCounter() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                updateCartCounter(data.count);
            })
            .catch(error => {
                console.error('Error loading cart count:', error);
            });
    }
    
    // Запускаем инициализацию
    initCartCounter();
});
</script>

@endsection
@endsection