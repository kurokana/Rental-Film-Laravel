@extends('layouts.app')

@section('title', 'Register - Rental Film')

@section('content')
<div class="max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Register</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium mb-2">No. Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-medium mb-2">Alamat</label>
                <textarea name="address" id="address" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">{{ old('address') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Register
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
