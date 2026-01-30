@extends('layouts.master')

@section('title', $product->name)
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
            <li class="breadcrumb-item"><a href="{{ route('category.show', $category->code) }}">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
     <!-- Сообщения об успехе/ошибке -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <!-- Галерея изображений -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body p-3">
                    <!-- Главное изображение -->
                    <div class="text-center mb-3">
                        @if($product->images)
                            @php
                                $images = json_decode($product->images);
                                $firstImage = $images[0] ?? null;
                            @endphp
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" 
                                     id="main-image" 
                                     class="img-fluid rounded" 
                                     alt="{{ $product->name }}"
                                     style="max-height: 400px; object-fit: contain;">
                            @endif
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 400px;">
                                <i class="bi bi-image text-muted" style="font-size: 64px;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Миниатюры -->
                    @if($product->images && count(json_decode($product->images)) > 1)
                        <div class="d-flex justify-content-center">
                            @foreach(json_decode($product->images) as $index => $image)
                                <div class="border rounded p-1 me-2 cursor-pointer" 
                                     onclick="changeMainImage('{{ asset('storage/' . $image) }}')"
                                     style="width: 60px; height: 60px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="img-fluid" 
                                         alt="Изображение {{ $index + 1 }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Информация о товаре -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="h2">{{ $product->name }}</h1>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating me-3">
                            @php
                                $avgRating = $reviews->avg('rating') ?? 0;
                                $reviewCount = $reviews->count();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= floor($avgRating) ? '-fill' : ($i <= $avgRating ? '-half' : '') }} text-warning"></i>
                            @endfor
                            <span class="ms-2">({{ $reviewCount }} отзывов)</span>
                        </div>
                        @if($product->popular)
                            <span class="badge bg-danger">Популярный</span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-primary mb-0">{{ number_format($product->price, 0, ',', ' ') }} ₽</h3>
                        <small class="text-muted">Цена за 1 шт.</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-3">Наличие:</span>
                            @if($product->stock && $product->stock->quantity > 0)
                                <span class="badge bg-success">В наличии</span>
                                <small class="text-muted ms-2">{{ $product->stock->quantity }} шт.</small>
                            @else
                                <span class="badge bg-secondary">Нет в наличии</span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <span class="me-3">Артикул:</span>
                            <span class="text-muted">{{ $product->code }}</span>
                        </div>
                        
                        <div>
                            <span class="me-3">Категория:</span>
                            <a href="{{ route('category.show', $category->code) }}" class="text-primary">
                                {{ $category->name }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Форма добавления в корзину -->
                    @if($product->stock && $product->stock->quantity > 0)
                        <form action="{{ route('basket-add', $product->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Количество</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                                        <input type="number" class="form-control text-center" 
                                               id="quantity" name="quantity" value="1" min="1" 
                                               max="{{ $product->stock->quantity }}">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-8 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-cart-plus me-2"></i>Добавить в корзину
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Товар временно отсутствует на складе
                        </div>
                    @endif
                    
                    <!-- Кнопки действий -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill" onclick="addToFavorites()">
                            <i class="bi bi-heart me-2"></i>В избранное
                        </button>
                        <button class="btn btn-outline-primary flex-fill" onclick="addToCompare()">
                            <i class="bi bi-bar-chart me-2"></i>Сравнить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Табы с информацией -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab">
                        Описание
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="characteristics-tab" data-bs-toggle="tab" 
                            data-bs-target="#characteristics" type="button" role="tab">
                        Характеристики
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button" role="tab">
                        Отзывы ({{ $reviewCount }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabsContent">
                <!-- Описание -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Описание товара</h5>
                            <p class="card-text">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Характеристики -->
                <div class="tab-pane fade" id="characteristics" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Характеристики</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td width="40%"><strong>Артикул</strong></td>
                                            <td>{{ $product->code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Категория</strong></td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>На складе</strong></td>
                                            <td>{{ $product->stock ? $product->stock->quantity : 0 }} шт.</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <!-- Дополнительные характеристики -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Отзывы -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Отзывы о товаре</h5>
                            
                            @auth
                                <!-- Форма добавления отзыва -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Оставить отзыв</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('review.store', $product->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Оценка</label>
                                                <div class="rating-input">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                                               class="visually-hidden" {{ $i == 5 ? 'checked' : '' }}>
                                                        <label for="star{{ $i }}" class="star-label">
                                                            <i class="bi bi-star"></i>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="advantages" class="form-label">Достоинства</label>
                                                    <textarea class="form-control" id="advantages" name="advantages" 
                                                              rows="2" placeholder="Что вам понравилось?"></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="disadvantages" class="form-label">Недостатки</label>
                                                    <textarea class="form-control" id="disadvantages" name="disadvantages" 
                                                              rows="2" placeholder="Что можно улучшить?"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Комментарий *</label>
                                                <textarea class="form-control" id="comment" name="comment" 
                                                          rows="3" required placeholder="Подробно опишите ваш опыт использования"></textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-send me-2"></i>Отправить отзыв
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Чтобы оставить отзыв, необходимо 
                                    <a href="{{ route('login') }}" class="alert-link">войти</a> или 
                                    <a href="{{ route('register') }}" class="alert-link">зарегистрироваться</a>.
                                </div>
                            @endauth
                            
                            <!-- Список отзывов -->
                            <div class="reviews-list">
                                @if($reviews->count() > 0)
                                    @foreach($reviews as $review)
                                        <div class="review-item border-bottom pb-4 mb-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <div class="rating small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $review->created_at->format('d.m.Y') }}
                                                </small>
                                            </div>
                                            
                                            @if($review->advantages)
                                                <div class="mb-2">
                                                    <strong class="text-success">✓ Достоинства:</strong>
                                                    <p class="mb-0">{{ $review->advantages }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($review->disadvantages)
                                                <div class="mb-2">
                                                    <strong class="text-danger">✗ Недостатки:</strong>
                                                    <p class="mb-0">{{ $review->disadvantages }}</p>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <strong>Комментарий:</strong>
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-chat-left-text text-muted" style="font-size: 48px;"></i>
                                        <p class="mt-3">Пока нет отзывов о товаре</p>
                                        <p class="text-muted small">Будьте первым, кто оставит отзыв!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeQuantity(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + change;
    const max = parseInt(input.max);
    const min = parseInt(input.min);
    
    if (value > max) value = max;
    if (value < min) value = min;
    
    input.value = value;
}

function changeMainImage(src) {
    document.getElementById('main-image').src = src;
}

function addToFavorites() {
    // Логика добавления в избранное
    alert('Товар добавлен в избранное');
}

function addToCompare() {
    // Логика добавления в сравнение
    alert('Товар добавлен в сравнение');
}

// Рейтинг при добавлении отзыва
document.querySelectorAll('.star-label').forEach(label => {
    label.addEventListener('click', function() {
        const rating = this.htmlFor.replace('star', '');
        document.querySelectorAll('.star-label').forEach(l => {
            const starNum = parseInt(l.htmlFor.replace('star', ''));
            const icon = l.querySelector('i');
            icon.classList.remove('bi-star-fill', 'bi-star');
            icon.classList.add(starNum <= rating ? 'bi-star-fill' : 'bi-star');
        });
    });
});
</script>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.star-label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
    transition: color 0.2s;
}
.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
}
input[type="radio"]:checked ~ .star-label {
    color: #ffc107;
}
.review-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
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