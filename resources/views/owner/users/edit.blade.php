@extends('layouts.app')

@section('title', 'Edit User - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <a href="{{ route('owner.users.index') }}">
            <x-button variant="outline">← Back to List</x-button>
        </a>
    </div>

    <form method="POST" action="{{ route('owner.users.update', $user) }}">
        @csrf
        @method('PUT')

        <x-card title="User Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-form.input 
                        name="name" 
                        label="Full Name" 
                        required 
                        :value="$user->name" 
                    />
                </div>

                <x-form.input 
                    name="email" 
                    label="Email" 
                    type="email" 
                    required 
                    :value="$user->email" 
                />

                <x-form.select name="role" label="Role" required>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Customer</option>
                    <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Staff (Pegawai)</option>
                    <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
                </x-form.select>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Change Password</label>
                    <p class="text-sm text-gray-600 mb-3">Leave empty to keep current password</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form.input 
                            name="password" 
                            label="New Password" 
                            type="password" 
                            placeholder="Minimum 8 characters" 
                        />

                        <x-form.input 
                            name="password_confirmation" 
                            label="Confirm Password" 
                            type="password" 
                            placeholder="Re-enter password" 
                        />
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Contact Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    name="phone" 
                    label="Phone Number" 
                    :value="$user->phone" 
                    placeholder="+62812345678" 
                />

                <div class="md:col-span-2">
                    <x-form.textarea 
                        name="address" 
                        label="Address" 
                        rows="3" 
                        placeholder="Full address..." 
                    >{{ $user->address }}</x-form.textarea>
                </div>
            </div>
        </x-card>

        <div class="flex gap-3">
            <x-button type="submit" variant="primary" size="lg">Update User</x-button>
            <a href="{{ route('owner.users.index') }}">
                <x-button type="button" variant="outline" size="lg">Cancel</x-button>
            </a>
        </div>
    </form>
</div>
@endsection
