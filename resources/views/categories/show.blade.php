@extends('layouts.master')

@section('title', $category->name)
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
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
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
                    <a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
                @endauth
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Категории</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>
    
    <!-- Заголовок категории -->
    <div class="row mb-5">
        <div class="col-md-3">
            @if($category->image && file_exists(public_path('storage/' . $category->image)))
                <img src="{{ asset('storage/' . $category->image) }}" 
                     class="img-fluid rounded" 
                     alt="{{ $category->name }}">
            @endif
        </div>
        <div class="col-md-9">
            <h1 class="display-5">{{ $category->name }}</h1>
            <p class="lead">{{ $category->description }}</p>
            <p class="text-muted">Найдено товаров: {{ $products->total() }}</p>
        </div>
    </div>
    
    <!-- Фильтры -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Поиск в категории..." id="search-input">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="sort-select">
                        <option value="price_asc">Сначала дешевые</option>
                        <option value="price_desc">Сначала дорогие</option>
                        <option value="name_asc">По названию (А-Я)</option>
                        <option value="name_desc">По названию (Я-А)</option>
                        <option value="popular">Популярные</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary active" data-view="grid">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-view="list">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Товары -->
    <div class="row" id="products-grid">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <a href="{{ route('product.show', $product->id) }}">
                        @if($product->images)
                            @php
                                $images = json_decode($product->images);
                                $firstImage = $images[0] ?? null;
                            @endphp
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 48px;"></i>
                                </div>
                            @endif
                        @endif
                    </a>
                    @if($product->popular)
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Популярный</span>
                    @endif
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark">
                            {{ $product->name }}
                        </a>
                    </h5>
                    <p class="card-text small text-muted">
                        {{ Str::limit($product->description, 80) }}
                    </p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                            <span class="badge bg-{{ $product->stock && $product->stock->quantity > 0 ? 'success' : 'secondary' }}">
                                {{ $product->stock && $product->stock->quantity > 0 ? 'В наличии' : 'Нет в наличии' }}
                            </span>
                        </div>
                        
                        @if($product->stock && $product->stock->quantity > 0)
                            <form action="{{ route('basket-add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-cart-plus me-2"></i>В корзину
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                Нет в наличии
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Пагинация -->
    @if($products->hasPages())
        <nav class="mt-5">
            {{ $products->links() }}
        </nav>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Переключение вида
    const viewButtons = document.querySelectorAll('[data-view]');
    const productsGrid = document.getElementById('products-grid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.view === 'list') {
                productsGrid.classList.add('list-view');
                productsGrid.querySelectorAll('.col-md-4').forEach(col => {
                    col.classList.remove('col-md-4');
                    col.classList.add('col-12');
                });
            } else {
                productsGrid.classList.remove('list-view');
                productsGrid.querySelectorAll('.col-12').forEach(col => {
                    col.classList.remove('col-12');
                    col.classList.add('col-md-4');
                });
            }
        });
    });
});
</script>

<style>
.list-view .card {
    flex-direction: row !important;
}
.list-view .card img {
    width: 200px;
    height: 200px;
    object-fit: cover;
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