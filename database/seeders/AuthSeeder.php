<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws \Throwable
     */
    public function run(): void
    {
        \DB::transaction(function () {
            $admin = User::forceCreateQuietly([
                'name' => 'fx112',
                'email' => 'yf244190857@gmail.com',
                'password' => '098a4d9f',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Role::create([
                'name' => 'super admin',
                'guard_name' => 'web',
            ]);

            Role::create([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $admin->assignRole(['admin', 'super admin']);

            $permissions = [
                [
                    'name' => 'admin dashboard',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'dashboard',
                        'route' => 'admin.dashboard',
                        'icon' => 'space_dashboard',
                        'icon_color' => '#179688',
                    ],
                ],
                [
                    'name' => 'admin menu auth',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'auth',
                        'icon' => 'key_off',
                        'icon_color' => '#3f51b5',
                    ],
                    'children' => [
                        [
                            'name' => 'users viewAny',
                            'guard_name' => 'web',
                            'admin_menu' => [
                                'label' => 'users.index',
                                'route' => 'admin.users.index',
                                'icon' => 'manage_accounts',
                                'icon_color' => '#2599c7',
                            ],
                            'children' => [
                                ['name' => 'users view', 'guard_name' => 'web'],
                                ['name' => 'users create', 'guard_name' => 'web'],
                                ['name' => 'users update', 'guard_name' => 'web'],
                                ['name' => 'users delete', 'guard_name' => 'web'],
                                ['name' => 'users own resource', 'guard_name' => 'web'],
                            ],
                        ],
                        [
                            'name' => 'roles viewAny',
                            'guard_name' => 'web',
                            'admin_menu' => [
                                'label' => 'roles.index',
                                'route' => 'admin.roles.index',
                                'icon' => 'group',
                                'icon_color' => '#2599c7',
                            ],
                            'children' => [
                                ['name' => 'roles view', 'guard_name' => 'web'],
                                ['name' => 'roles create', 'guard_name' => 'web'],
                                ['name' => 'roles update', 'guard_name' => 'web'],
                                ['name' => 'roles delete', 'guard_name' => 'web'],
                            ],
                        ],
                        [
                            'name' => 'permissions viewAny',
                            'guard_name' => 'web',
                            'admin_menu' => [
                                'route' => 'admin.permissions.index',
                                'icon' => 'gpp_good',
                                'icon_color' => '#2599c7',
                                'label' => 'permissions.index',
                            ],
                            'children' => [
                                ['name' => 'permissions view', 'guard_name' => 'web'],
                                ['name' => 'permissions create', 'guard_name' => 'web'],
                                ['name' => 'permissions update', 'guard_name' => 'web'],
                                ['name' => 'permissions delete', 'guard_name' => 'web'],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'posts viewAny',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'posts',
                        'icon' => 'article',
                        'icon_color' => '#f74d6b',
                        'route' => 'admin.posts.index',
                    ],
                    'children' => [
                        ['name' => 'posts create', 'guard_name' => 'web'],
                        ['name' => 'posts update', 'guard_name' => 'web'],
                        ['name' => 'posts delete', 'guard_name' => 'web'],
                        ['name' => 'posts own resource', 'guard_name' => 'web'],
                    ],
                ],
                [
                    'name' => 'categories viewAny',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'categories',
                        'icon' => 'menu',
                        'icon_color' => '#f33b9f',
                        'route' => 'admin.categories.index',
                    ],
                    'children' => [
                        ['name' => 'categories create', 'guard_name' => 'web'],
                        ['name' => 'categories update', 'guard_name' => 'web'],
                        ['name' => 'categories delete', 'guard_name' => 'web'],
                    ],
                ],
                [
                    'name' => 'tags viewAny',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'tags',
                        'icon' => 'tag',
                        'icon_color' => '#f1b059',
                        'route' => 'admin.tags.index',
                    ],
                    'children' => [
                        ['name' => 'tags create', 'guard_name' => 'web'],
                        ['name' => 'tags update', 'guard_name' => 'web'],
                        ['name' => 'tags delete', 'guard_name' => 'web'],
                    ],
                ],
                [
                    'name' => 'sites edit',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'site',
                        'icon' => 'settings',
                        'icon_color' => '#b34343',
                        'route' => 'admin.sites.edit',
                        'route_params' => [
                            [
                                'name' => 'site',
                                'value' => 1,
                            ],
                        ],
                    ],
                    'children' => [
                        ['name' => 'sites update', 'guard_name' => 'web'],
                    ],
                ],
                [
                    'name' => 'comments viewAny',
                    'guard_name' => 'web',
                    'admin_menu' => [
                        'label' => 'comments',
                        'icon' => 'comment',
                        'icon_color' => '#375757',
                        'route' => 'admin.comments.index',
                    ],
                    'children' => [
                        ['name' => 'comments create', 'guard_name' => 'web'],
                        ['name' => 'comments update', 'guard_name' => 'web'],
                        ['name' => 'comments delete', 'guard_name' => 'web'],
                        ['name' => 'comments own resource', 'guard_name' => 'web'],
                        ['name' => 'comments approve', 'guard_name' => 'web'],
                    ],
                ],
                [
                    'name' => 'vote',
                    'guard_name' => 'web',
                ],
            ];

            foreach ($permissions as $permission) {
                $this->createPermission($permission);
            }

            $commentRole = Role::create([
                'name' => 'commenter',
                'guard_name' => 'web',
            ]);
            $commentRole->givePermissionTo('comments viewAny', 'comments create', 'comments update', 'comments delete', 'comments own resource', 'comments approve');

            $voteRole = Role::create([
                'name' => 'voter',
                'guard_name' => 'web',
            ]);
            $voteRole->givePermissionTo('vote');
        });
    }

    protected function createPermission(array $data, ?AdminMenu $parent = null): void
    {
        $save_data = $data;

        unset($save_data['children']);
        unset($save_data['admin_menu']);

        $permission = new Permission;
        $permission->fill($save_data);
        $permission->save();

        if (isset($data['admin_menu']) && $data['admin_menu']) {
            if ($parent) {
                $data['admin_menu']['parent_id'] = $parent->id;
            }
            $menu = $permission->adminMenu()->create($data['admin_menu']);
        }

        if (array_key_exists('children', $data) && $data['children']) {
            foreach ($data['children'] as $item) {
                $this->createPermission($item, $menu ?? null);
            }
        }
    }
}
