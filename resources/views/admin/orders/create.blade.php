@extends('layouts.admin')

@section('title', 'Создать заказ')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Создать новый заказ</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">Пользователь (опционально)</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Без пользователя</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Статус *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="новый">Новый</option>
                            <option value="в_обработке">В обработке</option>
                            <option value="отправлен">Отправлен</option>
                            <option value="доставлен">Доставлен</option>
                            <option value="отменен">Отменен</option>
                        </select>
                    </div>
                </div>
                
                <h5 class="mb-3">Информация о клиенте</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Имя *</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Телефон *</label>
                        <input type="tel" name="phone" id="phone" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="total_price" class="form-label">Сумма заказа *</label>
                        <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Адрес доставки *</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Примечания</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-secondary">Сбросить</button>
                    <button type="submit" class="btn btn-primary">Создать заказ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection