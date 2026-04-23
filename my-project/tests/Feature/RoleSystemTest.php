<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * /dashboard rend désormais directement la bonne vue (200)
     * au lieu de rediriger vers /admin/dashboard, /student/dashboard, etc.
     */
    public function test_dashboard_returns_200_for_all_roles()
    {
        foreach (UserRole::cases() as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->actingAs($user)
                 ->get('/dashboard')
                 ->assertStatus(200);
        }
    }

    /**
     * Les routes rôle-spécifiques (/admin/dashboard, /student/dashboard…)
     * sont des alias qui redirigent vers /dashboard (302).
     */
    public function test_role_dashboard_aliases_redirect_to_central_dashboard()
    {
        $admin    = User::factory()->create(['role' => UserRole::Admin->value]);
        $etudiant = User::factory()->create(['role' => UserRole::Etudiant->value]);
        $entreprise = User::factory()->create(['role' => UserRole::Entreprise->value]);
        $encadrant  = User::factory()->create(['role' => UserRole::Encadrant->value]);

        $this->actingAs($admin)->get('/admin/dashboard')->assertRedirect(route('dashboard'));
        $this->actingAs($etudiant)->get('/student/dashboard')->assertRedirect(route('dashboard'));
        $this->actingAs($entreprise)->get('/entreprise/dashboard')->assertRedirect(route('dashboard'));
        $this->actingAs($encadrant)->get('/encadrant/dashboard')->assertRedirect(route('dashboard'));
    }

    /**
     * Un étudiant ne peut pas accéder à /admin/dashboard (403 ou redirection).
     */
    public function test_etudiant_cannot_access_admin_routes()
    {
        $etudiant = User::factory()->create(['role' => UserRole::Etudiant->value]);
        // The role middleware blocks the request — should not be 200
        $response = $this->actingAs($etudiant)->get('/admin/dashboard');
        $this->assertNotEquals(200, $response->getStatusCode());
    }

    /**
     * Un étudiant ne peut pas accéder à /entreprise/dashboard.
     */
    public function test_etudiant_cannot_access_entreprise_routes()
    {
        $etudiant = User::factory()->create(['role' => UserRole::Etudiant->value]);
        $response = $this->actingAs($etudiant)->get('/entreprise/dashboard');
        $this->assertNotEquals(200, $response->getStatusCode());
    }

    /**
     * L'admin peut changer le rôle d'un utilisateur via l'API.
     */
    public function test_admin_can_change_user_role()
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $user  = User::factory()->create(['role' => UserRole::Etudiant->value]);

        $response = $this->actingAs($admin)->patchJson("/api/users/{$user->id}/role", [
            'role' => UserRole::Encadrant->value,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'role' => UserRole::Encadrant->value,
        ]);
    }

    /**
     * Un rôle invalide est rejeté par la validation (422).
     */
    public function test_invalid_role_fails()
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $user  = User::factory()->create(['role' => UserRole::Etudiant->value]);

        $this->actingAs($admin)
             ->patchJson("/api/users/{$user->id}/role", ['role' => 'invented_role'])
             ->assertStatus(422);
    }
}
