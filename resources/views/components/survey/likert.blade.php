@props([
    'name',
    'label',
    'required' => true,
])

@php
    $levels = [
        5 => 'มากที่สุด',
        4 => 'มาก',
        3 => 'ปานกลาง',
        2 => 'น้อย',
        1 => 'น้อยที่สุด',
    ];
@endphp

<div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">

    <div class="font-medium text-gray-800 mb-4">
        {{ $label }}

        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">

        @foreach($levels as $value => $text)

            <label
                class="flex items-center justify-center gap-2 border rounded-md px-3 py-2 cursor-pointer hover:bg-blue-50 transition">

                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    @checked(old($name)==$value)
                    @required($required)
                    class="w-4 h-4 text-blue-600">

                <span class="text-sm text-gray-700 whitespace-nowrap">

                    {{ $text }}

                </span>

            </label>

        @endforeach

    </div>

    @error($name)

        <div class="text-red-600 text-sm mt-2">

            {{ $message }}

        </div>

    @enderror

</div>