@extends('layouts.app')

@section('title', 'Add New User - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
        <a href="{{ route('owner.users.index') }}">
            <x-button variant="outline">← Back to List</x-button>
        </a>
    </div>

    <form method="POST" action="{{ route('owner.users.store') }}">
        @csrf

        <x-card title="User Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-form.input 
                        name="name" 
                        label="Full Name" 
                        required 
                        placeholder="John Doe" 
                    />
                </div>

                <x-form.input 
                    name="email" 
                    label="Email" 
                    type="email" 
                    required 
                    placeholder="john@example.com" 
                />

                <x-form.select name="role" label="Role" required>
                    <option value="">Select Role</option>
                    <option value="user">Customer</option>
                    <option value="pegawai">Staff (Pegawai)</option>
                    <option value="owner">Owner</option>
                </x-form.select>

                <x-form.input 
                    name="password" 
                    label="Password" 
                    type="password" 
                    required 
                    placeholder="Minimum 8 characters" 
                />

                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm Password" 
                    type="password" 
                    required 
                    placeholder="Re-enter password" 
                />
            </div>
        </x-card>

        <x-card title="Contact Information (Optional)" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    name="phone" 
                    label="Phone Number" 
                    placeholder="+62812345678" 
                />

                <div class="md:col-span-2">
                    <x-form.textarea 
                        name="address" 
                        label="Address" 
                        rows="3" 
                        placeholder="Full address..." 
                    />
                </div>
            </div>
        </x-card>

        <div class="flex gap-3">
            <x-button type="submit" variant="primary" size="lg">Create User</x-button>
            <a href="{{ route('owner.users.index') }}">
                <x-button type="button" variant="outline" size="lg">Cancel</x-button>
            </a>
        </div>
    </form>
</div>
@endsection
