@extends('layouts.admin')

@section('title', 'Добавить категорию')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Добавить новую категорию</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название категории *</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">URL код (slug) *</label>
                            <input type="text" name="code" id="code" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code') }}" required>
                            <small class="text-muted">Используется в URL. Только латинские буквы, цифры и дефисы.</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Родительская категория</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">-- Без родительской категории --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
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
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение категории</label>
                            <input type="file" name="image" id="image" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Рекомендуемый размер: 400x400px</small>
                            
                            <div id="imagePreview" class="mt-3">
                                <img id="previewImage" class="img-thumbnail" 
                                     style="display: none; max-width: 200px;">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Статус</label>
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="form-check-input" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">
                                    Активная категория
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Настройки отображения</label>
                            <div class="form-check">
                                <input type="checkbox" name="show_in_menu" id="show_in_menu" 
                                       class="form-check-input" value="1" 
                                       {{ old('show_in_menu', true) ? 'checked' : '' }}>
                                <label for="show_in_menu" class="form-check-label">
                                    Показывать в меню
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="show_in_home" id="show_in_home" 
                                       class="form-check-input" value="1" 
                                       {{ old('show_in_home') ? 'checked' : '' }}>
                                <label for="show_in_home" class="form-check-label">
                                    Показывать на главной
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Порядок сортировки</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="form-control" 
                                   value="{{ old('sort_order', 0) }}">
                            <small class="text-muted">Чем меньше число, тем выше в списке</small>
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
                                       value="{{ old('meta_title') }}">
                                <small class="text-muted">Если не заполнено, будет использовано название категории</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" 
                                          class="form-control" rows="2">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="reset" class="btn btn-secondary">Сбросить</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить категорию
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр изображения
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('previewImage');
    
    if (imageInput && previewImage) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Автогенерация slug из названия
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    if (nameInput && codeInput) {
        nameInput.addEventListener('blur', function() {
            if (!codeInput.value) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9\-]/g, '')
                    .replace(/\-+/g, '-')
                    .replace(/^\-+|\-+$/g, '');
                codeInput.value = slug;
            }
        });
    }
});
</script>
@endpush
@endsection