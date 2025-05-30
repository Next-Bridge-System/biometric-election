<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Permission;
use App\AdminPermission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_array = [
            // Manage Operators - 1
            [
                'name' => 'Manage Operators',
                'slug' => 'manage-operators',
                'type' => 'parent',
                'order' => 1,
            ],
            [
                'name' => 'Add Operators',
                'slug' => 'add-operators',
                'type' => 'child',
                'order' => 1,
            ],
            [
                'name' => 'Edit Operators',
                'slug' => 'edit-operators',
                'type' => 'child',
                'order' => 1,
            ],
            [
                'name' => 'Delete Operators',
                'slug' => 'delete-operators',
                'type' => 'child',
                'order' => 1,
            ],


            // Manage Districts & Tehsils - 4
            [
                'name' => 'Manage Districts & Tehsils',
                'slug' => 'manage-districts',
                'type' => 'parent',
                'order' => 4,
            ],
            [
                'name' => 'Add Districts',
                'slug' => 'add-districts',
                'type' => 'child',
                'order' => 4,
            ],
            [
                'name' => 'Edit Districts',
                'slug' => 'edit-districts',
                'type' => 'child',
                'order' => 4,
            ],
            [
                'name' => 'Delete Districts',
                'slug' => 'delete-districts',
                'type' => 'child',
                'order' => 4,
            ],


            // Manage Bars - 5
            [
                'name' => 'Manage Bars',
                'slug' => 'manage-bars',
                'type' => 'parent',
                'order' => 5,
            ],
            [
                'name' => 'Add Bars',
                'slug' => 'add-bars',
                'type' => 'child',
                'order' => 5,
            ],
            [
                'name' => 'Edit Bars',
                'slug' => 'edit-bars',
                'type' => 'child',
                'order' => 5,
            ],
            [
                'name' => 'Delete Bars',
                'slug' => 'delete-bars',
                'type' => 'child',
                'order' => 5,
            ],

            // Manage Users - 10
            [
                'name' => 'Manage Users',
                'slug' => 'manage-users',
                'type' => 'parent',
                'order' => 10,
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'edit-users',
                'type' => 'child',
                'order' => 10,
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'delete-users',
                'type' => 'child',
                'order' => 10,
            ],
            [
                'name' => 'Users Direct Login',
                'slug' => 'users-direct-login',
                'type' => 'child',
                'order' => 10,
            ],
            [
                'name' => 'GC Users',
                'slug' => 'gc-users',
                'type' => 'child',
                'order' => 10,
            ],
            
            // Manage Reports - 14
            [
                'name' => 'Manage Reports',
                'slug' => 'manage-reports',
                'type' => 'parent',
                'order' => 14,
            ],
            [
                'name' => 'General Report',
                'slug' => 'general_report',
                'type' => 'child',
                'order' => 14,
            ],
            [
                'name' => 'Export Reports',
                'slug' => 'export-reports',
                'type' => 'child',
                'order' => 14,
            ],
           
            // Manage Biometric Verification - 16
            [
                'name' => 'Manage Biometric Verification',
                'slug' => 'manage-biometric-verification',
                'type' => 'parent',
                'order' => 16,
            ],
            [
                'name' => 'Delete Biometric Fingerprint',
                'slug' => 'delete-biometric-fingerprint',
                'type' => 'child',
                'order' => 16,
            ],
            
            // Manage Elections - 26
            [
                'name' => 'Manage Elections',
                'slug' => 'manage-elections',
                'type' => 'parent',
                'order' => 26,
            ],
            [
                'name' => 'View Elections',
                'slug' => 'view-elections',
                'type' => 'child',
                'order' => 26,
            ],
            [
                'name' => 'Add Elections',
                'slug' => 'add-elections',
                'type' => 'child',
                'order' => 26,
            ],
            [
                'name' => 'Edit Elections',
                'slug' => 'edit-elections',
                'type' => 'child',
                'order' => 26,
            ],

            // Manage Seats - 27
            [
                'name' => 'Manage Seats',
                'slug' => 'manage-seats',
                'type' => 'parent',
                'order' => 27,
            ],
            [
                'name' => 'Add Seats',
                'slug' => 'add-seats',
                'type' => 'child',
                'order' => 27,
            ],
            [
                'name' => 'Edit Seats',
                'slug' => 'edit-seats',
                'type' => 'child',
                'order' => 27,
            ],

            // Manage Candidates - 28
            [
                'name' => 'Manage Candidates',
                'slug' => 'manage-candidates',
                'type' => 'parent',
                'order' => 28,
            ],
            [
                'name' => 'Add Candidates',
                'slug' => 'add-candidates',
                'type' => 'child',
                'order' => 28,
            ],
            [
                'name' => 'Edit Candidates',
                'slug' => 'edit-candidates',
                'type' => 'child',
                'order' => 28,
            ],
        ];

        foreach ($permission_array as $key => $value) {
            Permission::updateOrCreate(['slug' => $value['slug']], $value);
        }

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            AdminPermission::updateOrCreate(['admin_id' => 1, 'permission_id' => $permission->id], [
                'admin_id' => 1,
                'permission_id' => $permission->id
            ]);
        }
    }
}
