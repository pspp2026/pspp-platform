use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonProgressController extends Controller
{
    // เริ่มเรียน (สร้าง record ถ้ายังไม่มี)
    public function startLesson($lessonId)
    {
        $userId = auth()->id();

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId
            ],
            [
                'progress_percent' => 0,
                'time_spent' => 0
            ]
        );

        return response()->json($progress);
    }

    // 📖 mark read + time
    public function markRead(Request $request, $lessonId)
    {
        $progress = $this->getProgressOrFail($lessonId);

        $progress->read_done = true;
        $progress->read_at = now();

        // เพิ่มเวลา (วินาที) ที่ส่งมาจาก client
        $this->addTime($progress, $request->input('time_spent', 0));

        $this->recalculateProgress($progress);
        $this->checkCompletion($progress);

        return response()->json($progress);
    }

    // 📝 submit quiz + score + time
    public function submitQuiz(Request $request, $lessonId)
    {
        $progress = $this->getProgressOrFail($lessonId);

        $score = (int) $request->input('score', 0);

        $progress->quiz_done = true;
        $progress->quiz_score = $score;
        $progress->quiz_at = now();

        $this->addTime($progress, $request->input('time_spent', 0));

        $this->recalculateProgress($progress);
        $this->checkCompletion($progress);

        return response()->json($progress);
    }

    // 🧠 reflection + time
    public function submitReflection(Request $request, $lessonId)
    {
        $progress = $this->getProgressOrFail($lessonId);

        $progress->reflection_done = true;
        $progress->reflection_at = now();

        $this->addTime($progress, $request->input('time_spent', 0));

        $this->recalculateProgress($progress);
        $this->checkCompletion($progress);

        return response()->json($progress);
    }

    // ⏱️ heartbeat สำหรับสะสมเวลา (เรียกทุก 10–30 วิ)
    public function heartbeat(Request $request, $lessonId)
    {
        $progress = $this->getProgressOrFail($lessonId);

        $this->addTime($progress, $request->input('time_spent', 0));

        return response()->json(['time_spent' => $progress->time_spent]);
    }

    // =======================
    // 🔧 Helper Methods
    // =======================

    private function getProgressOrFail($lessonId)
    {
        return LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lessonId)
            ->firstOrFail();
    }

    // เพิ่มเวลาแบบปลอดภัย (กันค่าติดลบ/เกินจริง)
    private function addTime(LessonProgress $progress, $seconds)
    {
        $seconds = max(0, min((int)$seconds, 300)); // ต่อครั้งไม่เกิน 5 นาที
        $progress->time_spent += $seconds;
        $progress->save();
    }

    // คำนวณ % (3 กิจกรรม = 100%)
    private function recalculateProgress(LessonProgress $progress)
    {
        $total = 3;
        $done = 0;

        if ($progress->read_done) $done++;
        if ($progress->quiz_done) $done++;
        if ($progress->reflection_done) $done++;

        $progress->progress_percent = (int) round(($done / $total) * 100);
        $progress->save();
    }

    // เช็คจบ (ปรับ threshold ได้)
    private function checkCompletion(LessonProgress $progress)
    {
        $minTime = 300; // 5 นาที
        $minScore = 70;

        if (
            $progress->read_done &&
            $progress->quiz_done &&
            $progress->quiz_score >= $minScore &&
            $progress->reflection_done &&
            $progress->time_spent >= $minTime
        ) {
            if (!$progress->completed) {
                $progress->completed = true;
                $progress->completed_at = now();
                $progress->save();

                // 👉 ตรงนี้คือ hook ไป Step 2 (Point/Badge)
                // app(BadgeService::class)->onLessonCompleted($progress);
            }
        }
    }
}