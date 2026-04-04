@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">👤 ผู้ใช้รออนุมัติ</h1>

        <a href="/" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            🏠 HOME
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('admin.users.approve.bulk') }}">
        @csrf

        {{-- ACTION BUTTONS --}}
        <div class="mb-4 flex gap-2">
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
                ✅ อนุมัติที่เลือก
            </button>

            <button type="button" onclick="toggleAll()" 
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">
                🔘 เลือกทั้งหมด
            </button>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="w-full text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-center">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th class="p-3 text-left">ชื่อ</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-center">
                                <input type="checkbox" 
                                       name="user_ids[]" 
                                       value="{{ $user->id }}">
                            </td>

                            <td class="p-3">
                                {{ $user->name }}
                            </td>

                            <td class="p-3">
                                {{ $user->email }}
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                    {{ $user->role }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-6 text-gray-500">
                                ไม่มีผู้ใช้รออนุมัติ 🎉
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </form>

</div>

{{-- JS --}}
<script>
function toggleAll() {
    let checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
    let checkAll = document.getElementById('checkAll');

    checkboxes.forEach(cb => cb.checked = !checkAll.checked);
    checkAll.checked = !checkAll.checked;
}
</script>

@endsection