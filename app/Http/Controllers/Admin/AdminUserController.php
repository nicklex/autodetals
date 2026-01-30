<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
public function index(Request $request)
{
    $query = User::withCount(['orders'])->latest();
    
    // Поиск
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }
    
    // Фильтр по роли (admin вместо is_admin)
    if ($request->has('role')) {
        if ($request->role == 'admin') {
            $query->where('admin', true);
        } elseif ($request->role == 'user') {
            $query->where('admin', false);
        }
    }
    
    // Фильтр по статусу
    if ($request->has('status')) {
        if ($request->status == 'active') {
            $query->where(function($q) {
                $q->whereNull('banned_at')
                  ->orWhere('banned_at', '>', now());
            });
        } elseif ($request->status == 'inactive') {
            $query->whereNotNull('banned_at')
                  ->where('banned_at', '<=', now());
        } elseif ($request->status == 'banned') {
            $query->whereNotNull('banned_at')
                  ->where('banned_at', '<=', now());
        }
    }
    
    // Получаем пользователей с пагинацией
    $users = $query->paginate(20);
    
    // Статистика
    $totalUsers = User::count();
    $activeUsers = User::where(function($q) {
        $q->whereNull('banned_at')
          ->orWhere('banned_at', '>', now());
    })->count();
    $adminUsers = User::where('admin', true)->count();
    $newUsers = User::where('created_at', '>=', now()->subMonth())->count();
    
    return view('admin.users.index', compact(
        'users',
        'totalUsers',
        'activeUsers',
        'adminUsers',
        'newUsers'
    ));
}
    
public function show(User $user)
{
    // Загружаем необходимые отношения
    $user->load(['orders' => function($q) {
        $q->latest()->take(5);
    }, 'reviews' => function($q) {
        $q->with('product')->latest()->take(3);
    }]);
    
    return view('admin.users.show', compact('user'));
}
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'admin' => 'boolean', // ← Изменено здесь
        'password' => 'nullable|string|min:8',
        'address' => 'nullable|string',
    ]);
    
    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'admin' => $request->has('admin'), // ← Изменено здесь
        'address' => $validated['address'] ?? null,
    ];
    
    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($validated['password']);
    }
    
    $user->update($updateData);
    
    return redirect()->route('admin.users.index')
        ->with('success', 'Пользователь обновлен');
}
    public function destroy(User $user)
    {
        // Нельзя удалить себя
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить свой собственный аккаунт');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь удален');
    }
    
    public function ban(User $user)
    {
        $user->update(['banned_at' => now()]);
        return back()->with('success', 'Пользователь заблокирован');
    }
    
    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);
        return back()->with('success', 'Пользователь разблокирован');
    }
}