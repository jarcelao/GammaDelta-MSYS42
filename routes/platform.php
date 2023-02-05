<?php

declare(strict_types=1);

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\PeopleGroup\PeopleGroupEditScreen;
use App\Orchid\Screens\PeopleGroup\PeopleGroupListScreen;
use App\Orchid\Screens\TeamMember\TeamMemberEditScreen;
use App\Orchid\Screens\Team\TeamListScreen;
use App\Orchid\Screens\Team\TeamEditScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push(__('User'), route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Role'), route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Platform > People Group
Route::screen('people-groups', PeopleGroupListScreen::class)
    ->name('platform.people-group')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('People Group'), route('platform.people-group')));

// Platform > People Group > Edit
Route::screen('people-group/{peopleGroup?}', PeopleGroupEditScreen::class)
    ->name('platform.people-group.edit')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.people-group')
        ->push(__('Manage'), route('platform.people-group.edit')));

// Platform > Team
Route::screen('team', TeamListScreen::class)
    ->name('platform.team')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Team'), route('platform.team')));

// Platform > Team > Edit
Route::screen('team/manage/{team?}', TeamEditScreen::class)
    ->name('platform.team.edit')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.team')
        ->push(__('Manage Team'), route('platform.team.edit')));

// Platform > Team > Edit Team Member
Route::screen('team/member/{teamMember?}', TeamMemberEditScreen::class)
    ->name('platform.team-member.edit')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.team')
        ->push(__('Manage Team Member'), route('platform.team-member.edit')));