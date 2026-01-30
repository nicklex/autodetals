@extends('layouts.master')

@section('title', 'Оформление заказа')
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
                <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a>
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
                <a href="{{ route('checkout') }}" class="text-white mx-2 my-1 active">Оформление</a>
            </nav>

            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                <span class="text-white me-3">Привет, {{ auth()->user()->name }}!</span>
                <a href="{{ route('profile.index') }}" class="btn btn-outline-light">Профиль</a>
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
            <li class="breadcrumb-item"><a href="{{ route('basket') }}">Корзина</a></li>
            <li class="breadcrumb-item active">Оформление заказа</li>
        </ol>
    </nav>
    
    <!-- Прогресс -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center">
                    <div class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="mt-2 small">Корзина</div>
                </div>
                
                <div class="flex-grow-1 mx-3">
                    <div class="progress" style="height: 2px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div class="mt-2 small">Данные</div>
                </div>
                
                <div class="flex-grow-1 mx-3">
                    <div class="progress" style="height: 2px;">
                        <div class="progress-bar bg-secondary" style="width: 50%"></div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex justify-content-center align-items-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="mt-2 small">Доставка</div>
                </div>
                
                <div class="flex-grow-1 mx-3">
                    <div class="progress" style="height: 2px;">
                        <div class="progress-bar bg-secondary" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="rounded-circle bg-secondary text-white d-inline-flex justify-content-center align-items-center" 
                         style="width: 40px; height: 40px;">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="mt-2 small">Оплата</div>
                </div>
            </div>
        </div>
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('order.store') }}" id="checkout-form">
        @csrf
        
        <div class="row">
            <!-- Левая колонка - данные заказа -->
            <div class="col-lg-8">
                <!-- Контактная информация -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="bi bi-person me-2"></i>Контактная информация
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Имя и фамилия *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Телефон *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Адрес доставки -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="bi bi-geo-alt me-2"></i>Адрес доставки
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($addresses))
                            <div class="mb-3">
                                <label class="form-label">Выберите сохраненный адрес</label>
                                <div class="list-group mb-3">
                                    @foreach($addresses as $index => $address)
                                        <div class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="address_option" 
                                                       id="address{{ $index }}" 
                                                       value="{{ $address }}"
                                                       {{ $loop->first ? 'checked' : '' }}>
                                                <label class="form-check-label" for="address{{ $index }}">
                                                    {{ $address }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <div class="list-group-item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="address_option" 
                                                   id="address_new" 
                                                   value="new">
                                            <label class="form-check-label" for="address_new">
                                                Новый адрес
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div id="new-address-section" class="{{ empty($addresses) ? '' : 'd-none' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">Город *</label>
                                    <input type="text" class="form-control" 
                                           id="city" name="city" 
                                           value="{{ old('city') }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label">Индекс</label>
                                    <input type="text" class="form-control" 
                                           id="postal_code" name="postal_code" 
                                           value="{{ old('postal_code') }}">
                                </div>
                                
                        <div class="col-md-8 mb-3">
    <label for="address" class="form-label">Улица, дом *</label>
    <input type="text" class="form-control @error('address') is-invalid @enderror" 
           id="street_address" name="street_address" 
           value="{{ old('street_address') }}">
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    <!-- Скрытое поле для финального адреса -->
    <input type="hidden" name="address" id="final_address" value="{{ old('address') }}">
</div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="apartment" class="form-label">Квартира/офис</label>
                                    <input type="text" class="form-control" 
                                           id="apartment" name="apartment" 
                                           value="{{ old('apartment') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" 
                                   id="save_address" name="save_address" value="1">
                            <label class="form-check-label" for="save_address">
                                Сохранить адрес для будущих заказов
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Способ доставки -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="bi bi-truck me-2"></i>Способ доставки
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" 
                                   name="delivery_method" id="delivery_standard" 
                                   value="standard" checked>
                            <label class="form-check-label" for="delivery_standard">
                                <strong>Стандартная доставка</strong>
                                <div class="text-muted small">
                                    @if($subtotal >= 5000)
                                        Бесплатно (при заказе от 5,000 ₽)
                                    @else
                                        350 ₽ (бесплатно при заказе от 5,000 ₽)
                                    @endif
                                    <br>
                                    3-7 рабочих дней
                                </div>
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="delivery_method" id="delivery_express" 
                                   value="express">
                            <label class="form-check-label" for="delivery_express">
                                <strong>Экспресс-доставка</strong>
                                <div class="text-muted small">
                                    650 ₽<br>
                                    1-2 рабочих дня
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Способ оплаты -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="bi bi-credit-card me-2"></i>Способ оплаты
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" 
                                   name="payment_method" id="payment_card" 
                                   value="card" checked>
                            <label class="form-check-label" for="payment_card">
                                <i class="bi bi-credit-card-2-front me-2"></i>
                                Банковской картой онлайн
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" 
                                   name="payment_method" id="payment_cash" 
                                   value="cash">
                            <label class="form-check-label" for="payment_cash">
                                <i class="bi bi-cash-coin me-2"></i>
                                Наложенный платеж
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="payment_method" id="payment_online" 
                                   value="online">
                            <label class="form-check-label" for="payment_online">
                                <i class="bi bi-wallet2 me-2"></i>
                                Электронными деньгами
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Комментарий к заказу -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="bi bi-chat-left-text me-2"></i>Комментарий к заказу
                        </h4>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="comment" name="comment" 
                                  rows="3" placeholder="Дополнительные пожелания к заказу...">{{ old('comment') }}</textarea>
                    </div>
                </div>
                
                <!-- Соглашения -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" id="terms" name="terms" value="1" required>
                            <label class="form-check-label" for="terms">
                                Я согласен с <a href="{{ route('terms') }}" target="_blank">условиями покупки</a> 
                                и <a href="{{ route('privacy') }}" target="_blank">политикой конфиденциальности</a> *
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Правая колонка - итоги заказа -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ваш заказ</h5>
                    </div>
                    <div class="card-body">
                        <!-- Товары -->
                        <div class="mb-3">
                            @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span>{{ $item['product']->name }}</span>
                                        <small class="text-muted d-block">× {{ $item['quantity'] }} шт.</small>
                                    </div>
                                    <span>{{ number_format($item['total'], 0, ',', ' ') }} ₽</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <!-- Подытог -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Товары:</span>
                            <span>{{ number_format($subtotal, 0, ',', ' ') }} ₽</span>
                        </div>
                        
                        <!-- Доставка -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Доставка:</span>
                            <span id="delivery-cost">
                                @if($deliveryCost === 0)
                                    Бесплатно
                                @else
                                    {{ number_format($deliveryCost, 0, ',', ' ') }} ₽
                                @endif
                            </span>
                        </div>
                        
                        <hr>
                        
                        <!-- Итого -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Итого:</span>
                            <span class="h4 text-primary" id="total-cost">
                                {{ number_format($subtotal + $deliveryCost, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                        
                        <!-- Кнопка оформления -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-lock me-2"></i>Подтвердить заказ
                        </button>
                        
                        <small class="text-muted d-block mt-2 text-center">
                            Нажимая кнопку, вы подтверждаете свой заказ
                        </small>
                        
                        <!-- Вернуться в корзину -->
                        <a href="{{ route('basket') }}" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="bi bi-arrow-left me-2"></i>Вернуться в корзину
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout page loaded');
    
    // Основные элементы
    const addressRadios = document.querySelectorAll('input[name="address_option"]');
    const newAddressSection = document.getElementById('new-address-section');
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    
    console.log('Found address radios:', addressRadios.length);
    
    // Обработчик изменения адреса
    function handleAddressChange() {
        const selectedRadio = document.querySelector('input[name="address_option"]:checked');
        
        if (!selectedRadio) {
            console.log('No address selected');
            return;
        }
        
        console.log('Selected address value:', selectedRadio.value);
        
        if (selectedRadio.id === 'address_new') {
            // Новый адрес
            newAddressSection.classList.remove('d-none');
            addressInput.required = true;
            cityInput.required = true;
            
            // Очищаем поля
            addressInput.value = '';
            cityInput.value = '';
            
            // Скрытое поле для адреса должно быть пустым
            document.getElementById('final_address').value = '';
        } else {
            // Сохраненный адрес
            newAddressSection.classList.add('d-none');
            addressInput.required = false;
            cityInput.required = false;
            
            // Заполняем основное поле адреса выбранным значением
            addressInput.value = selectedRadio.value;
            
            // Также заполняем скрытое поле
            document.getElementById('final_address').value = selectedRadio.value;
        }
    }
    
    // Назначаем обработчики на все радиокнопки адреса
    addressRadios.forEach(radio => {
        radio.addEventListener('change', handleAddressChange);
    });
    
    // Если нет сохраненных адресов, выбираем "новый адрес" по умолчанию
    @if(empty($addresses))
        console.log('No saved addresses');
        const newAddressRadio = document.getElementById('address_new');
        if (newAddressRadio) {
            newAddressRadio.checked = true;
            handleAddressChange();
        }
    @else
        // При загрузке страницы вызываем обработчик для выбранного адреса
        console.log('Has saved addresses, triggering change handler');
        setTimeout(() => {
            handleAddressChange();
        }, 100);
    @endif
    
    // Обработка полей нового адреса
    const newAddressFields = ['city', 'address', 'apartment', 'postal_code'];
    newAddressFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                // Если выбран новый адрес, обновляем финальный адрес
                if (document.getElementById('address_new')?.checked) {
                    updateFinalAddress();
                }
            });
        }
    });
    
    // Функция обновления финального адреса из полей
    function updateFinalAddress() {
        const city = document.getElementById('city').value || '';
        const address = document.getElementById('address').value || '';
        const apartment = document.getElementById('apartment').value || '';
        const postalCode = document.getElementById('postal_code').value || '';
        
        let fullAddress = '';
        
        if (city && address) {
            fullAddress = city + ', ' + address;
            
            if (apartment) {
                fullAddress += ', кв. ' + apartment;
            }
            
            if (postalCode) {
                fullAddress += ' (' + postalCode + ')';
            }
        }
        
        console.log('Generated full address:', fullAddress);
        
        // Обновляем основное поле адреса
        const addressInput = document.getElementById('address');
        addressInput.value = fullAddress;
        
        // И скрытое поле
        document.getElementById('final_address').value = fullAddress;
    }
    
    // Расчет стоимости доставки
    const deliveryRadios = document.querySelectorAll('input[name="delivery_method"]');
    const deliveryCostElement = document.getElementById('delivery-cost');
    const totalCostElement = document.getElementById('total-cost');
    const subtotal = {{ $subtotal }};
    
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            let cost = 0;
            
            if (this.value === 'express') {
                cost = 650;
            } else if (subtotal < 5000) {
                cost = 350;
            }
            
            // Обновляем отображение
            if (cost === 0) {
                deliveryCostElement.textContent = 'Бесплатно';
            } else {
                deliveryCostElement.textContent = cost.toLocaleString('ru-RU') + ' ₽';
            }
            
            // Обновляем итоговую стоимость
            const total = subtotal + cost;
            totalCostElement.textContent = total.toLocaleString('ru-RU') + ' ₽';
        });
    });
    
    // Валидация формы перед отправкой
    const checkoutForm = document.getElementById('checkout-form');
    
    checkoutForm.addEventListener('submit', function(e) {
        console.log('Form submission started');
        
        // Проверяем выбран ли адрес
        const selectedAddress = document.querySelector('input[name="address_option"]:checked');
        if (!selectedAddress) {
            e.preventDefault();
            alert('Пожалуйста, выберите адрес доставки');
            return;
        }
        
        // Если выбран новый адрес, проверяем поля
        if (selectedAddress.id === 'address_new') {
            const city = document.getElementById('city').value.trim();
            const address = document.getElementById('address').value.trim();
            
            if (!city || !address) {
                e.preventDefault();
                alert('Пожалуйста, заполните город и улицу для нового адреса');
                return;
            }
            
            // Обновляем финальный адрес перед отправкой
            updateFinalAddress();
        }
        
        // Проверяем согласие с условиями
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox?.checked) {
            e.preventDefault();
            alert('Необходимо согласиться с условиями покупки');
            return;
        }
        
        console.log('Form validation passed');
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