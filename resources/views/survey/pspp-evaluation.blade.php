@extends('layouts.app')

@section('title', 'แบบประเมินประสิทธิภาพของรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา')

@section('content')

<div class="max-w-6xl mx-auto py-8 px-4">

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('survey.pspp.submit') }}" method="POST">

        @csrf

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="bg-blue-700 text-white p-8">

                <h1 class="text-3xl font-bold text-center">

                    แบบประเมินประสิทธิภาพของรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา

                </h1>

                <p class="text-center mt-3 text-blue-100 text-lg">

                    สำหรับโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา กลุ่มจังหวัดแพร่

                </p>

            </div>

            <div class="p-8">

                <div class="grid md:grid-cols-2 gap-6 text-sm">

                    <div>
                        <span class="font-semibold">ชื่อเรื่องภาษาไทย :</span><br>
                        รูปแบบการใช้เทคโนโลยีเพื่อการศึกษาสำหรับโรงเรียนพระปริยัติธรรม
                        แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
                    </div>

                    <div>
                        <span class="font-semibold">ชื่อเรื่องภาษาอังกฤษ :</span><br>
                        A Model of Using Educational Technology for
                        Phrapariyattidham Secondary School in Phrae Province
                    </div>

                    <div>
                        <span class="font-semibold">ผู้วิจัย :</span><br>
                        พระมหาพิพัฒน์ อภิวฑฺฒโน (อายะนันท์)
                    </div>

                    <div>
                        <span class="font-semibold">สาขาวิชา :</span><br>
                        พระพุทธศาสนา
                    </div>

                    <div class="md:col-span-2">
                        <span class="font-semibold">อาจารย์ที่ปรึกษา :</span><br>
                        พระใบฎีกาศักดิธัช สํวโร, ดร.
                        และ
                        พระครูสุนทรธรรมนิทัศน์, ผศ., ดร.
                    </div>

                </div>

                <hr class="my-8">

                <h2 class="text-2xl font-bold mb-4">

                    วัตถุประสงค์ของแบบสอบถาม

                </h2>

                <p class="leading-8">

                    เพื่อประเมินประสิทธิภาพของรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา
                    สำหรับโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา
                    กลุ่มจังหวัดแพร่ โดยมีผู้บริหารโรงเรียนเป็นผู้ให้ข้อมูล

                </p>

                <hr class="my-8">

                <h2 class="text-2xl font-bold mb-4">

                    คำชี้แจง

                </h2>

                <ol class="list-decimal pl-6 space-y-3 leading-8">

                    <li>
                        แบบสอบถามนี้เป็นการสำรวจความคิดเห็นเกี่ยวกับรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา
                        สำหรับโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
                    </li>

                    <li>
                        แบบสอบถามนี้จัดทำขึ้นเพื่อประกอบการทำดุษฎีนิพนธ์
                        เรื่อง "รูปแบบการใช้เทคโนโลยีเพื่อการศึกษาสำหรับโรงเรียนพระปริยัติธรรม
                        แผนกสามัญศึกษา กลุ่มจังหวัดแพร่"
                    </li>

                    <li>
                        ข้อมูลที่ได้จากการตอบแบบสอบถามจะถูกเก็บไว้เป็นความลับ
                        และใช้เพื่อการวิจัยเท่านั้น
                    </li>

                    <li>
                        โปรดเลือกคำตอบที่ตรงกับความคิดเห็นของท่านมากที่สุด
                        ในทุกข้อ
                    </li>

                </ol>

                <div class="mt-10">

                    <x-survey.section
                        step="1"
                        title="ตอนที่ 1"
                        description="แบบประเมินประสิทธิภาพของรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา" />

                </div>

                <div class="mt-8 rounded-xl border bg-blue-50 p-5">

                    <h3 class="text-xl font-bold text-blue-800">

                        1. ด้านความสามารถในการทำงานของระบบ
                        (Functional Requirement Test)

                    </h3>

                </div>

                <x-survey.likert
                    name="answer[1]"
                    label="1.1 การเชื่อมโยงกับผู้ใช้ระบบ" />

                <x-survey.likert
                    name="answer[2]"
                    label="1.2 การจัดประเภทข้อมูลของระบบ" />

                <x-survey.likert
                    name="answer[3]"
                    label="1.3 การเพิ่มข้อมูล" />

                <x-survey.likert
                    name="answer[4]"
                    label="1.4 การปรับปรุงข้อมูล" />

                <x-survey.likert
                    name="answer[5]"
                    label="1.5 การนำเสนอข้อมูล" />

                <x-survey.likert
                    name="answer[6]"
                    label="1.6 การดึงดูดความสนใจ" />

                <div class="mt-10 rounded-xl border bg-blue-50 p-5">

                    <h3 class="text-xl font-bold text-blue-800">

                        2. ด้านความถูกต้องในการทำงานของระบบ
                        (Functional Test)

                    </h3>

                </div>

                <x-survey.likert
                    name="answer[7]"
                    label="2.1 ความถูกต้องในการจัดประเภทข้อมูล" />

                <x-survey.likert
                    name="answer[8]"
                    label="2.2 ความถูกต้องในการเพิ่มข้อมูล" />

                <x-survey.likert
                    name="answer[9]"
                    label="2.3 ความถูกต้องในการปรับปรุงข้อมูล" />

                <x-survey.likert
                    name="answer[10]"
                    label="2.4 ความถูกต้องในการนำเสนอข้อมูล" />

                <x-survey.likert
                    name="answer[11]"
                    label="2.5 ความถูกต้องในการแสดงข้อมูล" />

                <x-survey.likert
                    name="answer[12]"
                    label="2.6 ความถูกต้องในการป้อนข้อมูล" />



                <div class="mt-10 rounded-xl border bg-blue-50 p-5">

                    <h3 class="text-xl font-bold text-blue-800">

                        3. ด้านความสะดวกและง่ายต่อการใช้งาน
                        (Performance Test)

                    </h3>

                </div>

                <x-survey.likert
                    name="answer[13]"
                    label="3.1 ความง่ายในการใช้ระบบ" />

                <x-survey.likert
                    name="answer[14]"
                    label="3.2 ความเหมาะสมของกราฟิกที่นำเสนอ" />

                <x-survey.likert
                    name="answer[15]"
                    label="3.3 ความเหมาะสมในการออกแบบ" />

                <x-survey.likert
                    name="answer[16]"
                    label="3.4 ความชัดเจนของข้อความที่แสดงบนจอ" />

                <x-survey.likert
                    name="answer[17]"
                    label="3.5 ความเหมาะสมของสีโดยภาพรวม" />

                <x-survey.likert
                    name="answer[18]"
                    label="3.6 ความเหมาะสมของตัวอักษร" />

                <x-survey.likert
                    name="answer[19]"
                    label="3.7 ความเหมาะสมของภาพนิ่งที่นำเสนอ" />

                <x-survey.likert
                    name="answer[20]"
                    label="3.8 ความรวดเร็วในการทำงานของระบบ" />

                <div class="mt-10 rounded-xl border bg-blue-50 p-5">

                    <h3 class="text-xl font-bold text-blue-800">

                        4. ด้านการรักษาความปลอดภัย
                        (Security Test)

                    </h3>

                </div>

                <x-survey.likert
                    name="answer[21]"
                    label="4.1 การกำหนดรหัสผ่านมีความปลอดภัย" />

                <x-survey.likert
                    name="answer[22]"
                    label="4.2 การกู้รหัสผ่านมีความปลอดภัย" />

                <x-survey.likert
                    name="answer[23]"
                    label="4.3 มีระบบการป้องกันไวรัส" />


                <div class="mt-12">

                    <x-survey.section
                        step="3"
                        title="ตอนที่ 2"
                        description="ความคิดเห็นและข้อเสนอแนะเพิ่มเติม" />

                </div>

                <x-survey.textarea
                    name="suggestion"
                    label="ความคิดเห็นและข้อเสนอแนะอื่น ๆ"
                    placeholder="กรุณาระบุความคิดเห็นหรือข้อเสนอแนะเพิ่มเติม (ถ้ามี)"
                    rows="6" />

                <div class="mt-8 rounded-xl border border-blue-200 bg-blue-50 p-5">

                    <h3 class="font-semibold text-blue-900 mb-2">

                        ขอบพระคุณในความร่วมมือ

                    </h3>

                    <p class="text-gray-700 leading-7">

                        ผู้วิจัยขอขอบพระคุณท่านเป็นอย่างสูงที่กรุณาสละเวลาในการตอบแบบประเมินฉบับนี้
                        ข้อมูลของท่านจะถูกเก็บรักษาเป็นความลับ และนำไปใช้เพื่อประโยชน์ทางการวิจัย
                        ในการพัฒนารูปแบบการใช้เทคโนโลยีเพื่อการศึกษาสำหรับโรงเรียนพระปริยัติธรรม
                        แผนกสามัญศึกษา กลุ่มจังหวัดแพร่ เท่านั้น

                    </p>

                    <div class="mt-6 text-right">

                        <p class="font-semibold">

                            พระมหาพิพัฒน์ อภิวฑฺฒโน (อายะนันท์)

                        </p>

                        <p class="text-gray-600">

                            ผู้วิจัย

                        </p>

                    </div>

                </div>

                <x-survey.navigation
                    submit="true"
                    submitText="ส่งแบบประเมิน" />

            </div>

        </div>

    </form>

</div>

@endsection