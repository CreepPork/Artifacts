<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UploadTest extends TestCase
{
    /** @test */
    public function a_created_upload_can_be_seen()
    {
        $this->get('/api/upload')->assertJson([]);

        $upload = $this->create(Upload::class);

        $this->get('/api/upload')->assertSee($upload->filename);
        $this->get('/api/upload/' . $upload->id)->assertSee($upload->filename);
    }

    /** @test */
    public function a_file_can_be_uploaded()
    {
        $response = $this->post('/api/upload', [
            'accessToken' => env('ACCESS_TOKEN'),
            'zip' => $file = UploadedFile::fake()->create('thisIsATest.zip')
        ]);

        $response->assertSuccessful();

        $this->assertFileExists($file->path());
    }

    /** @test */
    public function a_file_cannot_be_uploaded_without_access_token()
    {
        $response = $this->post('/api/upload', [
            'accessToken' => 'thisIsNotTheCorrectToken',
            'zip' => UploadedFile::fake()->create('thisIsATest.zip')
        ]);

        $response->assertForbidden();

        $response->assertJson([
            'accessToken' => 'The access token is invalid.'
        ]);
    }

    /** @test */
    public function update_and_destroy_methods_are_only_for_logged_in_users()
    {
        $upload = $this->create(Upload::class);

        $this->patch('/api/upload/' . $upload->id, [])->assertRedirect('/login');
        $this->delete('/api/upload/' . $upload->id, [])->assertRedirect('/login');
    }

    /** @test */
    public function a_upload_can_be_edited()
    {
        $this->signIn();

        $upload = $this->create(Upload::class);

        $response = $this->json('PATCH', '/api/upload/' . $upload->id, [
            'zip' => UploadedFile::fake()->create('thisIsATest.zip')
        ]);

        $response->assertSee('thisIsATest.zip');
        $response->assertDontSee($upload->filename);
        $response->assertDontSee($upload->path);

        $this->get('/api/upload')->assertSee('thisIsATest.zip');
    }

    /** @test */
    public function a_upload_can_be_deleted()
    {
        $this->signIn();

        $upload = $this->create(Upload::class);

        $this->json('DELETE', '/api/upload/' . $upload->id)->assertJson(['success' => true]);

        $this->get('/api/upload')->assertDontSee($upload->path);
    }
}
