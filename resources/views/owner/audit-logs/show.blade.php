@extends('layouts.app')

@section('title', 'Audit Log Details - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Audit Log Details</h1>
        <a href="{{ route('owner.audit-logs.index') }}">
            <x-button variant="outline">← Back to Logs</x-button>
        </a>
    </div>

    <!-- Log Information -->
    <x-card title="Log Information" class="mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-gray-600 text-sm">Action</label>
                <p class="font-semibold text-lg">{{ ucfirst($log->action) }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Timestamp</label>
                <p class="font-semibold">{{ $log->created_at->format('d F Y H:i:s') }}</p>
                <p class="text-sm text-gray-600">{{ $log->created_at->diffForHumans() }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">User</label>
                <p class="font-semibold">{{ $log->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $log->user->email }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Role</label>
                <p class="font-semibold">
                    <x-badge :type="$log->user->role === 'owner' ? 'error' : ($log->user->role === 'pegawai' ? 'warning' : 'info')">
                        {{ strtoupper($log->user->role) }}
                    </x-badge>
                </p>
            </div>
            @if($log->model_type)
                <div>
                    <label class="text-gray-600 text-sm">Model Type</label>
                    <p class="font-semibold">{{ class_basename($log->model_type) }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Model ID</label>
                    <p class="font-semibold">#{{ $log->model_id }}</p>
                </div>
            @endif
            @if($log->ip_address)
                <div>
                    <label class="text-gray-600 text-sm">IP Address</label>
                    <p class="font-semibold">{{ $log->ip_address }}</p>
                </div>
            @endif
            @if($log->user_agent)
                <div class="col-span-2">
                    <label class="text-gray-600 text-sm">User Agent</label>
                    <p class="font-semibold text-sm">{{ $log->user_agent }}</p>
                </div>
            @endif
        </div>
    </x-card>

    <!-- Description -->
    <x-card title="Description" class="mb-6">
        <p class="text-gray-700">{{ $log->description }}</p>
    </x-card>

    <!-- Changes (if available) -->
    @if($log->old_values || $log->new_values)
        <x-card title="Changes" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Old Values -->
                @if($log->old_values)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Old Values</h4>
                        <div class="bg-red-50 rounded p-4 border border-red-200">
                            <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                <!-- New Values -->
                @if($log->new_values)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">New Values</h4>
                        <div class="bg-green-50 rounded p-4 border border-green-200">
                            <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>
    @endif

    <!-- Metadata (if available) -->
    @if($log->metadata)
        <x-card title="Additional Metadata">
            <div class="bg-gray-50 rounded p-4">
                <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </x-card>
    @endif
</div>
@endsection
