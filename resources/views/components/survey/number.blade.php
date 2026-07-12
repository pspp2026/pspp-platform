@props([
    'name',
    'label',
    'placeholder' => '',
    'min' => null,
    'max' => null,
    'step' => 1,
    'required' => false,
])

<div class="bg-white border rounded-xl shadow-sm p-5 mb-6">

    <!-- Label -->

    <label
        for="{{ $name }}"
        class="block text-lg font-semibold text-gray-800 mb-3">

        {{ $label }}

        @if($required)
            <span class="text-red-600">*</span>
        @endif

    </label>

    <!-- Number Input -->

    <input
        type="number"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name) }}"
        placeholder="{{ $placeholder }}"
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        @required($required)

        class="w-full md:w-64 rounded-lg border border-gray-300
               px-4 py-3
               focus:outline-none
               focus:ring-2
               focus:ring-blue-500
               focus:border-blue-500">

    <!-- Hint -->

    @if($min || $max)

        <div class="text-sm text-gray-500 mt-2">

            @if($min)
                ค่าต่ำสุด {{ $min }}
            @endif

            @if($min && $max)
                |
            @endif

            @if($max)
                ค่าสูงสุด {{ $max }}
            @endif

        </div>

    @endif

    <!-- Validation -->

    @error($name)

        <div class="mt-2 text-sm text-red-600">

            {{ $message }}

        </div>

    @enderror

</div>