@props([
    'name',
    'label',
    'options' => [],
    'required' => true,
])

<div class="bg-white border rounded-xl p-5 mb-6 shadow-sm">

    <!-- Question -->

    <label class="block text-lg font-semibold text-gray-800 mb-4">

        {{ $label }}

        @if($required)
            <span class="text-red-600">*</span>
        @endif

    </label>

    <!-- Options -->

    <div class="space-y-3">

        @foreach($options as $value => $text)

            <label
                class="flex items-center gap-3 border rounded-lg px-4 py-3 hover:bg-blue-50 cursor-pointer transition">

                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    @checked(old($name)==$value)
                    @required($required)
                    class="text-blue-600">

                <span class="text-gray-700">

                    {{ $text }}

                </span>

            </label>

        @endforeach

    </div>

    @error($name)

        <div class="text-red-600 text-sm mt-3">

            {{ $message }}

        </div>

    @enderror

</div>