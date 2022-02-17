<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_all_notes(){
        $user = User::factory()->create();
        $notes = Note::factory()->count(10)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/note');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'user_id',
                    'title',
                    'content',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]
        ]);
    }

    public function test_user_can_create_note(){
        $data = [
            'user_id' => 1,
            'title' => 'New note',
            'content' => 'Content of new note.'
        ];

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/note', $data);
        $response->assertStatus(201);
        $response->assertJson($data);
    }

    public function test_user_can_delete_note(){
        $user = User::factory()->create();
        $note = Note::factory()->create();

        $delete = $this->actingAs($user)->deleteJson('/api/v1/note/' . $note->id);
        $delete->assertStatus(200);
        $delete->assertJsonStructure(['msg']);
    }

    public function test_user_can_update_note(){
        $user = User::factory()->create();
        $note = Note::factory()->create();

        $response = $this->actingAs($user)->patchJson('/api/v1/note/' . $note->id, ['title' => 'Updated title', 'content' => 'Updated content.']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'title',
            'content',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);
    }

    public function test_user_can_read_note(){
        $user = User::factory()->create();
        $note = Note::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/note/' . $note->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'title',
            'content',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);
    }

    public function test_user_can_export_notes_to_excel(){
        $user = User::factory()->create();
        $notes = Note::factory()->count(10)->create();

        Excel::fake();

        $this->actingAs($user)->get('/notes/export-excel');

        Excel::assertDownloaded('my-notes.xlsx');
    }

    public function test_user_can_export_notes_to_pdf(){
        $user = User::factory()->create();
        $notes = Note::factory()->count(10)->create();

        $response = $this->actingAs($user)->get('/notes/export-pdf');
        $response->assertStatus(200);
        $response->assertSee('[/PDF /Text ]')->assertSee('stream');
    }
}
