@extends('layouts.admin')

@section('title', 'Управление отзывами')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Управление отзывами</h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Фильтры
            </button>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Все отзывы</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReviews }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Одобрено</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedReviews }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                На модерации</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingReviews }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Отклонено</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rejectedReviews }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобрено</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">Все рейтинги</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ (5)</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4)</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3)</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2)</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Поиск по товару или автору..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Применить
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица отзывов -->
    <div class="card shadow">
        <div class="card-body">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Автор</th>
                                <th>Рейтинг</th>
                                <th>Отзыв</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($review->product->images && count(json_decode($review->product->images)) > 0)
                                            <img src="{{ Storage::url(json_decode($review->product->images)[0]) }}" 
                                                 alt="{{ $review->product->name }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                                 class="rounded me-2">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ Str::limit($review->product->name, 20) }}</div>
                                            <a href="{{ route('admin.products.edit', $review->product_id) }}" 
                                               class="text-decoration-none small">
                                                <i class="fas fa-edit"></i> к товару
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($review->user)
                                        <div>{{ $review->user->name }}</div>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    @else
                                        <span class="text-muted">Гость</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">({{ $review->rating }}/5)</small>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="mb-1">{{ Str::limit($review->comment, 100) }}</div>
                                    @if(strlen($review->comment) > 100)
                                        <button type="button" class="btn btn-sm btn-link p-0" 
                                                data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">
                                            Читать полностью
                                        </button>
                                        
                                        <!-- Модальное окно полного текста -->
                                        <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Отзыв от {{ $review->user->name ?? 'Гость' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $review->comment }}</p>
                                                        <hr>
                                                        <small class="text-muted">
                                                            Оставлен: {{ $review->created_at->format('d.m.Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $review->created_at->format('d.m.Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Одобрено</span>
                                    @elseif($review->is_approved === false)
                                        <span class="badge bg-danger">Отклонено</span>
                                    @else
                                        <span class="badge bg-warning">На модерации</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success" title="Одобрить">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($review->is_approved !== false)
                                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-warning" title="Отклонить">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    title="Удалить" 
                                                    onclick="return confirm('Удалить отзыв?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Пагинация -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Показано {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }} из {{ $reviews->total() }}
                    </div>
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Отзывы не найдены</h5>
                    <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Массовые действия -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Массовые действия</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionsForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="bulkAction" class="form-label">Действие</label>
                        <select name="action" id="bulkAction" class="form-select" required>
                            <option value="">-- Выберите действие --</option>
                            <option value="approve">Одобрить выбранные</option>
                            <option value="reject">Отклонить выбранные</option>
                            <option value="delete">Удалить выбранные</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Действие будет применено ко всем отмеченным отзывам.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" form="bulkActionsForm" class="btn btn-primary">Применить</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Выделение всех чекбоксов
    const selectAll = document.getElementById('selectAll');
    const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            reviewCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Массовые действия
    const bulkActionsForm = document.getElementById('bulkActionsForm');
    if (bulkActionsForm) {
        bulkActionsForm.addEventListener('submit', function(e) {
            const selectedReviews = Array.from(reviewCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedReviews.length === 0) {
                e.preventDefault();
                alert('Выберите хотя бы один отзыв');
                return false;
            }
            
            // Добавляем выбранные ID в форму
            selectedReviews.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'review_ids[]';
                input.value = id;
                this.appendChild(input);
            });
        });
    }
});
</script>
@endpush
@endsection