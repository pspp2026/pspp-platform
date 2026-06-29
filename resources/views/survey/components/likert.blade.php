@props([
    'name',
    'label',
    'required' => true,
])

<div class="bg-white border rounded-xl p-5 mb-4 shadow-sm">

    <div class="font-medium text-gray-800 mb-4">
        {{ $label }}

        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </div>

    <div class="grid grid-cols-5 gap-4 text-center">

        @php
            $levels = [
                5 => 'มากที่สุด',
                4 => 'มาก',
                3 => 'ปานกลาง',
                2 => 'น้อย',
                1 => 'น้อยที่สุด',
            ];
        @endphp

        @foreach($levels as $value => $text)

            <label
                class="border rounded-lg p-3 cursor-pointer hover:bg-blue-50 transition">

                <div class="mb-2">

                    <input
                        type="radio"
                        name="{{ $name }}"
                        value="{{ $value }}"
                        @checked(old($name)==$value)
                        @required($required)
                        class="text-blue-600">

                </div>

                <div class="font-bold text-lg">

                    {{ $value }}

                </div>

                <div class="text-xs text-gray-600 mt-1">

                    {{ $text }}

                </div>

            </label>

        @endforeach

    </div>

    @error($name)

        <div class="text-red-600 text-sm mt-2">

            {{ $message }}

        </div>

    @enderror

</div>