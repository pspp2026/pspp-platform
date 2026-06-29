@props([
    'name',
    'label',
    'placeholder' => '',
    'rows' => 5,
    'required' => false,
])

<div class="bg-white border rounded-xl p-5 mb-6 shadow-sm">

    <!-- Label -->

    <label
        for="{{ $name }}"
        class="block text-lg font-semibold text-gray-800 mb-3">

        {{ $label }}

        @if($required)
            <span class="text-red-600">*</span>
        @endif

    </label>

    <!-- Textarea -->

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        class="w-full rounded-lg border border-gray-300 p-4
               focus:ring-2 focus:ring-blue-500
               focus:border-blue-500
               resize-y">{{ old($name) }}</textarea>

    <!-- Character Hint -->

    <div class="text-sm text-gray-500 mt-2">

        กรุณาระบุความคิดเห็นหรือข้อเสนอแนะเพิ่มเติม

    </div>

    <!-- Validation -->

    @error($name)

        <div class="mt-2 text-sm text-red-600">

            {{ $message }}

        </div>

    @enderror

</div>