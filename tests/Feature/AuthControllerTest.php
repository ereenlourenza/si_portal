<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\UserModel;

class AuthControllerTest extends TestCase
{
    // Jangan pakai RefreshDatabase karena bisa hapus datamu

    /** @test */
    public function index_returns_login_view()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function authenticate_successful_login_redirects_to_beranda()
    {
        $response = $this->post(route('login.authenticate'), [
            'username' => 'admin',
            'password' => 'admin12345', // dari data seed
        ]);

        $response->assertRedirect(route('beranda.index'));
        $this->assertAuthenticated(); // pastikan user berhasil login
    }

    /** @test */
    public function authenticate_failed_login_redirects_back_with_errors()
    {
        $response = $this->from(route('login.index'))->post(route('login.authenticate'), [
            'username' => 'admin',
            'password' => 'salah_password',
        ]);

        $response->assertRedirect(route('login.index'));
        $response->assertSessionHasErrors('authentication');
        $this->assertGuest(); // pastikan user belum login
    }

    /** @test */
    public function logout_logs_user_out_and_redirects_to_login()
    {
        // Login manual menggunakan user yang ada
        $user = UserModel::where('username', 'admin')->first();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login.index'));
        $this->assertGuest(); // Pastikan user sudah logout
    }
}
