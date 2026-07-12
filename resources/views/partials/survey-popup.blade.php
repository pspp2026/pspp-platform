@if(isset($showSurveyPopup) && $showSurveyPopup)

<div
    id="surveyModal"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60">

    <div
        class="w-full max-w-xl mx-4 bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 text-white px-8 py-6">

            <div class="flex items-center gap-4">

                <div class="text-5xl">
                    📋
                </div>

                <div>

                    <h2 class="text-2xl font-bold">
                        ขอความร่วมมือตอบแบบประเมิน
                    </h2>

                    <p class="text-blue-100 mt-1">
                        PSPP Platform
                    </p>

                </div>

            </div>

        </div>

        {{-- Body --}}
        <div class="px-8 py-7">

            <p class="text-lg text-gray-800">

                เรียน ผู้ใช้งานระบบ

            </p>

            <p class="mt-4 leading-8 text-gray-700">

                ขอความร่วมมือในการตอบแบบประเมิน

                <strong>

                    "รูปแบบการใช้เทคโนโลยีเพื่อการศึกษา
                    สำหรับโรงเรียนพระปริยัติธรรม
                    แผนกสามัญศึกษา
                    กลุ่มจังหวัดแพร่"

                </strong>

                <br><br>

                ใช้เวลาประมาณ

                <span class="font-bold text-blue-700">
                    3 - 5 นาที
                </span>

                เท่านั้น

            </p>

            <div class="mt-6 rounded-xl bg-yellow-50 border border-yellow-200 p-4">

                <div class="font-semibold text-yellow-800">

                    📌 ข้อมูลของท่านจะถูกเก็บเป็นความลับ

                </div>

                <div class="text-sm text-yellow-700 mt-2">

                    ใช้เพื่อการวิจัยและการพัฒนาระบบเท่านั้น

                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-100 px-8 py-5 flex justify-end gap-3">

            <button
                type="button"
                id="btnLater"
                class="px-5 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-200">

                ภายหลัง

            </button>

            <button
                type="button"
                id="btnSurvey"
                class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                ตอบแบบประเมิน

            </button>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('surveyModal');

    if (!modal) return;

    // ปุ่ม "ภายหลัง"
    document.getElementById('btnLater').addEventListener('click', function () {

        modal.remove();

    });

    // ปุ่ม "ตอบแบบประเมิน"
    document.getElementById('btnSurvey').addEventListener('click', function () {

        modal.remove();

        window.location.href = "{{ route('survey.pspp.evaluation') }}";

    });

});

</script>

@endif