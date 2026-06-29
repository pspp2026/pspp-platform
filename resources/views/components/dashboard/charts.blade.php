<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Students Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">
                👨‍🎓 นักเรียนแต่ละโรงเรียน
            </h2>
        </div>

        <canvas id="studentsChart" height="120"></canvas>

    </div>

    {{-- Teachers Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">
                👨‍🏫 ครูแต่ละโรงเรียน
            </h2>
        </div>

        <canvas id="teachersChart" height="120"></canvas>

    </div>

</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const studentLabels = @json(collect($charts['studentsBySchool'])->pluck('label'));
const studentValues = @json(collect($charts['studentsBySchool'])->pluck('value'));

new Chart(document.getElementById('studentsChart'),{

    type:'bar',

    data:{
        labels:studentLabels,
        datasets:[{
            label:'Students',
            data:studentValues,
            backgroundColor:'#10B981'
        }]
    },

    options:{
        responsive:true,
        plugins:{
            legend:{display:false}
        }
    }

});

const teacherLabels = @json(collect($charts['teachersBySchool'])->pluck('label'));
const teacherValues = @json(collect($charts['teachersBySchool'])->pluck('value'));

new Chart(document.getElementById('teachersChart'),{

    type:'bar',

    data:{
        labels:teacherLabels,
        datasets:[{
            label:'Teachers',
            data:teacherValues,
            backgroundColor:'#3B82F6'
        }]
    },

    options:{
        responsive:true,
        plugins:{
            legend:{display:false}
        }
    }

});

</script>

@endpush