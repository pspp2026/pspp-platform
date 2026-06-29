document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('scheduleModal');

    if (!modal) {
        return;
    }

    const modalTitle = document.getElementById('modalTitle');

    const subject = document.getElementById('subject_id');
    const teacher = document.getElementById('teacher_id');

    const classroom = document.getElementById('classroom_id');
    const period = document.getElementById('period_id');
    const day = document.getElementById('day_of_week');

    const scheduleId = document.getElementById('schedule_id');

    document.querySelectorAll('.editScheduleBtn').forEach(button => {

        button.addEventListener('click', () => {

            modalTitle.innerText = '✏️ แก้ไขตารางสอน';

            scheduleId.value = button.dataset.id;

            subject.value = button.dataset.subject;
            teacher.value = button.dataset.teacher;

            classroom.value = button.dataset.classroom;
            period.value = button.dataset.period;
            day.value = button.dataset.day;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        });

    });

});