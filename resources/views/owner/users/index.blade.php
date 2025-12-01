@extends('layouts.app')

@section('title', 'User Management - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <a href="{{ route('owner.users.create') }}">
            <x-button variant="primary">+ Add New User</x-button>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b">
        <nav class="flex space-x-8">
            <a href="{{ route('owner.users.index') }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('role') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                All Users ({{ $users->total() }})
            </a>
            <a href="{{ route('owner.users.index', ['role' => 'user']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('role') === 'user' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Customers
            </a>
            <a href="{{ route('owner.users.index', ['role' => 'pegawai']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('role') === 'pegawai' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Staff
            </a>
            <a href="{{ route('owner.users.index', ['role' => 'owner']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('role') === 'owner' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Owners
            </a>
        </nav>
    </div>

    <!-- Search -->
    <x-card class="mb-6">
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="role" value="{{ request('role') }}">
            <input type="text" name="search" placeholder="Search by name or email..." 
                value="{{ request('search') }}"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            <x-button type="submit" variant="primary">Search</x-button>
            @if(request('search'))
                <a href="{{ route('owner.users.index', request()->only('role')) }}">
                    <x-button type="button" variant="outline">Reset</x-button>
                </a>
            @endif
        </form>
    </x-card>

    @if($users->count() > 0)
        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-indigo-600 font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-badge :type="$user->role === 'owner' ? 'error' : ($user->role === 'pegawai' ? 'warning' : 'info')">
                                        {{ strtoupper($user->role) }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <p>{{ $user->phone ?? '-' }}</p>
                                    <p class="text-gray-600">{{ Str::limit($user->address ?? '-', 30) }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('owner.users.edit', $user) }}">
                                            <x-button variant="primary" size="sm">Edit</x-button>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('owner.users.destroy', $user) }}" 
                                                class="inline" onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="danger" size="sm">Delete</x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No users found</p>
            </div>
        </x-card>
    @endif
</div>
@endsection
