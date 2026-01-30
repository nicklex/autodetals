@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Редактировать товар</h1>
        <div>
         
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Основная информация -->
                        <div class="mb-4">
                            <h5 class="mb-3">Основная информация</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Название товара *</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Артикул (код) *</label>
                                    <input type="text" name="code" id="code" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           value="{{ old('code', $product->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Категория *</label>
                                    <select name="category_id" id="category_id" 
                                            class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Выберите категорию --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea name="description" id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Цены -->
                        <div class="mb-4">
                            <h5 class="mb-3">Цены</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Цена *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="price" id="price" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price', $product->price) }}" required>
                                        <span class="input-group-text">₽</span>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="old_price" class="form-label">Старая цена</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="old_price" id="old_price" 
                                               class="form-control @error('old_price') is-invalid @enderror" 
                                               value="{{ old('old_price', $product->old_price) }}">
                                        <span class="input-group-text">₽</span>
                                        @error('old_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                       
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Изображения -->
                        <div class="mb-4">
                            <h5 class="mb-3">Изображения</h5>
                            
                            <!-- Существующие изображения -->
                            @if($product->images && count(json_decode($product->images)) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Текущие изображения:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(json_decode($product->images) as $image)
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($image) }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                                        onclick="removeImage(this, '{{ $image }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Загрузка новых изображений -->
                            <div class="mb-3">
                                <label for="images" class="form-label">Добавить изображения</label>
                                <input type="file" name="images[]" id="images" 
                                       class="form-control @error('images') is-invalid @enderror" 
                                       accept="image/*" multiple>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Можно выбрать несколько файлов. Максимальный размер: 2MB</small>
                            </div>
                            
                            <div id="image-preview" class="mt-3"></div>
                        </div>
                        
                        <!-- Характеристики -->
                        <div class="mb-4">
                            <h5 class="mb-3">Характеристики</h5>
                            
                            <div class="mb-3">
                                <label for="brand" class="form-label">Бренд</label>
                                <input type="text" name="brand" id="brand" 
                                       class="form-control" 
                                       value="{{ old('brand', $product->brand) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="weight" class="form-label">Вес (г)</label>
                                <input type="number" name="weight" id="weight" 
                                       class="form-control" 
                                       value="{{ old('weight', $product->weight) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="dimensions" class="form-label">Размеры (мм)</label>
                                <input type="text" name="dimensions" id="dimensions" 
                                       class="form-control" 
                                       placeholder="100x50x30" 
                                       value="{{ old('dimensions', $product->dimensions) }}">
                            </div>
                        </div>
                        
                        <!-- Запас на складе -->
                        <div class="mb-4">
                            <h5 class="mb-3">Склад</h5>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-warehouse"></i>
                                Текущий запас: <strong>{{ $product->stock->quantity ?? 0 }}</strong> шт.
                                <br>
                                <small>Для изменения запаса перейдите в <a href="{{ route('admin.stocks.edit', $product->id) }}">управление складом</a></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- SEO -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">SEO настройки</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" 
                                       class="form-control" 
                                       value="{{ old('meta_title', $product->meta_title) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" 
                                          class="form-control" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр новых изображений
    const imagesInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    
    if (imagesInput && previewContainer) {
        imagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            
            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                            <img src="${e.target.result}" 
                                 class="img-thumbnail" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        `;
                        previewContainer.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    }
});

function removeImage(button, imagePath) {
    if (confirm('Удалить это изображение?')) {
        // Отправляем AJAX запрос для удаления изображения
        fetch('{{ route("admin.products.update", $product->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify({
                remove_image: imagePath
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.closest('.position-relative').remove();
            }
        });
    }
}
</script>
@endpush
@endsection