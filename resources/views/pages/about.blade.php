@extends('layouts.master')

@section('title', 'О компании AutoDetails')

@section('content')
<style>
    .feature-icon {
        width: 60px;
        height: 60px;
        background: #c46f00;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .feature-icon i {
        font-size: 24px;
        color: white;
    }
    
    .team-member {
        transition: transform 0.3s ease;
    }
    
    .team-member:hover {
        transform: translateY(-10px);
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #c46f00;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -36px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #c46f00;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #c46f00;
    }
</style>
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
<!-- Заголовок страницы -->
<section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ asset('images/about-hero.jpg') }}') center/cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold mb-4">О компании AutoDetails</h1>
                <p class="lead mb-0">Более 10 лет мы обеспечиваем автомобилистов качественными запчастями</p>
            </div>
        </div>
    </div>
</section>

<!-- О компании -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset('images/about-company.jpg') }}" 
                     alt="О компании AutoDetails" 
                     class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Наша история</h2>
                <p class="lead mb-4">
                    AutoDetails была основана в 2015 году с целью предоставить автомобилистам России доступ к качественным автозапчастям по разумным ценам.
                </p>
                <p class="mb-4">
                    Начиная с небольшого магазина в Дзержинске, сегодня мы превратились в один из крупнейших онлайн-магазинов автозапчастей в России с представительствами в 50 городах.
                </p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="text-center p-3">
                            <h3 class="text-primary mb-0">10+</h3>
                            <p class="text-muted mb-0">Лет на рынке</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3">
                            <h3 class="text-primary mb-0">50,000+</h3>
                            <p class="text-muted mb-0">Довольных клиентов</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Наша миссия -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="mb-4">Наша миссия</h2>
                <p class="lead">
                    Сделать покупку автозапчастей простой, быстрой и выгодной для каждого автомобилиста России.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>Качество</h4>
                    <p class="text-muted">
                        Все товары проходят строгий контроль качества. Мы работаем только с проверенными поставщиками и производителями.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4>Доступность</h4>
                    <p class="text-muted">
                        Более 10,000 наименований всегда в наличии. Быстрая доставка в любой регион России.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Поддержка</h4>
                    <p class="text-muted">
                        Профессиональные консультанты помогут подобрать запчасти именно для вашего автомобиля.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Этапы развития -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Этапы развития</h2>
        
        <div class="timeline">
            <div class="timeline-item">
                <h4>2015</h4>
                <p class="text-muted">Основание компании. Открытие первого магазина в Дзержинске.</p>
            </div>
            <div class="timeline-item">
                <h4>2017</h4>
                <p class="text-muted">Запуск интернет-магазина. Расширение ассортимента до 1,000 позиций.</p>
            </div>
            <div class="timeline-item">
                <h4>2019</h4>
                <p class="text-muted">Открытие складов в Москве и Санкт-Петербурге. Доставка по всей России.</p>
            </div>
            <div class="timeline-item">
                <h4>2021</h4>
                <p class="text-muted">Выход на международный рынок. Запуск мобильного приложения.</p>
            </div>
            <div class="timeline-item">
                <h4>2023</h4>
                <p class="text-muted">Более 50,000 клиентов. Расширение ассортимента до 10,000 позиций.</p>
            </div>
        </div>
    </div>
</section>

<!-- Команда -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Наша команда</h2>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm team-member">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/team-1.jpg') }}" 
                                 alt="Александр Петров" 
                                 class="rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">Александр Петров</h5>
                        <p class="text-muted small mb-3">Основатель компании</p>
                        <p class="small text-muted">Опыт в автомобильной индустрии более 15 лет</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm team-member">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/team-2.jpg') }}" 
                                 alt="Мария Иванова" 
                                 class="rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">Мария Иванова</h5>
                        <p class="text-muted small mb-3">Технический директор</p>
                        <p class="small text-muted">Специалист по подбору автозапчастей</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm team-member">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/team-3.jpg') }}" 
                                 alt="Дмитрий Смирнов" 
                                 class="rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">Дмитрий Смирнов</h5>
                        <p class="text-muted small mb-3">Директор по логистике</p>
                        <p class="small text-muted">Организует быструю доставку по всей стране</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm team-member">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/team-4.jpg') }}" 
                                 alt="Елена Козлова" 
                                 class="rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">Елена Козлова</h5>
                        <p class="text-muted small mb-3">Руководитель отдела качества</p>
                        <p class="small text-muted">Контролирует качество всех поставляемых товаров</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Преимущества -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="bi bi-award text-primary me-2"></i>Наши сертификаты
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Сертификат качества ISO 9001:2015
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Лицензия на торговлю автозапчастями
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Сертификат официального дилера
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Награда "Лучший онлайн-магазин 2023"
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="bi bi-hand-thumbs-up text-primary me-2"></i>Наши гарантии
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-shield-check text-primary me-2"></i>
                                Гарантия на все товары от 6 месяцев до 3 лет
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-arrow-return-left text-primary me-2"></i>
                                Возврат товара в течение 14 дней
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-truck text-primary me-2"></i>
                                Бесплатная доставка при заказе от 5,000 ₽
                            </li>
                            <li>
                                <i class="bi bi-headset text-primary me-2"></i>
                                Техническая поддержка 24/7
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-3">Станьте частью нашей истории!</h3>
                <p class="mb-0">Присоединяйтесь к 50,000 довольных клиентов AutoDetails</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('catalog') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-cart-check me-2"></i>Перейти в каталог
                </a>
            </div>
        </div>
    </div>
</section>
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
@endsection