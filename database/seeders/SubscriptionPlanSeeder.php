<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // ── CITIZEN PLANS ─────────────────────────────────────
        SubscriptionPlan::updateOrCreate(['slug' => 'free'], [
            'name'         => 'Free Sathi',
            'hindi_name'   => 'फ्री साथी',
            'type'         => 'citizen',
            'price_monthly'=> 0,
            'price_yearly' => 0,
            'sort_order'   => 1,
            'is_active'    => true,
            'is_popular'   => false,
            'features_json'=> [
                'Scheme matching — Unlimited',
                'Smart Search — Unlimited',
                'Sathi AI — 20 messages/month',
                'Document Vault — 5 documents',
                'Family Members — 2 members',
                'Alerts & Deadlines — Always free',
                'Grievance Filing — Always free',
            ],
            'limits_json'  => [
                'ai_messages'    => 20,
                'documents'      => 5,
                'family_members' => 2,
                'ocr_extractions'=> 2,
                'form_fills'     => 0,
                'voice_minutes'  => 5,
                'human_sathi'    => 0,
            ],
        ]);

        SubscriptionPlan::updateOrCreate(['slug' => 'sathi-plus'], [
            'name'                  => 'Sathi Plus',
            'hindi_name'            => 'साथी प्लस',
            'type'                  => 'citizen',
            'price_monthly'         => 99,
            'price_yearly'          => 999,
            'razorpay_plan_monthly' => env('RAZORPAY_PLAN_PLUS_MONTHLY'),
            'razorpay_plan_yearly'  => env('RAZORPAY_PLAN_PLUS_YEARLY'),
            'sort_order'            => 2,
            'is_active'             => true,
            'is_popular'            => true,
            'features_json'         => [
                'Everything in Free, plus:',
                'Sathi AI — 200 messages/month',
                'Document Vault — 30 documents',
                'Family Members — 5 members',
                'OCR Extraction — 15/month',
                'AI Form Filling — 10 forms/month',
                'Human Sathi — 2 sessions/month',
                'Voice Input — 60 min/month',
                'WhatsApp Sathi Access',
                'Priority Search Results',
            ],
            'limits_json'  => [
                'ai_messages'    => 200,
                'documents'      => 30,
                'family_members' => 5,
                'ocr_extractions'=> 15,
                'form_fills'     => 10,
                'voice_minutes'  => 60,
                'human_sathi'    => 2,
            ],
        ]);

        SubscriptionPlan::updateOrCreate(['slug' => 'sathi-pro'], [
            'name'                  => 'Sathi Pro',
            'hindi_name'            => 'साथी प्रो',
            'type'                  => 'citizen',
            'price_monthly'         => 299,
            'price_yearly'          => 2999,
            'razorpay_plan_monthly' => env('RAZORPAY_PLAN_PRO_MONTHLY'),
            'razorpay_plan_yearly'  => env('RAZORPAY_PLAN_PRO_YEARLY'),
            'sort_order'            => 3,
            'is_active'             => true,
            'is_popular'            => false,
            'features_json'         => [
                'Everything in Plus, plus:',
                'Sathi AI — Unlimited',
                'Document Vault — Unlimited',
                'Family Members — Unlimited',
                'OCR Extraction — Unlimited',
                'AI Form Filling — Unlimited',
                'Human Sathi — Unlimited',
                'Specialist Access',
                'Home Visit Support',
                'Dedicated Relationship Manager',
            ],
            'limits_json'  => [
                'ai_messages'    => -1, // unlimited
                'documents'      => -1,
                'family_members' => -1,
                'ocr_extractions'=> -1,
                'form_fills'     => -1,
                'voice_minutes'  => -1,
                'human_sathi'    => -1,
            ],
        ]);

        // ── Seva Mitra PLANS ───────────────────────────────────
        SubscriptionPlan::updateOrCreate(['slug' => 'csc-free'], [
            'name'         => 'Seva Mitra Free',
            'hindi_name'   => 'CSC फ्री',
            'type'         => 'seva_mitra',
            'price_monthly'=> 0,
            'price_yearly' => 0,
            'sort_order'   => 1,
            'is_active'    => true,
            'is_popular'   => false,
            'features_json'=> [
                'Queue Manager — Basic',
                'Portal Status — All portals',
                'Document Vault — 10 customers',
                'Earnings tracking',
                'Task management',
            ],
            'limits_json'  => [
                'monthly_tasks'    => 50,
                'vault_customers'  => 10,
                'photo_processing' => 10,
                'ocr_extractions'  => 5,
                'form_fills'       => 5,
                'pdf_merges'       => 5,
            ],
        ]);

        SubscriptionPlan::updateOrCreate(['slug' => 'csc-basic'], [
            'name'                  => 'Sathi Seva Mitra Basic',
            'hindi_name'            => 'साथी CSC बेसिक',
            'type'                  => 'seva_mitra',
            'price_monthly'         => 199,
            'price_yearly'          => 1999,
            'razorpay_plan_monthly' => env('RAZORPAY_PLAN_CSC_BASIC_MONTHLY'),
            'razorpay_plan_yearly'  => env('RAZORPAY_PLAN_CSC_BASIC_YEARLY'),
            'sort_order'            => 2,
            'is_active'             => true,
            'is_popular'            => true,
            'features_json'         => [
                'Everything in Free, plus:',
                'Unlimited monthly tasks',
                'Unlimited customer vault',
                'Photo Processor — Unlimited',
                'Passport Photo Maker — Unlimited',
                'OCR Extractor — 50/month',
                'PDF Creator — 50/month',
                'Form Auto-Filler — 20/month',
                'Priority task allocation',
                'Monthly earnings report',
            ],
            'limits_json'  => [
                'monthly_tasks'    => -1,
                'vault_customers'  => -1,
                'photo_processing' => -1,
                'ocr_extractions'  => 50,
                'form_fills'       => 20,
                'pdf_merges'       => 50,
            ],
        ]);

        SubscriptionPlan::updateOrCreate(['slug' => 'csc-pro'], [
            'name'                  => 'Sathi Seva Mitra Pro',
            'hindi_name'            => 'साथी CSC प्रो',
            'type'                  => 'seva_mitra',
            'price_monthly'         => 499,
            'price_yearly'          => 4999,
            'razorpay_plan_monthly' => env('RAZORPAY_PLAN_CSC_PRO_MONTHLY'),
            'razorpay_plan_yearly'  => env('RAZORPAY_PLAN_CSC_PRO_YEARLY'),
            'sort_order'            => 3,
            'is_active'             => true,
            'is_popular'            => false,
            'features_json'         => [
                'Everything in Basic, plus:',
                'OCR Extractor — Unlimited',
                'Form Auto-Filler — Unlimited',
                'PDF Creator — Unlimited',
                'Digital Signature — Unlimited',
                'AI Form Auto-Filler — Unlimited',
                'Dedicated support',
                'Business analytics dashboard',
                'Custom CSC branding',
                'WhatsApp business integration',
            ],
            'limits_json'  => [
                'monthly_tasks'    => -1,
                'vault_customers'  => -1,
                'photo_processing' => -1,
                'ocr_extractions'  => -1,
                'form_fills'       => -1,
                'pdf_merges'       => -1,
            ],
        ]);

        $this->command->info('✅ 6 subscription plans seeded! (3 citizen + 3 CSC)');
    }
}
