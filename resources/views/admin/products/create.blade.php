@extends('layouts.admin')

@section('title', 'Добавить товар')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Добавить новый товар</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Основная информация -->
                        <div class="mb-4">
                            <h5 class="mb-3">Основная информация</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Название товара *</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Артикул (код) *</label>
                                    <input type="text" name="code" id="code" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           value="{{ old('code') }}" required>
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
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                          rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Цены и наличие -->
                        <div class="mb-4">
                            <h5 class="mb-3">Цены и наличие</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Цена *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="price" id="price" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price') }}" required>
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
                                               value="{{ old('old_price') }}">
                                        <span class="input-group-text">₽</span>
                                        @error('old_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Количество на складе *</label>
                                    <input type="number" name="quantity" id="quantity" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           value="{{ old('quantity', 0) }}" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Статус</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="is_active" id="is_active" 
                                               class="form-check-input" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">
                                            Активный (отображается на сайте)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Изображения -->
                        <div class="mb-4">
                            <h5 class="mb-3">Изображения</h5>
                            
                            <div class="mb-3">
                                <label for="images" class="form-label">Загрузить изображения</label>
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
                                       value="{{ old('brand') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="weight" class="form-label">Вес (г)</label>
                                <input type="number" name="weight" id="weight" 
                                       class="form-control" 
                                       value="{{ old('weight') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="dimensions" class="form-label">Размеры (мм)</label>
                                <input type="text" name="dimensions" id="dimensions" 
                                       class="form-control" 
                                       placeholder="100x50x30" 
                                       value="{{ old('dimensions') }}">
                            </div>
                        </div>
                        
                        <!-- SEO -->
                        <div class="mb-4">
                            <h5 class="mb-3">SEO</h5>
                            
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" 
                                       class="form-control" 
                                       value="{{ old('meta_title') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" 
                                          class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="reset" class="btn btn-secondary">Сбросить</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить товар
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр изображений
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
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        `;
                        previewContainer.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    }
    
    // Автогенерация артикула из названия
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    if (nameInput && codeInput) {
        nameInput.addEventListener('blur', function() {
            if (!codeInput.value) {
                const code = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9\-]/g, '')
                    .replace(/\-+/g, '-')
                    .replace(/^\-+|\-+$/g, '');
                codeInput.value = code;
            }
        });
    }
});
</script>
@endpush
@endsection