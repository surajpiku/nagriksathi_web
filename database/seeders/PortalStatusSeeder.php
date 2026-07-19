<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PortalStatus;

class PortalStatusSeeder extends Seeder
{
    public function run(): void
    {
        $portals = [
            ['portal_name' => 'PM-KISAN',            'portal_url' => 'https://pmkisan.gov.in',           'check_url' => 'https://pmkisan.gov.in'],
            ['portal_name' => 'Aadhaar / UIDAI',     'portal_url' => 'https://uidai.gov.in',             'check_url' => 'https://uidai.gov.in'],
            ['portal_name' => 'NSP Scholarships',    'portal_url' => 'https://scholarships.gov.in',      'check_url' => 'https://scholarships.gov.in'],
            ['portal_name' => 'PFMS Payments',       'portal_url' => 'https://pfms.nic.in',              'check_url' => 'https://pfms.nic.in'],
            ['portal_name' => 'UMANG App',           'portal_url' => 'https://umang.gov.in',             'check_url' => 'https://umang.gov.in'],
            ['portal_name' => 'DigiLocker',          'portal_url' => 'https://digilocker.gov.in',        'check_url' => 'https://digilocker.gov.in'],
            ['portal_name' => 'EPFO',                'portal_url' => 'https://epfindia.gov.in',          'check_url' => 'https://epfindia.gov.in'],
            ['portal_name' => 'PM Awas Yojana',      'portal_url' => 'https://pmayg.nic.in',             'check_url' => 'https://pmayg.nic.in'],
            ['portal_name' => 'MyScheme Portal',     'portal_url' => 'https://myscheme.gov.in',          'check_url' => 'https://myscheme.gov.in'],
            ['portal_name' => 'Passport Seva',       'portal_url' => 'https://passportindia.gov.in',     'check_url' => 'https://passportindia.gov.in'],
            ['portal_name' => 'Voter Services',      'portal_url' => 'https://voterportal.eci.gov.in',   'check_url' => 'https://voterportal.eci.gov.in'],
            ['portal_name' => 'Jan Aushadhi',        'portal_url' => 'https://janaushadhi.gov.in',       'check_url' => 'https://janaushadhi.gov.in'],
            ['portal_name' => 'PM Maandhan',         'portal_url' => 'https://maandhan.in',              'check_url' => 'https://maandhan.in'],
            ['portal_name' => 'Ayushman Bharat',     'portal_url' => 'https://pmjay.gov.in',             'check_url' => 'https://pmjay.gov.in'],
            ['portal_name' => 'National Portal',     'portal_url' => 'https://india.gov.in',             'check_url' => 'https://india.gov.in'],
            ['portal_name' => 'CSC Locator',         'portal_url' => 'https://locator.csc.gov.in',       'check_url' => 'https://locator.csc.gov.in'],
            ['portal_name' => 'MGNREGA',             'portal_url' => 'https://nrega.nic.in',             'check_url' => 'https://nrega.nic.in'],
            ['portal_name' => 'Startup India',       'portal_url' => 'https://startupindia.gov.in',      'check_url' => 'https://startupindia.gov.in'],
        ];

        foreach ($portals as $portal) {
            PortalStatus::updateOrCreate(
                ['portal_url' => $portal['portal_url']],
                array_merge($portal, ['status' => 'unknown', 'is_active' => true])
            );
        }

        $this->command->info('✅ ' . count($portals) . ' portals seeded!');
    }
}