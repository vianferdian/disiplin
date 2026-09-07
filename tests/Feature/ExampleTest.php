<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login_and_access_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'role' => 'wakasek',
        ]);

        $response = $this->post('/login', [
            'login' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_bank_data_proxy_returns_kelas_and_siswa(): void
    {
        $user = User::factory()->create([
            'username' => 'wakasek_test',
            'password' => bcrypt('password123'),
            'role' => 'wakasek',
        ]);

        $response = $this->actingAs($user)->getJson('/api-proxy/kelas');
        $response->assertStatus(200)->assertJson(['success' => true]);

        $siswaResponse = $this->actingAs($user)->getJson('/api-proxy/siswa?kelas=X-RPL-1');
        $siswaResponse->assertStatus(200)->assertJson(['success' => true]);
    }
}
