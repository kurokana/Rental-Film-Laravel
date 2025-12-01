@props([
    'label',
    'name',
    'options' => [],
    'selected' => '',
    'required' => false,
    'placeholder' => 'Select an option',
    'error' => null
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-medium mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500' . ($error ? ' border-red-500' : ' border-gray-300')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
