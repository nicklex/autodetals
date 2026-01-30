@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Просмотр товара</h1>
        <div class="btn-group">
            <a href="{{ route('product.show', $product->id) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-external-link-alt"></i> На сайте
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка -->
        <div class="col-lg-8">
            <!-- Карточка товара -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Основная информация</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Изображения -->
                            @if($product->images && count(json_decode($product->images)) > 0)
                                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach(json_decode($product->images) as $index => $image)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ Storage::url($image) }}" 
                                                     class="d-block w-100 rounded" 
                                                     alt="{{ $product->name }}"
                                                     style="height: 300px; object-fit: contain;">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count(json_decode($product->images)) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 300px;">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $product->name }}</h3>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-muted">Артикул:</div>
                                    <strong>{{ $product->code }}</strong>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Категория:</div>
                                    @if($product->category)
                                        <span class="badge bg-info">{{ $product->category->name }}</span>
                                    @else
                                        <span class="text-muted">Без категории</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-muted">Цена:</div>
                                    <h4 class="text-primary">{{ number_format($product->price, 0, ',', ' ') }} ₽</h4>
                                    @if($product->old_price)
                                        <div class="text-muted text-decoration-line-through">
                                            {{ number_format($product->old_price, 0, ',', ' ') }} ₽
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Запас на складе:</div>
                                    @if($product->stock)
                                        @if($product->stock->quantity < 10)
                                            <h4 class="text-danger">{{ $product->stock->quantity }} шт.</h4>
                                        @else
                                            <h4 class="text-success">{{ $product->stock->quantity }} шт.</h4>
                                        @endif
                                    @else
                                        <h4 class="text-danger">0 шт.</h4>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-muted">Статус:</div>
                                <span class="badge 
                                    @if($product->is_active) bg-success @else bg-secondary @endif">
                                    {{ $product->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                            </div>
                            
                            @if($product->brand || $product->weight || $product->dimensions)
                            <div class="mb-3">
                                <h6>Характеристики:</h6>
                                <div class="row">
                                    @if($product->brand)
                                        <div class="col-4">
                                            <small class="text-muted">Бренд:</small>
                                            <div>{{ $product->brand }}</div>
                                        </div>
                                    @endif
                                    @if($product->weight)
                                        <div class="col-4">
                                            <small class="text-muted">Вес:</small>
                                            <div>{{ $product->weight }} г</div>
                                        </div>
                                    @endif
                                    @if($product->dimensions)
                                        <div class="col-4">
                                            <small class="text-muted">Размеры:</small>
                                            <div>{{ $product->dimensions }} мм</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($product->description)
                    <div class="mt-4">
                        <h6>Описание:</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Отзывы -->
            @if($product->reviews && $product->reviews->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Отзывы ({{ $product->reviews->count() }})</h5>
                    <a href="{{ route('admin.reviews.index') }}?product_id={{ $product->id }}" class="btn btn-sm btn-primary">
                        Все отзывы
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($product->reviews->take(3) as $review)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $review->user->name ?? 'Аноним' }}</h6>
                                <small>{{ $review->created_at->format('d.m.Y') }}</small>
                            </div>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-1">{{ $review->comment }}</p>
                            @if(!$review->is_approved)
                                <span class="badge bg-warning">На модерации</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Правая колонка -->
        <div class="col-lg-4">
            <!-- Быстрые действия -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.stocks.edit', $product->id) }}" class="btn btn-warning">
                            <i class="fas fa-warehouse"></i> Управление запасом
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Редактировать товар
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Удалить товар? Это действие нельзя отменить.')">
                                <i class="fas fa-trash"></i> Удалить товар
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Статистика -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Информация</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Создан:</span>
                            <strong>{{ $product->created_at->format('d.m.Y H:i') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Обновлен:</span>
                            <strong>{{ $product->updated_at->format('d.m.Y H:i') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Просмотры:</span>
                            <strong>{{ $product->views ?? 0 }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- SEO информация -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">SEO информация</h5>
                </div>
                <div class="card-body">
                    @if($product->meta_title || $product->meta_description)
                        <div class="mb-3">
                            <small class="text-muted">Meta Title:</small>
                            <div>{{ $product->meta_title ?: 'Не указан' }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Meta Description:</small>
                            <div>{{ $product->meta_description ?: 'Не указана' }}</div>
                        </div>
                    @else
                        <p class="text-muted mb-0">SEO настройки не указаны</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection