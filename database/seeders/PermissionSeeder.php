<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['id' => 1,'name' => 'access_role_permission','guard_name' => 'web',],
            ['id' => 2,'name' => 'crm','guard_name' => 'web',],
            ['id' => 3,'name' => 'employee','guard_name' => 'web',],
            ['id' => 4,'name' => 'inventory','guard_name' => 'web',],
            ['id' => 5,'name' => 'project','guard_name' => 'web',],
            ['id' => 6,'name' => 'hrm','guard_name' => 'web',],
            ['id' => 7,'name' => 'expense','guard_name' => 'web',],
            ['id' => 8,'name' => 'revenue','guard_name' => 'web',],
            ['id' => 9,'name' => 'account','guard_name' => 'web',],
            ['id' => 10,'name' => 'investment','guard_name' => 'web',],
            ['id' => 11,'name' => 'settings','guard_name' => 'web',],
            ['id' => 12,'name' => 'employee_create','guard_name' => 'web',],
            ['id' => 13,'name' => 'employee_update','guard_name' => 'web',],
            ['id' => 14,'name' => 'employee_documents_update','guard_name' => 'web',],
            ['id' => 15,'name' => 'employee_documents_delete','guard_name' => 'web',],
            ['id' => 16,'name' => 'employee_identity_update','guard_name' => 'web',],
            ['id' => 17,'name' => 'employee_identity_delete','guard_name' => 'web',],
            ['id' => 18,'name' => 'employee_address_update','guard_name' => 'web',],
            ['id' => 19,'name' => 'employee_qualifications_update','guard_name' => 'web',],
            ['id' => 20,'name' => 'employee_qualifications_delete','guard_name' => 'web',],
            ['id' => 21,'name' => 'employee_workexperience_update','guard_name' => 'web',],
            ['id' => 22,'name' => 'employee_workexperience_delete','guard_name' => 'web',],
            ['id' => 23,'name' => 'employee_certification_update','guard_name' => 'web',],
            ['id' => 24,'name' => 'employee_certification_delete','guard_name' => 'web',],
            ['id' => 25,'name' => 'employee_reference_update','guard_name' => 'web',],
            ['id' => 26,'name' => 'employee_reference_delete','guard_name' => 'web',],
            ['id' => 27,'name' => 'employee_bank_accounts_update','guard_name' => 'web',],
            ['id' => 28,'name' => 'employee_bank_accounts_delete','guard_name' => 'web',],
            ['id' => 29,'name' => 'employee_delete','guard_name' => 'web',],
            ['id' => 30,'name' => 'employee_show','guard_name' => 'web',],
            ['id' => 31,'name' => 'employee_profile','guard_name' => 'web',],
            ['id' => 32,'name' => 'employee_import','guard_name' => 'web',],
            ['id' => 33,'name' => 'employee_export','guard_name' => 'web',],
            ['id' => 34,'name' => 'deparment','guard_name' => 'web',],
            ['id' => 35,'name' => 'deparment_create','guard_name' => 'web',],
            ['id' => 36,'name' => 'deparment_edit','guard_name' => 'web',],
            ['id' => 37,'name' => 'deparment_delete','guard_name' => 'web',],
            ['id' => 38,'name' => 'designation','guard_name' => 'web',],
            ['id' => 39,'name' => 'designation_create','guard_name' => 'web',],
            ['id' => 40,'name' => 'designation_edit','guard_name' => 'web',],
            ['id' => 41,'name' => 'designation_delete','guard_name' => 'web',],
            ['id' => 42,'name' => 'crm_setting_view','guard_name' => 'web',],
            ['id' => 43,'name' => 'interested_on','guard_name' => 'web',],
            ['id' => 44,'name' => 'interested_create','guard_name' => 'web',],
            ['id' => 45,'name' => 'interested_edit','guard_name' => 'web',],
            ['id' => 46,'name' => 'interested_delete','guard_name' => 'web',],
            ['id' => 47,'name' => 'contact_through','guard_name' => 'web',],
            ['id' => 48,'name' => 'contact_through_create','guard_name' => 'web',],
            ['id' => 49,'name' => 'contact_through_edit','guard_name' => 'web',],
            ['id' => 50,'name' => 'contact_through_delete','guard_name' => 'web',],
            ['id' => 51,'name' => 'priority','guard_name' => 'web',],
            ['id' => 52,'name' => 'priority_create','guard_name' => 'web',],
            ['id' => 53,'name' => 'priority_edit','guard_name' => 'web',],
            ['id' => 54,'name' => 'priority_delete','guard_name' => 'web',],
            ['id' => 55,'name' => 'client_type','guard_name' => 'web',],
            ['id' => 56,'name' => 'client_type_create','guard_name' => 'web',],
            ['id' => 57,'name' => 'client_type_edit','guard_name' => 'web',],
            ['id' => 58,'name' => 'client_type_delete','guard_name' => 'web',],
            ['id' => 59,'name' => 'client_import','guard_name' => 'web',],
            ['id' => 60,'name' => 'client_export','guard_name' => 'web',],
            ['id' => 61,'name' => 'comment','guard_name' => 'web',],
            ['id' => 62,'name' => 'reminder','guard_name' => 'web',],
            ['id' => 63,'name' => 'client_update_view','guard_name' => 'web',],
            ['id' => 64,'name' => 'client_create','guard_name' => 'web',],
            ['id' => 65,'name' => 'client_update','guard_name' => 'web',],
            ['id' => 66,'name' => 'client_delete','guard_name' => 'web',],
            ['id' => 67,'name' => 'client_documents_view','guard_name' => 'web',],
            ['id' => 68,'name' => 'client_documents_create','guard_name' => 'web',],
            ['id' => 69,'name' => 'client_documents_delete','guard_name' => 'web',],
            ['id' => 70,'name' => 'client_identity_view','guard_name' => 'web',],
            ['id' => 71,'name' => 'client_identity_create','guard_name' => 'web',],
            ['id' => 72,'name' => 'client_identity_delete','guard_name' => 'web',],
            ['id' => 73,'name' => 'client_contact_person_view','guard_name' => 'web',],
            ['id' => 74,'name' => 'client_contact_person_create','guard_name' => 'web',],
            ['id' => 75,'name' => 'client_address_view','guard_name' => 'web',],
            ['id' => 76,'name' => 'client_address_create','guard_name' => 'web',],
            ['id' => 77,'name' => 'client_reference_view','guard_name' => 'web',],
            ['id' => 78,'name' => 'client_reference_create','guard_name' => 'web',],
            ['id' => 79,'name' => 'client_reference_delete','guard_name' => 'web',],

            ['id' => 80,'name' => 'client_bank_account','guard_name' => 'web',],
            ['id' => 81,'name' => 'client_bank_account_create','guard_name' => 'web',],
            ['id' => 82,'name' => 'client_bank_account_delete','guard_name' => 'web',],
            ['id' => 83,'name' => 'marketing_followup','guard_name' => 'web',],
            ['id' => 84,'name' => 'marketing_followup_create','guard_name' => 'web',],
            ['id' => 85,'name' => 'marketing_followup_edit','guard_name' => 'web',],
            ['id' => 86,'name' => 'marketing_followup_delete','guard_name' => 'web',],
            ['id' => 87,'name' => 'business_category','guard_name' => 'web',],
            ['id' => 88,'name' => 'business_category_create','guard_name' => 'web',],
            ['id' => 89,'name' => 'business_category_edit','guard_name' => 'web',],
            ['id' => 90,'name' => 'business_category_delete','guard_name' => 'web',],


        ]);

    }
}
