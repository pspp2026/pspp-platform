@props([
    'previous' => null,
    'next' => null,
    'submit' => false,
    'submitText' => 'ส่งแบบสอบถาม',
])

<div class="flex justify-between items-center mt-10 pt-6 border-t">

    <!-- ปุ่มย้อนกลับ -->

    <div>

        @if($previous)

            <a href="{{ $previous }}"
               class="inline-flex items-center px-6 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">

                ← ย้อนกลับ

            </a>

        @endif

    </div>

    <!-- ปุ่มด้านขวา -->

    <div>

        @if($submit)

            <button
                type="submit"
                class="inline-flex items-center px-8 py-3 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">

                ✅ {{ $submitText }}

            </button>

        @elseif($next)

            <a href="{{ $next }}"
               class="inline-flex items-center px-8 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">

                ถัดไป →

            </a>

        @endif

    </div>

</div>