<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Grade;
use App\Models\Message;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EduTrackDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_is_accessible_after_login(): void
    {
        $user = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_admin_can_create_a_student(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $class = SchoolClass::factory()->create();

        $this->actingAs($admin)
            ->post(route('students.store'), [
                'first_name' => 'Sara',
                'last_name' => 'Benali',
                'date_of_birth' => '2012-04-10',
                'gender' => 'Féminin',
                'school_class_id' => $class->id,
                'parent_phone' => '0600000999',
                'address' => 'Casablanca',
                'social_status' => 'Bonne',
                'medical_notes' => 'Aucune',
                'status' => 'actif',
            ])
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'first_name' => 'Sara',
            'last_name' => 'Benali',
        ]);
    }

    public function test_teacher_only_sees_students_from_assigned_classes(): void
    {
        $teacher = User::factory()->create(['role' => UserRole::Enseignant]);
        $otherTeacher = User::factory()->create(['role' => UserRole::Enseignant]);

        $ownedClass = SchoolClass::factory()->create(['teacher_id' => $teacher->id]);
        $foreignClass = SchoolClass::factory()->create(['teacher_id' => $otherTeacher->id]);

        $ownedStudent = Student::factory()->create(['school_class_id' => $ownedClass->id]);
        $foreignStudent = Student::factory()->create(['school_class_id' => $foreignClass->id]);

        $response = $this->actingAs($teacher)->get(route('students.index'));

        $response->assertOk();
        $response->assertSee($ownedStudent->full_name);
        $response->assertDontSee($foreignStudent->full_name);
    }

    public function test_student_average_is_calculated_correctly(): void
    {
        $student = Student::factory()->create();
        $subject = Subject::factory()->create(['default_coefficient' => 2]);
        $teacher = User::factory()->create(['role' => UserRole::Enseignant]);

        Grade::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'score' => 12,
            'coefficient' => 2,
            'semester' => 'S1',
        ]);

        Grade::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'score' => 16,
            'coefficient' => 1,
            'semester' => 'S1',
        ]);

        $this->assertSame(13.33, $student->fresh()->averageScore());
    }

    public function test_bulletin_is_accessible(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $student = Student::factory()->create();

        $this->actingAs($admin)
            ->get(route('students.bulletin', $student))
            ->assertOk();
    }

    public function test_message_can_be_sent(): void
    {
        $sender = User::factory()->create(['role' => UserRole::Enseignant]);
        $recipient = User::factory()->create(['role' => UserRole::Encadrant]);

        $this->actingAs($sender)
            ->post(route('messages.store'), [
                'recipient_id' => $recipient->id,
                'subject' => 'Coordination',
                'body' => 'Merci de vérifier le dossier élève.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'subject' => 'Coordination',
        ]);
    }

    public function test_transformation_page_is_accessible_publicly(): void
    {
        $this->get(route('transformation-digitale'))->assertOk();
    }
}
