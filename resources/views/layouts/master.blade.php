<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - AutoDetails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            color: #c46f00 !important;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #c46f00;
            border-color: #c46f00;
        }
        .btn-primary:hover {
            background-color: #a55c00;
            border-color: #a55c00;
        }
        .btn-outline-primary {
            color: #c46f00;
            border-color: #c46f00;
        }
        .btn-outline-primary:hover {
            background-color: #c46f00;
            border-color: #c46f00;
        }
        .text-primary {
            color: #c46f00 !important;
        }
        .bg-primary {
            background-color: #c46f00 !important;
        }
        .border-primary {
            border-color: #c46f00 !important;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        footer {
            background-color: #f8f9fa;
            margin-top: auto;
        }
        .form-control:focus {
            border-color: #c46f00;
            box-shadow: 0 0 0 0.25rem rgba(196, 111, 0, 0.25);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header будет вставляться на каждой странице отдельно -->
    @yield('header')
    
    <main class="py-4">
        @yield('content')
    </main>
    
    <!-- Footer будет вставляться на каждой странице отдельно -->
    @yield('footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обновление счетчика корзины
    function updateCartCount() {
        fetch('{{ route("api.cart.count") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count;
            });
    }
    
    // Обработка добавления в корзину через AJAX
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const form = this.closest('form');
            
            fetch('{{ route("api.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем счетчик
                    document.getElementById('cart-count').textContent = data.count;
                    
                    // Показываем уведомление
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                // Если AJAX не сработал, отправляем форму обычным способом
                if (form) {
                    form.submit();
                }
            });
        });
    });
    
    // Функция для показа уведомлений
    function showNotification(message, type = 'success') {
        // Создаем уведомление
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Автоматически скрываем через 3 секунды
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }
});
</script>
</body>
</html>