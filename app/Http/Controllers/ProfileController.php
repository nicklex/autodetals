<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Текущий пароль неверен'])
                    ->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }
        
        $user->save();
        
        return redirect()->route('profile.index')
            ->with('success', 'Профиль успешно обновлен');
    }
    
    public function addresses()
    {
        $user = Auth::user();
        
        // Исправление: правильно обрабатываем JSON/массив адресов
        $addresses = [];
        
        if (!empty($user->addresses)) {
            if (is_string($user->addresses)) {
                // Если это JSON строка
                $decoded = json_decode($user->addresses, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $addresses = $decoded;
                }
            } elseif (is_array($user->addresses)) {
                // Если уже массив
                $addresses = $user->addresses;
            }
        }
        
        return view('profile.addresses', compact('addresses'));
    }
    
    public function addAddress(Request $request)
    {
        $user = Auth::user();
        
        // Получаем текущие адреса
        $addresses = [];
        if (!empty($user->addresses)) {
            if (is_string($user->addresses)) {
                $decoded = json_decode($user->addresses, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $addresses = $decoded;
                }
            } elseif (is_array($user->addresses)) {
                $addresses = $user->addresses;
            }
        }
        
        if (count($addresses) >= 3) {
            return redirect()->back()
                ->with('error', 'Можно добавить не более 3 адресов');
        }
        
        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'apartment' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $fullAddress = $request->city . ', ' . $request->address;
        if (!empty($request->apartment)) {
            $fullAddress .= ', кв. ' . $request->apartment;
        }
        if (!empty($request->postal_code)) {
            $fullAddress .= ' (' . $request->postal_code . ')';
        }
        
        $addresses[] = $fullAddress;
        
        // Сохраняем как JSON строку
        $user->addresses = json_encode($addresses);
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Адрес успешно добавлен');
    }
    
    public function updateAddress(Request $request, $index)
    {
        $user = Auth::user();
        
        // Получаем текущие адреса
        $addresses = [];
        if (!empty($user->addresses)) {
            if (is_string($user->addresses)) {
                $decoded = json_decode($user->addresses, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $addresses = $decoded;
                }
            } elseif (is_array($user->addresses)) {
                $addresses = $user->addresses;
            }
        }
        
        if (!isset($addresses[$index])) {
            return redirect()->back()
                ->with('error', 'Адрес не найден');
        }
        
        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'apartment' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $fullAddress = $request->city . ', ' . $request->address;
        if (!empty($request->apartment)) {
            $fullAddress .= ', кв. ' . $request->apartment;
        }
        if (!empty($request->postal_code)) {
            $fullAddress .= ' (' . $request->postal_code . ')';
        }
        
        $addresses[$index] = $fullAddress;
        
        // Сохраняем как JSON строку
        $user->addresses = json_encode($addresses);
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Адрес успешно обновлен');
    }
    
    public function deleteAddress($index)
    {
        $user = Auth::user();
        
        // Получаем текущие адреса
        $addresses = [];
        if (!empty($user->addresses)) {
            if (is_string($user->addresses)) {
                $decoded = json_decode($user->addresses, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $addresses = $decoded;
                }
            } elseif (is_array($user->addresses)) {
                $addresses = $user->addresses;
            }
        }
        
        if (!isset($addresses[$index])) {
            return redirect()->back()
                ->with('error', 'Адрес не найден');
        }
        
        // Удаляем адрес
        array_splice($addresses, $index, 1);
        
        // Сохраняем как JSON строку
        $user->addresses = json_encode($addresses);
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Адрес успешно удален');
    }
    
    public function favorites()
    {
        return view('profile.favorites');
    }
    
    public function addToFavorites($productId)
    {
        // Реализация добавления в избранное
        return redirect()->back()->with('success', 'Товар добавлен в избранное');
    }
    
    public function removeFromFavorites($productId)
    {
        // Реализация удаления из избранного
        return redirect()->back()->with('success', 'Товар удален из избранного');
    }
    
    public function toggleFavorite(Request $request)
    {
        // AJAX метод для переключения избранного
        return response()->json(['success' => true]);
    }
}