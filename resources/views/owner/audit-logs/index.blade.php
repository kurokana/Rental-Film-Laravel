@extends('layouts.app')

@section('title', 'Audit Logs - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Audit Logs</h1>

    <!-- Filters -->
    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-form.input 
                name="search" 
                label="Search" 
                placeholder="User or action..." 
                :value="request('search')" 
            />

            <x-form.select name="action" label="Action Type">
                <option value="">All Actions</option>
                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            </x-form.select>

            <x-form.input 
                name="date" 
                label="Date" 
                type="date" 
                :value="request('date')" 
            />

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">Filter</x-button>
                <a href="{{ route('owner.audit-logs.index') }}">
                    <x-button type="button" variant="outline">Reset</x-button>
                </a>
            </div>
        </form>
    </x-card>

    @if($logs->count() > 0)
        <div class="space-y-3">
            @foreach($logs as $log)
                <x-card>
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $log->action === 'created' ? 'bg-green-100' : '' }}
                            {{ $log->action === 'updated' ? 'bg-blue-100' : '' }}
                            {{ $log->action === 'deleted' ? 'bg-red-100' : '' }}
                            {{ in_array($log->action, ['login', 'logout']) ? 'bg-gray-100' : '' }}">
                            @if($log->action === 'created')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            @elseif($log->action === 'updated')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            @elseif($log->action === 'deleted')
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Log Details -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $log->action }}</h3>
                                    <p class="text-gray-600">{{ $log->description }}</p>
                                </div>
                                <span class="text-sm text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</span>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $log->user->name }}</span>
                                </div>

                                @if($log->model_type)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</span>
                                    </div>
                                @endif

                                @if($log->ip_address)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        <span>{{ $log->ip_address }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($log->old_values || $log->new_values)
                                <div class="mt-3">
                                    <a href="{{ route('owner.audit-logs.show', $log) }}" class="text-indigo-600 hover:underline text-sm">
                                        View detailed changes →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No audit logs found</p>
            </div>
        </x-card>
    @endif
</div>
@endsection
