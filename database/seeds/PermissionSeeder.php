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


            // Manage Applications - 2
            [
                'name' => 'Manage Secure Card Data',
                'slug' => 'manage-applications',
                'type' => 'parent',
                'order' => 2,
            ],
            [
                'name' => 'Add Applications',
                'slug' => 'add-applications',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Edit Applications',
                'slug' => 'edit-applications',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Delete Applications',
                'slug' => 'delete-applications',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Import Applications',
                'slug' => 'import-applications',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Export Applications',
                'slug' => 'export-applications',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Manage Queue Listing',
                'slug' => 'manage-queue-listing',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Export Queue Listing',
                'slug' => 'export-queue-listing',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Add Queue Listing',
                'slug' => 'add-queue-listing',
                'type' => 'child',
                'order' => 2,
            ],
            [
                'name' => 'Remove Queue Listing',
                'slug' => 'remove-queue-listing',
                'type' => 'child',
                'order' => 2,
            ],


            // Manage Universities - 3
            [
                'name' => 'Manage Universities',
                'slug' => 'manage-universities',
                'type' => 'parent',
                'order' => 3,
            ],
            [
                'name' => 'Add Universities',
                'slug' => 'add-universities',
                'type' => 'child',
                'order' => 3,
            ],
            [
                'name' => 'Edit Universities',
                'slug' => 'edit-universities',
                'type' => 'child',
                'order' => 3,
            ],
            [
                'name' => 'Delete Universities',
                'slug' => 'delete-universities',
                'type' => 'child',
                'order' => 3,
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


            // Manage Existing Lawyers - 6
            [
                'name' => 'Manage Existing Lawyers',
                'slug' => 'manage-lawyers',
                'type' => 'parent',
                'order' => 6,
            ],
            [
                'name' => 'Add Lawyers',
                'slug' => 'add-lawyers',
                'type' => 'child',
                'order' => 6,
            ],
            [
                'name' => 'Edit Lawyers',
                'slug' => 'edit-lawyers',
                'type' => 'child',
                'order' => 6,
            ],
            [
                'name' => 'Delete Lawyers',
                'slug' => 'delete-lawyers',
                'type' => 'child',
                'order' => 6,
            ],


            // Manage Cases - 7
            [
                'name' => 'Manage Cases',
                'slug' => 'manage-cases',
                'type' => 'parent',
                'order' => 7,
            ],
            [
                'name' => 'Add Cases',
                'slug' => 'add-cases',
                'type' => 'child',
                'order' => 7,
            ],
            [
                'name' => 'Edit Cases',
                'slug' => 'edit-cases',
                'type' => 'child',
                'order' => 7,
            ],
            [
                'name' => 'Delete Cases',
                'slug' => 'delete-cases',
                'type' => 'child',
                'order' => 7,
            ],

            // Manage Intimation Applications - 8
            [
                'name' => 'Manage Intimations',
                'slug' => 'manage-intimations',
                'type' => 'parent',
                'order' => 8,
            ],
            [
                'name' => 'Add Intimations',
                'slug' => 'add-intimations',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Edit Intimations',
                'slug' => 'edit-intimations',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Delete Intimations',
                'slug' => 'delete-intimations',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Start Date',
                'slug' => 'intimation-start-date',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Activity Log',
                'slug' => 'intimation-activity-log',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Approved',
                'slug' => 'intimation-approved',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Notes',
                'slug' => 'intimation-notes',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Messages',
                'slug' => 'intimation-messages',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Reports',
                'slug' => 'intimation-reports',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Edit Partial Intimation',
                'slug' => 'edit-partial-intimation',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Reset Payment',
                'slug' => 'intimation-reset-payment',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Srl Joining Date',
                'slug' => 'intimation-srl-joining-date',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Srl Joining Date Validation',
                'slug' => 'intimation-srl-joining-date-validation',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Detail Print',
                'slug' => 'intimation-detail-print',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Add Intimation RCPT Date',
                'slug' => 'add-intimation-rcpt-date',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Edit Intimation RCPT Date',
                'slug' => 'edit-intimation-rcpt-date',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Account Department Approve Payment',
                'slug' => 'account-department-approve-payment',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Account Department Disapprove Payment',
                'slug' => 'account-department-disapprove-payment',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Intimation Objections',
                'slug' => 'intimation-objections',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Edit Senior Lawyer Information',
                'slug' => 'intimation-edit-senior-lawyer-information',
                'type' => 'child',
                'order' => 8,
            ],
            [
                'name' => 'Edit Academic Record',
                'slug' => 'intimation-edit-academic-record',
                'type' => 'child',
                'order' => 8,
            ],

            // Manage Payments - 9
            [
                'name' => 'Manage Payments',
                'slug' => 'manage-payments',
                'type' => 'parent',
                'order' => 9,
            ],
            [
                'name' => 'Add Payments',
                'slug' => 'add-payments',
                'type' => 'child',
                'order' => 9,
            ],
            [
                'name' => 'Edit Payment Fees',
                'slug' => 'edit-payment-fees',
                'type' => 'child',
                'order' => 9,
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
            // Manage Policies - 11
            [
                'name' => 'Manage Policies',
                'slug' => 'manage-policies',
                'type' => 'parent',
                'order' => 11,
            ],
            [
                'name' => 'Add Policies',
                'slug' => 'add-policies',
                'type' => 'child',
                'order' => 11,
            ],
            [
                'name' => 'Edit Policies',
                'slug' => 'edit-policies',
                'type' => 'child',
                'order' => 11,
            ],

            // Manage Complaints - 12
            [
                'name' => 'Manage Complaints',
                'slug' => 'manage-complaints',
                'type' => 'parent',
                'order' => 12,
            ],
            // Manage Certificates - 13
            [
                'name' => 'Manage Certificates',
                'slug' => 'manage-certificates',
                'type' => 'parent',
                'order' => 13,
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
            // Manage Vouchers - 15
            [
                'name' => 'Manage Vouchers',
                'slug' => 'manage-vouchers',
                'type' => 'parent',
                'order' => 15,
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
            // Manage Police Verification - 17
            [
                'name' => 'Manage Police Verification',
                'slug' => 'manage-police-verification',
                'type' => 'parent',
                'order' => 17,
            ],

            // Manage Lower Court - 18
            [
                'name' => 'Manage Lower Court',
                'slug' => 'manage-lower-court',
                'type' => 'parent',
                'order' => 18,
            ],
            [
                'name' => 'Add Lower Court',
                'slug' => 'add-lower-court',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit Lower Court',
                'slug' => 'edit-lower-court',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Delete Lower Court',
                'slug' => 'delete-lower-court',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit Partial Lower Court',
                'slug' => 'edit-partial-lower-court',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add Interview Secret Code LC',
                'slug' => 'add-interview-secret-code-lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Print Interview Letter LC',
                'slug' => 'print-interview-letter-lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Assign Interview Members LC',
                'slug' => 'assign-interview-members-lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Print Detail LC',
                'slug' => 'print-detail-lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Lower Court Activity Log',
                'slug' => 'lower-court-activity-log',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add License No',
                'slug' => 'add_license_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit License No',
                'slug' => 'edit_license_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add BF No',
                'slug' => 'add_bf_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit BF No',
                'slug' => 'edit_bf_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add PLJ No',
                'slug' => 'add_plj_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit PLJ No',
                'slug' => 'edit_plj_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add Group Insurance No',
                'slug' => 'add_gi_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit Group Insurance No',
                'slug' => 'edit_gi_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Add Register/Ledger No',
                'slug' => 'add_reg_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit Register/Ledger No',
                'slug' => 'edit_reg_no_lc',
                'type' => 'child',
                'order' => 18,
            ],

            [
                'name' => 'Add BF Ledger No',
                'slug' => 'add_bf_ledger_no',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit BF Ledger No',
                'slug' => 'edit_bf_ledger_no',
                'type' => 'child',
                'order' => 18,
            ],

            [
                'name' => 'Add Rcpt No & Date',
                'slug' => 'add_rcpt_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Edit Rcpt No & Date',
                'slug' => 'edit_rcpt_no_lc',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Update LC Date',
                'slug' => 'update_lc_date',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'LC Edit Payment Fee',
                'slug' => 'lc_edit_payment_fee',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'LC Quick Create',
                'slug' => 'lc_quick_create',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'LC Quick Edit',
                'slug' => 'lc_quick_edit',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Lc Reset Payment',
                'slug' => 'lc_reset_payment',
                'type' => 'child',
                'order' => 18,
            ],
            [
                'name' => 'Lc Objections',
                'slug' => 'lc_objections',
                'type' => 'child',
                'order' => 18,
            ],

            // Manage Members - 19
            [
                'name' => 'Manage Members',
                'slug' => 'manage-members',
                'type' => 'parent',
                'order' => 19,
            ],
            [
                'name' => 'Add Members',
                'slug' => 'add-members',
                'type' => 'child',
                'order' => 19,
            ],
            [
                'name' => 'Edit Members',
                'slug' => 'edit-members',
                'type' => 'child',
                'order' => 19,
            ],
            [
                'name' => 'Delete Members',
                'slug' => 'delete-members',
                'type' => 'child',
                'order' => 19,
            ],

            // Manage High Court - 20
            // Manage High Court - 20
            [
                'name' => 'Manage High Court',
                'slug' => 'manage-high-court',
                'type' => 'parent',
                'order' => 20,
            ],
            [
                'name' => 'Add High Court',
                'slug' => 'add-high-court',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit High Court',
                'slug' => 'edit-high-court',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Delete High Court',
                'slug' => 'delete-high-court',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit Partial High Court',
                'slug' => 'edit-partial-high-court',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add Interview Secret Code HC',
                'slug' => 'add-interview-secret-code-hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Print Interview Letter HC',
                'slug' => 'print-interview-letter-hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Assign Interview Members HC',
                'slug' => 'assign-interview-members-hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Print Detail HC',
                'slug' => 'print-detail-hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'High Court Activity Log',
                'slug' => 'high-court-activity-log',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add License No',
                'slug' => 'add_license_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit License No',
                'slug' => 'edit_license_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add HCR No',
                'slug' => 'add_hcr_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit HCR No',
                'slug' => 'edit_hcr_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add BF No',
                'slug' => 'add_bf_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit BF No',
                'slug' => 'edit_bf_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add PLJ No',
                'slug' => 'add_plj_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit PLJ No',
                'slug' => 'edit_plj_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add Group Insurance No',
                'slug' => 'add_gi_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit Group Insurance No',
                'slug' => 'edit_gi_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add Register/Ledger No',
                'slug' => 'add_reg_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit Register/Ledger No',
                'slug' => 'edit_reg_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Add Rcpt No & Date',
                'slug' => 'add_rcpt_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit Rcpt No & Date',
                'slug' => 'edit_rcpt_no_hc',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Update HC Date',
                'slug' => 'update_hc_date',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'HC Edit Payment Fee',
                'slug' => 'hc_edit_payment_fee',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'HC Quick Create',
                'slug' => 'hc_quick_create',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'HC Quick Edit',
                'slug' => 'hc_quick_edit',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'HC Reset Payment',
                'slug' => 'hc_reset_payment',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'HC Objections',
                'slug' => 'hc_objections',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Move GCLC To GCHC',
                'slug' => 'move_gclc_to_gchc',
                'type' => 'child',
                'order' => 20,
            ],

            //
            [
                'name' => 'Add LC License',
                'slug' => 'add_lc_lic',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit LC License',
                'slug' => 'edit_lc_lic',
                'type' => 'child',
                'order' => 20,
            ],

            [
                'name' => 'Add LC Ledger',
                'slug' => 'add_lc_ledger',
                'type' => 'child',
                'order' => 20,
            ],
            [
                'name' => 'Edit LC Ledger',
                'slug' => 'edit_lc_ledger',
                'type' => 'child',
                'order' => 20,
            ],


            // Manage GC Lower Court - 21
            [
                'name' => 'GC Lower Court',
                'slug' => 'gc_lower_court',
                'type' => 'parent',
                'order' => 21,
            ],
            [
                'name' => 'Edit',
                'slug' => 'gc_lower_court_edit',
                'type' => 'child',
                'order' => 21,
            ],

            // Manage GC High Court - 22
            [
                'name' => 'GC high Court',
                'slug' => 'gc_high_court',
                'type' => 'parent',
                'order' => 22,
            ],
            [
                'name' => 'Edit',
                'slug' => 'gc_high_court_edit',
                'type' => 'child',
                'order' => 22,
            ],

            // Manage Lawyer Requests - 23
            [
                'name' => 'Lawyer Requests',
                'slug' => 'manage-lawyer-requests',
                'type' => 'parent',
                'order' => 23,
            ],
            [
                'name' => 'Delete',
                'slug' => 'lawyer_request_delete',
                'type' => 'child',
                'order' => 23,
            ],
            [
                'name' => 'Reset',
                'slug' => 'lawyer_request_reset',
                'type' => 'child',
                'order' => 23,
            ],

            // Manage Lawyer Complaints - 24
            [
                'name' => 'Lawyer Complaint',
                'slug' => 'lawyer_complaint',
                'type' => 'parent',
                'order' => 24,
            ],

            // Manage Posts - 25
            [
                'name' => 'Manage Posts',
                'slug' => 'manage_posts',
                'type' => 'parent',
                'order' => 25,
            ],

            // Manage Elections - 26
            [
                'name' => 'Manage Elections',
                'slug' => 'manage-elections',
                'type' => 'parent',
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
            [
                'name' => 'Delete Elections',
                'slug' => 'delete-elections',
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
            [
                'name' => 'Delete Seats',
                'slug' => 'delete-seats',
                'type' => 'child',
                'order' => 27,
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
