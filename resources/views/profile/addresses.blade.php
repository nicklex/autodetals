@extends('layouts.master')

@section('title', 'Адреса доставки')

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
                <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1">Заказы</a>
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


@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Меню профиля</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="{{ route('profile.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-person me-2"></i>Личные данные
                        </a>
                    </li>
                    <li class="list-group-item active">
                        <a href="{{ route('profile.addresses') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-geo-alt me-2"></i>Адреса доставки
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-bag me-2"></i>Мои заказы
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('reviews.my') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-chat-left-text me-2"></i>Мои отзывы
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Адреса доставки</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="bi bi-plus-lg me-2"></i>Добавить адрес
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(count($addresses) > 0)
                        <div class="row">
                            @foreach($addresses as $index => $address)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">Адрес {{ $index + 1 }}</h5>
                                                <form action="{{ route('profile.addresses.delete', $index) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Удалить этот адрес?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <p class="card-text">{{ $address }}</p>
                                            <div class="mt-3">
                                                <button class="btn btn-outline-primary btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editAddressModal{{ $index }}">
                                                    <i class="bi bi-pencil me-1"></i>Редактировать
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt text-muted" style="font-size: 64px;"></i>
                            <h4 class="mt-3">Адреса доставки не добавлены</h4>
                            <p class="text-muted">Добавьте адрес для быстрого оформления заказов</p>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-lg me-2"></i>Добавить первый адрес
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления адреса -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить адрес доставки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.addresses.add') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="city" class="form-label">Город *</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Адрес (улица, дом) *</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="apartment" class="form-label">Квартира/офис</label>
                            <input type="text" class="form-control" id="apartment" name="apartment">
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Почтовый индекс</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить адрес</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальные окна редактирования -->
@foreach($addresses as $index => $address)
<div class="modal fade" id="editAddressModal{{ $index }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать адрес</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.addresses.update', $index) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_city{{ $index }}" class="form-label">Город *</label>
                        <input type="text" class="form-control" id="edit_city{{ $index }}" 
                               name="city" value="{{ explode(',', $address)[0] ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address{{ $index }}" class="form-label">Адрес *</label>
                        <input type="text" class="form-control" id="edit_address{{ $index }}" 
                               name="address" value="{{ explode(',', $address)[1] ?? $address }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_apartment{{ $index }}" class="form-label">Квартира/офис</label>
                            <input type="text" class="form-control" id="edit_apartment{{ $index }}" 
                                   name="apartment" value="{{ explode('кв.', $address)[1] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_postal_code{{ $index }}" class="form-label">Индекс</label>
                            <input type="text" class="form-control" id="edit_postal_code{{ $index }}" 
                                   name="postal_code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Обновить адрес</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

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
                    <a href="{{ route('profile.addresses') }}" class="text-white mx-2 my-1">Адреса</a>
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