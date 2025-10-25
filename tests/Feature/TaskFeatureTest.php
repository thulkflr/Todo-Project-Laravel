<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // استخدام هذا لضمان قاعدة بيانات نظيفة لكل اختبار
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TodoModel;
use App\Enums\TodoStatus;
use Illuminate\Http\UploadedFile; // لاختبار رفع الملفات
use Illuminate\Support\Facades\Storage; // للتحقق من الملفات

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase; // تفعيل تنظيف قاعدة البيانات

    /**
     * اختبار: المستخدم غير المسجل (Guest) لا يمكنه رؤية المهام ويتم توجيهه لصفحة Login.
     */
    public function test_guest_cannot_see_tasks(): void
    {
        $response = $this->get(route('tasks.index'));

        // التأكد من إعادة التوجيه (Status 302) إلى صفحة Login
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * اختبار: المستخدم المسجل يمكنه رؤية مهامه فقط.
     */
    public function test_authenticated_user_can_see_only_their_tasks(): void
    {
        // 1. إنشاء مستخدم 1 + مهمة 1
        $user1 = User::factory()->create();
        $task1 = TodoModel::factory()->create(['user_id' => $user1->id, 'title' => 'مهمة المستخدم الأول']);

        // 2. إنشاء مستخدم 2 + مهمة 2
        $user2 = User::factory()->create();
        $TodoModel2 = TodoModel::factory()->create(['user_id' => $user2->id, 'title' => 'مهمة المستخدم الثاني']);

        // 3. تسجيل الدخول كمستخدم 1
        $response = $this->actingAs($user1)->get(route('todos.index'));

        // 4. التأكد من أن الصفحة تعمل (Status 200)
        $response->assertStatus(200);

        // 5. التأكد أنه يرى مهمته
        $response->assertSee('مهمة المستخدم الأول');

        // 6. التأكد (وهو الأهم) أنه لا يرى مهمة المستخدم الآخر
        $response->assertDontSee('مهمة المستخدم الثاني');
    }

    /**
     * اختبار: المستخدم المسجل يمكنه إنشاء مهمة جديدة.
     */
    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        // بيانات المهمة الجديدة
        $taskData = [
            'title' => 'مهمة اختبار جديدة',
            'description' => 'وصف المهمة',
            'status' => TodoStatus::PENDING_TASK->value,
            'due_date' => '2026-01-01',
        ];

        // إرسال طلب POST (تسجيل الدخول كمستخدم)
        $response = $this->actingAs($user)->post(route('todos.store'), $taskData);

        // التأكد من إعادة التوجيه لصفحة المهام
        $response->assertRedirect(route('todos.index'));

        // التأكد من وجود رسالة النجاح
        $response->assertSessionHas('success', 'تم إنشاء المهمة بنجاح!');

        // التأكد من أن المهمة موجودة في قاعدة البيانات ومرتبطة بالمستخدم الصحيح
        $this->assertDatabaseHas('todo_model', [
            'user_id' => $user->id,
            'title' => 'مهمة اختبار جديدة',
            'completed' => 'pending'
        ]);
    }

    /**
     * اختبار: المستخدم المسجل يمكنه إنشاء مهمة مع مرفق.
     */
    public function test_user_can_create_task_with_attachment(): void
    {
        Storage::fake('public'); // استخدام نظام ملفات وهمي

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('document.jpg'); // إنشاء ملف وهمي

        $taskData = [
            'title' => 'مهمة مع مرفق',
            'status' => TodoStatus::PENDING_TASK->value,
            'attachment' => $file,
        ];

        $this->actingAs($user)->post(route('todos.store'), $taskData);

        // التأكد من أن المهمة في قاعدة البيانات
        $task = TodoModel::first();
        $this->assertNotNull($task->attachment);

        // التأكد من أن الملف تم تخزينه في المسار المتوقع
        Storage::disk('public')->assertExists($task->attachment);
    }

    /**
     * اختبار: المستخدم لا يمكنه تحديث مهمة لا يمتلكها.
     */
    public function test_user_cannot_update_other_users_task(): void
    {
        // 1. إنشاء مستخدم 1 + مهمة 1
        $user1 = User::factory()->create();
        $task1 = TodoModel::factory()->create(['user_id' => $user1->id, 'title' => 'مهمة أصلية']);

        // 2. إنشاء مستخدم 2 (المهاجم)
        $user2 = User::factory()->create();

        // 3. محاولة تسجيل الدخول كمستخدم 2 وتحديث مهمة 1
        $response = $this->actingAs($user2)->put(route('todos.update', $task1), [
            'title' => 'تم التعديل (اختراق)',
            'status' => TodoStatus::COMPLETED_TASK->value,
        ]);

        // 4. التأكد من أن الخادم رد بـ 403 Forbidden (غير مصرح لك)
        // هذا يؤكد أن الـ Policy تعمل بنجاح!
        $response->assertStatus(403);

        // 5. التأكد من أن بيانات المهمة الأصلية لم تتغير في قاعدة البيانات
        $this->assertDatabaseHas('todo_model', [
            'id' => $task1->id,
            'title' => 'مهمة أصلية', // العنوان الأصلي
            'status' => $task1->status->value // الحالة الأصلية
        ]);
    }

    /**
     * اختبار: المستخدم لا يمكنه حذف مهمة لا يمتلكها.
     */
    public function test_user_cannot_delete_other_users_task(): void
    {
        // 1. إنشاء مستخدم 1 + مهمة 1
        $user1 = User::factory()->create();
        $task1 = TodoModel::factory()->create(['user_id' => $user1->id]);

        // 2. إنشاء مستخدم 2 (المهاجم)
        $user2 = User::factory()->create();

        // 3. محاولة تسجيل الدخول كمستخدم 2 وحذف مهمة 1
        $response = $this->actingAs($user2)->delete(route('todos.destroy', $task1));

        // 4. التأكد من أن الـ Policy أوقفته (403 Forbidden)
        $response->assertStatus(403);

        // 5. التأكد من أن المهمة لا تزال موجودة في قاعدة البيانات
        $this->assertDatabaseHas('todo_model', [
            'id' => $task1->id,
        ]);
    }
}
