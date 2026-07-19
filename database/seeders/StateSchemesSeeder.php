<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StateSchemesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding state schemes...');
        $this->seedBihar();
        $this->seedUP();
        $this->seedRajasthan();
        $this->seedMP();
        $this->seedMaharashtra();
    }

    private function addSchemes(array $schemes, string $stateCode): void
    {
        $added = 0;
        foreach ($schemes as $data) {
            $slug = Str::slug($data['name']);
            if (Scheme::where('slug', $slug)->orWhere('name', $data['name'])->exists()) continue;

            $category = SchemeCategory::where('name', 'like', '%' . explode(' ', $data['category'])[0] . '%')
                ->orWhere('name', $data['category'])->first() ?? SchemeCategory::first();

            Scheme::create([
                'name'                   => $data['name'],
                'hindi_name'             => $data['hindi_name'] ?? null,
                'slug'                   => $slug,
                'category_id'            => $category->id,
                'ministry'               => $data['ministry'],
                'description'            => $data['description'],
                'benefit_value'          => $data['benefit_value'] ?? 0,
                'benefit_type'           => $data['benefit_type'] ?? 'service',
                'portal_url'             => $data['portal_url'] ?? '',
                'helpline'               => $data['helpline'] ?? null,
                'eligibility_rules_json' => $data['eligibility'] ?? [],
                'is_central'             => false,
                'state'                  => $stateCode,
                'is_active'              => true,
            ]);
            $added++;
        }
        $this->command->info("  Added {$added} schemes for {$stateCode}");
    }

    private function seedBihar(): void
    {
        $this->command->info('Seeding Bihar schemes...');
        $this->addSchemes([
            ['name' => 'Bihar Student Credit Card Yojana', 'hindi_name' => 'बिहार स्टूडेंट क्रेडिट कार्ड योजना', 'category' => 'Education', 'ministry' => 'Education Department, Bihar', 'description' => 'Education loan up to Rs.4 lakh at 4% interest for higher education.', 'benefit_value' => 400000, 'benefit_type' => 'loan', 'portal_url' => 'https://www.7nishchay-yuvaupmission.bihar.gov.in', 'helpline' => '1800-3456-444', 'eligibility' => ['state' => 'BR', 'min_age' => 17, 'max_age' => 25]],
            ['name' => 'Mukhyamantri Kanya Utthan Yojana Bihar', 'hindi_name' => 'मुख्यमंत्री कन्या उत्थान योजना', 'category' => 'Women & Child', 'ministry' => 'Women Development Corporation, Bihar', 'description' => 'Rs.50,000 financial assistance for girl child from birth to graduation.', 'benefit_value' => 50000, 'benefit_type' => 'cash', 'portal_url' => 'https://medhasoft.bih.nic.in', 'helpline' => '0612-2506068', 'eligibility' => ['state' => 'BR', 'gender' => 'female']],
            ['name' => 'Mukhyamantri Medhavriti Yojana Bihar', 'hindi_name' => 'मुख्यमंत्री मेधावृत्ति योजना', 'category' => 'Education', 'ministry' => 'Education Department, Bihar', 'description' => 'Scholarship for SC/ST girls passing Class 12 with 1st or 2nd division.', 'benefit_value' => 15000, 'benefit_type' => 'scholarship', 'portal_url' => 'https://medhasoft.bih.nic.in', 'helpline' => '0612-2506068', 'eligibility' => ['state' => 'BR', 'gender' => 'female', 'caste_category' => ['sc', 'st']]],
            ['name' => 'Bihar Mukhyamantri Gram Parivahan Yojana', 'hindi_name' => 'मुख्यमंत्री ग्राम परिवहन योजना', 'category' => 'Financial Benefits', 'ministry' => 'Transport Department, Bihar', 'description' => 'Subsidy of Rs.1 lakh for purchase of 4-wheeler for public transport in rural areas.', 'benefit_value' => 100000, 'benefit_type' => 'subsidy', 'portal_url' => 'https://state.bihar.gov.in/transport', 'helpline' => '0612-2547234', 'eligibility' => ['state' => 'BR', 'min_age' => 21, 'max_age' => 45]],
            ['name' => 'Bihar Swayam Sahayata Bhatta Yojana', 'hindi_name' => 'स्वयं सहायता भत्ता योजना', 'category' => 'Employment & Skill', 'ministry' => 'Labour Department, Bihar', 'description' => 'Rs.1000/month allowance for unemployed youth aged 20-25 years for 2 years.', 'benefit_value' => 24000, 'benefit_type' => 'cash', 'portal_url' => 'https://www.7nishchay-yuvaupmission.bihar.gov.in', 'helpline' => '1800-3456-444', 'eligibility' => ['state' => 'BR', 'min_age' => 20, 'max_age' => 25]],
            ['name' => 'Bihar Mukhyamantri Udyami Yojana', 'hindi_name' => 'मुख्यमंत्री उद्यमी योजना', 'category' => 'Business & MSME', 'ministry' => 'Industries Department, Bihar', 'description' => 'Rs.10 lakh loan at 0% interest for SC/ST/OBC/Women/Youth to start business.', 'benefit_value' => 1000000, 'benefit_type' => 'loan', 'portal_url' => 'https://udyami.bihar.gov.in', 'helpline' => '18003456214', 'eligibility' => ['state' => 'BR', 'min_age' => 18, 'max_age' => 50]],
            ['name' => 'Bihar Vridha Pension Yojana', 'hindi_name' => 'बिहार वृद्धा पेंशन योजना', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Welfare Department, Bihar', 'description' => 'Monthly pension of Rs.400 for elderly citizens above 60 years.', 'benefit_value' => 4800, 'benefit_type' => 'pension', 'portal_url' => 'https://elabharthi.bih.nic.in', 'helpline' => '1800-345-6262', 'eligibility' => ['state' => 'BR', 'min_age' => 60]],
            ['name' => 'Laxmibai Social Security Pension Bihar', 'hindi_name' => 'लक्ष्मीबाई सामाजिक सुरक्षा पेंशन', 'category' => 'Women & Child', 'ministry' => 'Social Welfare Department, Bihar', 'description' => 'Monthly pension of Rs.400 for widows aged 18-39 years from BPL families.', 'benefit_value' => 4800, 'benefit_type' => 'pension', 'portal_url' => 'https://elabharthi.bih.nic.in', 'helpline' => '1800-345-6262', 'eligibility' => ['state' => 'BR', 'gender' => 'female', 'min_age' => 18, 'max_age' => 39, 'bpl_status' => true]],
            ['name' => 'Bihar Har Ghar Bijli Yojana', 'hindi_name' => 'हर घर बिजली योजना', 'category' => 'Financial Benefits', 'ministry' => 'Energy Department, Bihar', 'description' => 'Free electricity connection to every household in Bihar.', 'benefit_value' => 5000, 'benefit_type' => 'service', 'portal_url' => 'https://hargharbijli.bsphcl.co.in', 'helpline' => '1912', 'eligibility' => ['state' => 'BR']],
            ['name' => 'Bihar Agricultural Input Subsidy Scheme', 'hindi_name' => 'बिहार कृषि इनपुट अनुदान योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, Bihar', 'description' => 'Compensation for crop loss due to natural disasters at Rs.6800-13500 per acre.', 'benefit_value' => 13500, 'benefit_type' => 'subsidy', 'portal_url' => 'https://dbtagriculture.bihar.gov.in', 'helpline' => '0612-2233555', 'eligibility' => ['state' => 'BR', 'occupation' => 'farmer']],
            ['name' => 'Bihar Diesel Anudan Yojana', 'hindi_name' => 'बिहार डीजल अनुदान योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, Bihar', 'description' => 'Diesel subsidy of Rs.50 per litre for irrigation of crops in Bihar.', 'benefit_value' => 3000, 'benefit_type' => 'subsidy', 'portal_url' => 'https://dbtagriculture.bihar.gov.in', 'helpline' => '0612-2233555', 'eligibility' => ['state' => 'BR', 'occupation' => 'farmer']],
            ['name' => 'Bihar Rajya Fasal Sahayata Yojana', 'hindi_name' => 'बिहार राज्य फसल सहायता योजना', 'category' => 'Agriculture', 'ministry' => 'Cooperative Department, Bihar', 'description' => 'Crop insurance scheme — Rs.7500/hectare for up to 20% loss, Rs.10000 for more loss.', 'benefit_value' => 10000, 'benefit_type' => 'insurance', 'portal_url' => 'https://pacsonline.bih.nic.in/fsy/', 'helpline' => '18001800110', 'eligibility' => ['state' => 'BR', 'occupation' => 'farmer']],
            ['name' => 'Bihar Mukhyamantri Awas Yojana Rural', 'hindi_name' => 'मुख्यमंत्री आवास योजना ग्रामीण', 'category' => 'Housing', 'ministry' => 'Rural Development Department, Bihar', 'description' => 'Housing assistance of Rs.1.20 lakh for BPL families to construct house in rural Bihar.', 'benefit_value' => 120000, 'benefit_type' => 'grant', 'portal_url' => 'https://pmaymis.gov.in', 'helpline' => '1800-11-6446', 'eligibility' => ['state' => 'BR', 'bpl_status' => true]],
            ['name' => 'Bihar Free Coaching Scheme SC ST', 'hindi_name' => 'बिहार निःशुल्क कोचिंग योजना', 'category' => 'Education', 'ministry' => 'BC/EBC Welfare Department, Bihar', 'description' => 'Free coaching for competitive exams for SC/ST/BC/EBC students in Bihar.', 'benefit_value' => 30000, 'benefit_type' => 'service', 'portal_url' => 'https://bcebcwelfare.bih.nic.in', 'helpline' => '0612-2215406', 'eligibility' => ['state' => 'BR', 'caste_category' => ['sc', 'st', 'obc']]],
            ['name' => 'Bihar Mukhyamantri Balak Balika Protsahan', 'hindi_name' => 'मुख्यमंत्री बालक बालिका प्रोत्साहन', 'category' => 'Education', 'ministry' => 'Education Department, Bihar', 'description' => 'Cash reward of Rs.10,000 for students passing Class 10 with 1st division.', 'benefit_value' => 10000, 'benefit_type' => 'cash', 'portal_url' => 'https://medhasoft.bih.nic.in', 'helpline' => '0612-2506068', 'eligibility' => ['state' => 'BR']],
            ['name' => 'Bihar Kushal Yuva Program', 'hindi_name' => 'बिहार कुशल युवा प्रोग्राम', 'category' => 'Employment & Skill', 'ministry' => 'Labour Department, Bihar', 'description' => 'Free skill development training in soft skills, life skills and communication.', 'benefit_value' => 0, 'benefit_type' => 'training', 'portal_url' => 'https://skillmissionbihar.org', 'helpline' => '1800-3456-444', 'eligibility' => ['state' => 'BR', 'min_age' => 15, 'max_age' => 28]],
            ['name' => 'Bihar Post Matric Scholarship SC', 'hindi_name' => 'बिहार पोस्ट मैट्रिक छात्रवृत्ति SC', 'category' => 'Education', 'ministry' => 'SC/ST Welfare Department, Bihar', 'description' => 'Post matric scholarship for SC students studying in Class 11 and above.', 'benefit_value' => 15000, 'benefit_type' => 'scholarship', 'portal_url' => 'https://pmsonline.bih.nic.in', 'helpline' => '0612-2215406', 'eligibility' => ['state' => 'BR', 'caste_category' => ['sc'], 'max_income' => 250000]],
            ['name' => 'Bihar Mukhyamantri Mahila Udyami Yojana', 'hindi_name' => 'मुख्यमंत्री महिला उद्यमी योजना', 'category' => 'Women & Child', 'ministry' => 'Industries Department, Bihar', 'description' => 'Rs.10 lakh loan at 0% interest for women entrepreneurs to start business.', 'benefit_value' => 1000000, 'benefit_type' => 'loan', 'portal_url' => 'https://udyami.bihar.gov.in', 'helpline' => '18003456214', 'eligibility' => ['state' => 'BR', 'gender' => 'female', 'min_age' => 18, 'max_age' => 50]],
            ['name' => 'Bihar Jal Jeevan Hariyali Yojana', 'hindi_name' => 'जल जीवन हरियाली योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, Bihar', 'description' => 'Subsidy for water conservation, solar pump installation and tree plantation.', 'benefit_value' => 75500, 'benefit_type' => 'subsidy', 'portal_url' => 'https://dbtagriculture.bihar.gov.in', 'helpline' => '0612-2233555', 'eligibility' => ['state' => 'BR', 'occupation' => 'farmer']],
            ['name' => 'Bihar Disability Pension Yojana', 'hindi_name' => 'बिहार विकलांगता पेंशन योजना', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Welfare Department, Bihar', 'description' => 'Monthly pension of Rs.400 for persons with 40% or more disability.', 'benefit_value' => 4800, 'benefit_type' => 'pension', 'portal_url' => 'https://elabharthi.bih.nic.in', 'helpline' => '1800-345-6262', 'eligibility' => ['state' => 'BR']],
        ], 'BR');
    }

    private function seedUP(): void
    {
        $this->command->info('Seeding Uttar Pradesh schemes...');
        $this->addSchemes([
            ['name' => 'UP Mukhyamantri Kisan Durghatna Kalyan Yojana', 'hindi_name' => 'मुख्यमंत्री किसान दुर्घटना कल्याण योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, UP', 'description' => 'Compensation of Rs.5 lakh to farmer families in case of accidental death or disability.', 'benefit_value' => 500000, 'benefit_type' => 'insurance', 'portal_url' => 'https://upagripardarshi.gov.in', 'helpline' => '1800-180-5566', 'eligibility' => ['state' => 'UP', 'occupation' => 'farmer']],
            ['name' => 'UP Kanya Sumangala Yojana', 'hindi_name' => 'कन्या सुमंगला योजना', 'category' => 'Women & Child', 'ministry' => 'Women & Child Development, UP', 'description' => 'Rs.15,000 financial assistance for girl child in 6 installments from birth to graduation.', 'benefit_value' => 15000, 'benefit_type' => 'cash', 'portal_url' => 'https://mksy.up.gov.in', 'helpline' => '1800-419-0001', 'eligibility' => ['state' => 'UP', 'gender' => 'female', 'max_income' => 300000]],
            ['name' => 'UP Free Laptop Yojana', 'hindi_name' => 'UP निःशुल्क लैपटॉप योजना', 'category' => 'Education', 'ministry' => 'Education Department, UP', 'description' => 'Free laptop for students passing Class 10 and 12 with good marks from UP Board.', 'benefit_value' => 15000, 'benefit_type' => 'grant', 'portal_url' => 'https://upcmo.up.nic.in', 'helpline' => '1800-180-5566', 'eligibility' => ['state' => 'UP']],
            ['name' => 'UP Vridha Pension Yojana', 'hindi_name' => 'UP वृद्धावस्था पेंशन योजना', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Welfare Department, UP', 'description' => 'Monthly pension of Rs.500 for elderly citizens above 60 years in UP.', 'benefit_value' => 6000, 'benefit_type' => 'pension', 'portal_url' => 'https://sspy-up.gov.in', 'helpline' => '18004190001', 'eligibility' => ['state' => 'UP', 'min_age' => 60]],
            ['name' => 'UP Nirashrit Mahila Pension', 'hindi_name' => 'UP निराश्रित महिला पेंशन', 'category' => 'Women & Child', 'ministry' => 'Social Welfare Department, UP', 'description' => 'Monthly pension of Rs.500 for widow/destitute women in UP.', 'benefit_value' => 6000, 'benefit_type' => 'pension', 'portal_url' => 'https://sspy-up.gov.in', 'helpline' => '18004190001', 'eligibility' => ['state' => 'UP', 'gender' => 'female']],
            ['name' => 'UP Divyang Pension Yojana', 'hindi_name' => 'UP दिव्यांग पेंशन योजना', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Welfare Department, UP', 'description' => 'Monthly pension of Rs.500 for persons with disability in UP.', 'benefit_value' => 6000, 'benefit_type' => 'pension', 'portal_url' => 'https://sspy-up.gov.in', 'helpline' => '18004190001', 'eligibility' => ['state' => 'UP']],
            ['name' => 'UP Mukhyamantri Swarozgar Yojana', 'hindi_name' => 'मुख्यमंत्री स्वरोजगार योजना', 'category' => 'Business & MSME', 'ministry' => 'MSME Department, UP', 'description' => 'Loan up to Rs.25 lakh at subsidized rate for self-employment in UP.', 'benefit_value' => 2500000, 'benefit_type' => 'loan', 'portal_url' => 'https://diupmsme.upsdc.gov.in', 'helpline' => '1800-180-5566', 'eligibility' => ['state' => 'UP', 'min_age' => 18, 'max_age' => 40]],
            ['name' => 'UP Mahila Samarthya Yojana', 'hindi_name' => 'UP महिला सामर्थ्य योजना', 'category' => 'Women & Child', 'ministry' => 'Women & Child Development, UP', 'description' => 'Skill development and self-employment for women in UP through local industry linkage.', 'benefit_value' => 0, 'benefit_type' => 'training', 'portal_url' => 'https://mahilakalyan.up.nic.in', 'helpline' => '1800-180-5566', 'eligibility' => ['state' => 'UP', 'gender' => 'female']],
            ['name' => 'UP Kisan Karj Rahat Yojana', 'hindi_name' => 'UP किसान कर्ज राहत योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, UP', 'description' => 'Loan waiver up to Rs.1 lakh for small and marginal farmers in UP.', 'benefit_value' => 100000, 'benefit_type' => 'subsidy', 'portal_url' => 'https://upkisankarjrahat.upsdc.gov.in', 'helpline' => '1800-180-5566', 'eligibility' => ['state' => 'UP', 'occupation' => 'farmer']],
            ['name' => 'UP Scholarship Scheme OBC', 'hindi_name' => 'UP छात्रवृत्ति योजना OBC', 'category' => 'Education', 'ministry' => 'Backward Class Welfare, UP', 'description' => 'Pre and post matric scholarship for OBC students in UP.', 'benefit_value' => 12000, 'benefit_type' => 'scholarship', 'portal_url' => 'https://scholarship.up.gov.in', 'helpline' => '18001805131', 'eligibility' => ['state' => 'UP', 'caste_category' => ['obc'], 'max_income' => 200000]],
        ], 'UP');
    }

    private function seedRajasthan(): void
    {
        $this->command->info('Seeding Rajasthan schemes...');
        $this->addSchemes([
            ['name' => 'Rajasthan Palanhar Yojana', 'hindi_name' => 'पालनहार योजना', 'category' => 'Women & Child', 'ministry' => 'Social Justice Department, Rajasthan', 'description' => 'Monthly assistance of Rs.1500 for orphan children up to age 18 in Rajasthan.', 'benefit_value' => 18000, 'benefit_type' => 'cash', 'portal_url' => 'https://sje.rajasthan.gov.in', 'helpline' => '1800-180-6127', 'eligibility' => ['state' => 'RJ', 'max_age' => 18]],
            ['name' => 'Rajasthan Mukhyamantri Rajshri Yojana', 'hindi_name' => 'मुख्यमंत्री राजश्री योजना', 'category' => 'Women & Child', 'ministry' => 'Women & Child Development, Rajasthan', 'description' => 'Rs.50,000 financial benefit for girl child in 6 installments from birth to Class 12.', 'benefit_value' => 50000, 'benefit_type' => 'cash', 'portal_url' => 'https://wcd.rajasthan.gov.in', 'helpline' => '181', 'eligibility' => ['state' => 'RJ', 'gender' => 'female']],
            ['name' => 'Rajasthan Shubh Shakti Yojana', 'hindi_name' => 'शुभशक्ति योजना', 'category' => 'Women & Child', 'ministry' => 'Labour Department, Rajasthan', 'description' => 'Rs.55,000 assistance for marriage/self-employment of daughters of construction workers.', 'benefit_value' => 55000, 'benefit_type' => 'cash', 'portal_url' => 'https://labour.rajasthan.gov.in', 'helpline' => '1800-180-6127', 'eligibility' => ['state' => 'RJ', 'gender' => 'female']],
            ['name' => 'Rajasthan Mukhyamantri Krishak Sathi Yojana', 'hindi_name' => 'मुख्यमंत्री कृषक साथी योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, Rajasthan', 'description' => 'Compensation of Rs.5000-2 lakh to farmers for accidental death/injury during farming.', 'benefit_value' => 200000, 'benefit_type' => 'insurance', 'portal_url' => 'https://agriculture.rajasthan.gov.in', 'helpline' => '0141-2227849', 'eligibility' => ['state' => 'RJ', 'occupation' => 'farmer']],
            ['name' => 'Rajasthan Social Security Pension', 'hindi_name' => 'राजस्थान सामाजिक सुरक्षा पेंशन', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Justice Department, Rajasthan', 'description' => 'Monthly pension of Rs.750-1000 for elderly, widow and disabled persons in Rajasthan.', 'benefit_value' => 12000, 'benefit_type' => 'pension', 'portal_url' => 'https://ssp.rajasthan.gov.in', 'helpline' => '1800-180-6127', 'eligibility' => ['state' => 'RJ', 'min_age' => 55]],
            ['name' => 'Rajasthan Free Scooty Yojana', 'hindi_name' => 'राजस्थान निःशुल्क स्कूटी योजना', 'category' => 'Education', 'ministry' => 'Education Department, Rajasthan', 'description' => 'Free scooty for meritorious girl students passing Class 12 from Rajasthan Board.', 'benefit_value' => 50000, 'benefit_type' => 'grant', 'portal_url' => 'https://hte.rajasthan.gov.in', 'helpline' => '0141-2706106', 'eligibility' => ['state' => 'RJ', 'gender' => 'female']],
        ], 'RJ');
    }

    private function seedMP(): void
    {
        $this->command->info('Seeding Madhya Pradesh schemes...');
        $this->addSchemes([
            ['name' => 'MP Laadli Laxmi Yojana', 'hindi_name' => 'लाडली लक्ष्मी योजना', 'category' => 'Women & Child', 'ministry' => 'Women & Child Development, MP', 'description' => 'Rs.1.43 lakh for girl child in MP — investments made over 5 years, paid at key milestones.', 'benefit_value' => 143000, 'benefit_type' => 'cash', 'portal_url' => 'https://ladlilaxmi.mp.gov.in', 'helpline' => '07879804079', 'eligibility' => ['state' => 'MP', 'gender' => 'female']],
            ['name' => 'MP Mukhyamantri Kisan Kalyan Yojana', 'hindi_name' => 'मुख्यमंत्री किसान कल्याण योजना', 'category' => 'Agriculture', 'ministry' => 'Agriculture Department, MP', 'description' => 'Additional Rs.4000 per year to PM Kisan beneficiaries in Madhya Pradesh.', 'benefit_value' => 4000, 'benefit_type' => 'cash', 'portal_url' => 'https://saara.mp.gov.in', 'helpline' => '0755-2700803', 'eligibility' => ['state' => 'MP', 'occupation' => 'farmer']],
            ['name' => 'MP Gaon Ki Beti Yojana', 'hindi_name' => 'गांव की बेटी योजना', 'category' => 'Education', 'ministry' => 'Higher Education Department, MP', 'description' => 'Rs.500/month scholarship for 10 months for girls from rural areas scoring 60%+ in Class 12.', 'benefit_value' => 5000, 'benefit_type' => 'scholarship', 'portal_url' => 'https://scholarshipportal.mp.nic.in', 'helpline' => '0755-2661914', 'eligibility' => ['state' => 'MP', 'gender' => 'female']],
            ['name' => 'MP Mukhyamantri Yuva Udyami Yojana', 'hindi_name' => 'मुख्यमंत्री युवा उद्यमी योजना', 'category' => 'Business & MSME', 'ministry' => 'MSME Department, MP', 'description' => 'Loan up to Rs.10 lakh at 5% interest for youth entrepreneurs in MP.', 'benefit_value' => 1000000, 'benefit_type' => 'loan', 'portal_url' => 'https://msme.mponline.gov.in', 'helpline' => '0755-6720200', 'eligibility' => ['state' => 'MP', 'min_age' => 18, 'max_age' => 40]],
            ['name' => 'MP Social Security Pension Scheme', 'hindi_name' => 'MP सामाजिक सुरक्षा पेंशन', 'category' => 'Elderly & Disabled', 'ministry' => 'Social Justice Department, MP', 'description' => 'Monthly pension of Rs.300-600 for elderly, widow and disabled persons in MP.', 'benefit_value' => 7200, 'benefit_type' => 'pension', 'portal_url' => 'https://socialjustice.mp.gov.in', 'helpline' => '0755-2556916', 'eligibility' => ['state' => 'MP', 'min_age' => 60]],
        ], 'MP');
    }

    private function seedMaharashtra(): void
    {
        $this->command->info('Seeding Maharashtra schemes...');
        $this->addSchemes([
            ['name' => 'Maharashtra Lek Ladki Yojana', 'hindi_name' => 'लेक लाडकी योजना', 'category' => 'Women & Child', 'ministry' => 'Women & Child Development, Maharashtra', 'description' => 'Financial assistance of Rs.1.01 lakh for girl child from birth to age 18 in Maharashtra.', 'benefit_value' => 101000, 'benefit_type' => 'cash', 'portal_url' => 'https://womenchild.maharashtra.gov.in', 'helpline' => '1800-233-4385', 'eligibility' => ['state' => 'MH', 'gender' => 'female']],
            ['name' => 'Maharashtra Mahatma Jyotirao Phule Jan Arogya Yojana', 'hindi_name' => 'महात्मा ज्योतिराव फुले जन आरोग्य योजना', 'category' => 'Health Services', 'ministry' => 'Health Department, Maharashtra', 'description' => 'Free medical treatment up to Rs.1.5 lakh for below poverty line families in Maharashtra.', 'benefit_value' => 150000, 'benefit_type' => 'insurance', 'portal_url' => 'https://www.jeevandayee.gov.in', 'helpline' => '155388', 'eligibility' => ['state' => 'MH', 'bpl_status' => true]],
            ['name' => 'Maharashtra Swadhaar Gruh Yojana', 'hindi_name' => 'स्वाधार गृह योजना', 'category' => 'Women & Child', 'ministry' => 'Social Justice Department, Maharashtra', 'description' => 'Rs.51,000 annual assistance for SC students studying outside their home district in Maharashtra.', 'benefit_value' => 51000, 'benefit_type' => 'scholarship', 'portal_url' => 'https://mahaeschol.maharashtra.gov.in', 'helpline' => '1800-120-8040', 'eligibility' => ['state' => 'MH', 'caste_category' => ['sc']]],
            ['name' => 'Maharashtra Anna Bhau Sathe Vikas Yojana', 'hindi_name' => 'अण्णाभाऊ साठे विकास महामंडळ', 'category' => 'Financial Benefits', 'ministry' => 'Social Justice Department, Maharashtra', 'description' => 'Loan up to Rs.50,000 at low interest for Matang community members in Maharashtra.', 'benefit_value' => 50000, 'benefit_type' => 'loan', 'portal_url' => 'https://mahaschemes.maharashtra.gov.in', 'helpline' => '1800-120-8040', 'eligibility' => ['state' => 'MH']],
            ['name' => 'Maharashtra Ramai Awas Yojana', 'hindi_name' => 'रमाई आवास योजना', 'category' => 'Housing', 'ministry' => 'Social Justice Department, Maharashtra', 'description' => 'Housing subsidy for SC/Nav-Buddha families in Maharashtra for house construction.', 'benefit_value' => 250000, 'benefit_type' => 'grant', 'portal_url' => 'https://mahaschemes.maharashtra.gov.in', 'helpline' => '1800-120-8040', 'eligibility' => ['state' => 'MH', 'caste_category' => ['sc']]],
        ], 'MH');
    }
}