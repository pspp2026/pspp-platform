<!DOCTYPE html>
<html>
<head>
    <title>ปฏิทิน</title>
</head>
<body>

<h1>📅 ปฏิทินกิจกรรม</h1>

<form method="POST" action="/calendar">
    @csrf
    <input type="text" name="title" placeholder="ชื่อกิจกรรม">
    <input type="date" name="event_date">
    <button>เพิ่ม</button>
</form>

<hr>

@foreach($events as $event)
    <p>{{ $event->title }} - {{ $event->event_date }}</p>
@endforeach

</body>
</html>