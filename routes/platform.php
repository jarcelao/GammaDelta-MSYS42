<?php

declare(strict_types=1);

// use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Community\CommunityListScreen;
use App\Orchid\Screens\Community\CommunityCreateScreen;
use App\Orchid\Screens\Community\CommunityManageScreen;
use App\Orchid\Screens\Team\TeamListScreen;
use App\Orchid\Screens\Team\TeamEditScreen;
use App\Orchid\Screens\Program\ProgramEditScreen;
use App\Orchid\Screens\ProgramProgress\ProgramProgressEditScreen;
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
// Route::screen('/main', PlatformScreen::class)
//     ->name('platform.main');

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

// Platform > Community
Route::screen('communities', CommunityListScreen::class)
    ->name('platform.community')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Community'), route('platform.community')));

// Platform > Community > Create
Route::screen('communities/create', CommunityCreateScreen::class)
    ->name('platform.community.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.community')
        ->push(__('Create'), route('platform.community.create')));

// Platform > Community > Manage
Route::screen('communities/manage/{community}', CommunityManageScreen::class)
    ->name('platform.community.manage')
    ->breadcrumbs(fn (Trail $trail, $community) => $trail
        ->parent('platform.community')
        ->push(__('Manage'), route('platform.community.manage', $community)));

// Platform > Team > Manage
Route::screen('teams/manage/{team?}', TeamEditScreen::class)
    ->name('platform.team.manage')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Manage Team'), route('platform.team.manage')));

// Platform > Manage Program
Route::screen('programs/manage/{program?}', ProgramEditScreen::class)
    ->name('platform.program.manage')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Manage'), route('platform.program.manage')));

// Platform > Manage Program Report
Route::screen('programs/report/{programprogess?}', ProgramProgressEditScreen::class)
    ->name('platform.program.report')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Manage'), route('platform.program.report')));