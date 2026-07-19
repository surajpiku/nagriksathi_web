<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StateMaster;
use App\Models\DistrictMaster;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['name' => 'Andhra Pradesh',    'hindi_name' => 'आंध्र प्रदेश',    'code' => 'AP', 'type' => 'state',  'capital' => 'Amaravati'],
            ['name' => 'Arunachal Pradesh', 'hindi_name' => 'अरुणाचल प्रदेश',  'code' => 'AR', 'type' => 'state',  'capital' => 'Itanagar'],
            ['name' => 'Assam',             'hindi_name' => 'असम',              'code' => 'AS', 'type' => 'state',  'capital' => 'Dispur'],
            ['name' => 'Bihar',             'hindi_name' => 'बिहार',            'code' => 'BR', 'type' => 'state',  'capital' => 'Patna'],
            ['name' => 'Chhattisgarh',      'hindi_name' => 'छत्तीसगढ़',        'code' => 'CG', 'type' => 'state',  'capital' => 'Raipur'],
            ['name' => 'Goa',               'hindi_name' => 'गोवा',             'code' => 'GA', 'type' => 'state',  'capital' => 'Panaji'],
            ['name' => 'Gujarat',           'hindi_name' => 'गुजरात',           'code' => 'GJ', 'type' => 'state',  'capital' => 'Gandhinagar'],
            ['name' => 'Haryana',           'hindi_name' => 'हरियाणा',          'code' => 'HR', 'type' => 'state',  'capital' => 'Chandigarh'],
            ['name' => 'Himachal Pradesh',  'hindi_name' => 'हिमाचल प्रदेश',   'code' => 'HP', 'type' => 'state',  'capital' => 'Shimla'],
            ['name' => 'Jharkhand',         'hindi_name' => 'झारखंड',           'code' => 'JH', 'type' => 'state',  'capital' => 'Ranchi'],
            ['name' => 'Karnataka',         'hindi_name' => 'कर्नाटक',          'code' => 'KA', 'type' => 'state',  'capital' => 'Bengaluru'],
            ['name' => 'Kerala',            'hindi_name' => 'केरल',             'code' => 'KL', 'type' => 'state',  'capital' => 'Thiruvananthapuram'],
            ['name' => 'Madhya Pradesh',    'hindi_name' => 'मध्य प्रदेश',      'code' => 'MP', 'type' => 'state',  'capital' => 'Bhopal'],
            ['name' => 'Maharashtra',       'hindi_name' => 'महाराष्ट्र',       'code' => 'MH', 'type' => 'state',  'capital' => 'Mumbai'],
            ['name' => 'Manipur',           'hindi_name' => 'मणिपुर',           'code' => 'MN', 'type' => 'state',  'capital' => 'Imphal'],
            ['name' => 'Meghalaya',         'hindi_name' => 'मेघालय',           'code' => 'ML', 'type' => 'state',  'capital' => 'Shillong'],
            ['name' => 'Mizoram',           'hindi_name' => 'मिजोरम',           'code' => 'MZ', 'type' => 'state',  'capital' => 'Aizawl'],
            ['name' => 'Nagaland',          'hindi_name' => 'नागालैंड',         'code' => 'NL', 'type' => 'state',  'capital' => 'Kohima'],
            ['name' => 'Odisha',            'hindi_name' => 'ओडिशा',            'code' => 'OD', 'type' => 'state',  'capital' => 'Bhubaneswar'],
            ['name' => 'Punjab',            'hindi_name' => 'पंजाब',            'code' => 'PB', 'type' => 'state',  'capital' => 'Chandigarh'],
            ['name' => 'Rajasthan',         'hindi_name' => 'राजस्थान',         'code' => 'RJ', 'type' => 'state',  'capital' => 'Jaipur'],
            ['name' => 'Sikkim',            'hindi_name' => 'सिक्किम',          'code' => 'SK', 'type' => 'state',  'capital' => 'Gangtok'],
            ['name' => 'Tamil Nadu',        'hindi_name' => 'तमिल नाडु',        'code' => 'TN', 'type' => 'state',  'capital' => 'Chennai'],
            ['name' => 'Telangana',         'hindi_name' => 'तेलंगाना',         'code' => 'TS', 'type' => 'state',  'capital' => 'Hyderabad'],
            ['name' => 'Tripura',           'hindi_name' => 'त्रिपुरा',         'code' => 'TR', 'type' => 'state',  'capital' => 'Agartala'],
            ['name' => 'Uttar Pradesh',     'hindi_name' => 'उत्तर प्रदेश',     'code' => 'UP', 'type' => 'state',  'capital' => 'Lucknow'],
            ['name' => 'Uttarakhand',       'hindi_name' => 'उत्तराखंड',        'code' => 'UK', 'type' => 'state',  'capital' => 'Dehradun'],
            ['name' => 'West Bengal',       'hindi_name' => 'पश्चिम बंगाल',     'code' => 'WB', 'type' => 'state',  'capital' => 'Kolkata'],
            // Union Territories
            ['name' => 'Delhi',             'hindi_name' => 'दिल्ली',           'code' => 'DL', 'type' => 'ut',    'capital' => 'New Delhi'],
            ['name' => 'Jammu & Kashmir',   'hindi_name' => 'जम्मू और कश्मीर', 'code' => 'JK', 'type' => 'ut',    'capital' => 'Srinagar'],
            ['name' => 'Ladakh',            'hindi_name' => 'लद्दाख',           'code' => 'LA', 'type' => 'ut',    'capital' => 'Leh'],
            ['name' => 'Chandigarh',        'hindi_name' => 'चंडीगढ़',          'code' => 'CH', 'type' => 'ut',    'capital' => 'Chandigarh'],
            ['name' => 'Puducherry',        'hindi_name' => 'पुदुच्चेरी',       'code' => 'PY', 'type' => 'ut',    'capital' => 'Puducherry'],
            ['name' => 'Andaman & Nicobar', 'hindi_name' => 'अंडमान और निकोबार','code' => 'AN', 'type' => 'ut',    'capital' => 'Port Blair'],
            ['name' => 'Lakshadweep',       'hindi_name' => 'लक्षद्वीप',        'code' => 'LD', 'type' => 'ut',    'capital' => 'Kavaratti'],
            ['name' => 'Dadra & Nagar Haveli', 'hindi_name' => 'दादरा और नगर हवेली', 'code' => 'DN', 'type' => 'ut', 'capital' => 'Daman'],
        ];

        foreach ($states as $state) {
            StateMaster::updateOrCreate(['code' => $state['code']], array_merge($state, ['is_active' => true]));
        }
        $this->command->info('✅ ' . count($states) . ' states/UTs seeded!');

        // Seed key districts for priority states
        $districts = [
            // Bihar
            'BR' => [
                ['name' => 'Patna',        'hindi_name' => 'पटना'],
                ['name' => 'Gaya',         'hindi_name' => 'गया'],
                ['name' => 'Muzaffarpur',  'hindi_name' => 'मुजफ्फरपुर'],
                ['name' => 'Bhagalpur',    'hindi_name' => 'भागलपुर'],
                ['name' => 'Darbhanga',    'hindi_name' => 'दरभंगा'],
                ['name' => 'Nalanda',      'hindi_name' => 'नालंदा'],
                ['name' => 'Samastipur',   'hindi_name' => 'समस्तीपुर'],
                ['name' => 'Vaishali',     'hindi_name' => 'वैशाली'],
                ['name' => 'Saran',        'hindi_name' => 'सारण'],
                ['name' => 'Sitamarhi',    'hindi_name' => 'सीतामढ़ी'],
                ['name' => 'Madhubani',    'hindi_name' => 'मधुबनी'],
                ['name' => 'Purnia',       'hindi_name' => 'पूर्णिया'],
                ['name' => 'Araria',       'hindi_name' => 'अररिया'],
                ['name' => 'Begusarai',    'hindi_name' => 'बेगूसराय'],
                ['name' => 'Rohtas',       'hindi_name' => 'रोहतास'],
            ],
            // Uttar Pradesh
            'UP' => [
                ['name' => 'Lucknow',      'hindi_name' => 'लखनऊ'],
                ['name' => 'Kanpur',       'hindi_name' => 'कानपुर'],
                ['name' => 'Agra',         'hindi_name' => 'आगरा'],
                ['name' => 'Varanasi',     'hindi_name' => 'वाराणसी'],
                ['name' => 'Allahabad',    'hindi_name' => 'प्रयागराज'],
                ['name' => 'Meerut',       'hindi_name' => 'मेरठ'],
                ['name' => 'Gorakhpur',    'hindi_name' => 'गोरखपुर'],
                ['name' => 'Mathura',      'hindi_name' => 'मथुरा'],
                ['name' => 'Bareilly',     'hindi_name' => 'बरेली'],
                ['name' => 'Aligarh',      'hindi_name' => 'अलीगढ़'],
            ],
            // Rajasthan
            'RJ' => [
                ['name' => 'Jaipur',       'hindi_name' => 'जयपुर'],
                ['name' => 'Jodhpur',      'hindi_name' => 'जोधपुर'],
                ['name' => 'Udaipur',      'hindi_name' => 'उदयपुर'],
                ['name' => 'Kota',         'hindi_name' => 'कोटा'],
                ['name' => 'Ajmer',        'hindi_name' => 'अजमेर'],
                ['name' => 'Bikaner',      'hindi_name' => 'बीकानेर'],
            ],
            // Maharashtra
            'MH' => [
                ['name' => 'Mumbai',       'hindi_name' => 'मुंबई'],
                ['name' => 'Pune',         'hindi_name' => 'पुणे'],
                ['name' => 'Nagpur',       'hindi_name' => 'नागपुर'],
                ['name' => 'Nashik',       'hindi_name' => 'नासिक'],
                ['name' => 'Aurangabad',   'hindi_name' => 'औरंगाबाद'],
                ['name' => 'Thane',        'hindi_name' => 'ठाणे'],
            ],
            // Madhya Pradesh
            'MP' => [
                ['name' => 'Bhopal',       'hindi_name' => 'भोपाल'],
                ['name' => 'Indore',       'hindi_name' => 'इंदौर'],
                ['name' => 'Gwalior',      'hindi_name' => 'ग्वालियर'],
                ['name' => 'Jabalpur',     'hindi_name' => 'जबलपुर'],
                ['name' => 'Ujjain',       'hindi_name' => 'उज्जैन'],
            ],
            // West Bengal
            'WB' => [
                ['name' => 'Kolkata',      'hindi_name' => 'कोलकाता'],
                ['name' => 'Howrah',       'hindi_name' => 'हावड़ा'],
                ['name' => 'Darjeeling',   'hindi_name' => 'दार्जिलिंग'],
                ['name' => 'Siliguri',     'hindi_name' => 'सिलीगुड़ी'],
                ['name' => 'Asansol',      'hindi_name' => 'आसनसोल'],
            ],
            // Delhi
            'DL' => [
                ['name' => 'Central Delhi',   'hindi_name' => 'मध्य दिल्ली'],
                ['name' => 'North Delhi',     'hindi_name' => 'उत्तर दिल्ली'],
                ['name' => 'South Delhi',     'hindi_name' => 'दक्षिण दिल्ली'],
                ['name' => 'East Delhi',      'hindi_name' => 'पूर्व दिल्ली'],
                ['name' => 'West Delhi',      'hindi_name' => 'पश्चिम दिल्ली'],
                ['name' => 'New Delhi',       'hindi_name' => 'नई दिल्ली'],
            ],
            // Gujarat
            'GJ' => [
                ['name' => 'Ahmedabad',    'hindi_name' => 'अहमदाबाद'],
                ['name' => 'Surat',        'hindi_name' => 'सूरत'],
                ['name' => 'Vadodara',     'hindi_name' => 'वडोदरा'],
                ['name' => 'Rajkot',       'hindi_name' => 'राजकोट'],
                ['name' => 'Gandhinagar',  'hindi_name' => 'गांधीनगर'],
            ],
            // Tamil Nadu
            'TN' => [
                ['name' => 'Chennai',      'hindi_name' => 'चेन्नई'],
                ['name' => 'Coimbatore',   'hindi_name' => 'कोयंबटूर'],
                ['name' => 'Madurai',      'hindi_name' => 'मदुरई'],
                ['name' => 'Salem',        'hindi_name' => 'सेलम'],
                ['name' => 'Tiruchirappalli', 'hindi_name' => 'तिरुचिरापल्ली'],
            ],
            // Karnataka
            'KA' => [
                ['name' => 'Bengaluru',    'hindi_name' => 'बेंगलुरु'],
                ['name' => 'Mysuru',       'hindi_name' => 'मैसूर'],
                ['name' => 'Hubli',        'hindi_name' => 'हुबली'],
                ['name' => 'Mangaluru',    'hindi_name' => 'मंगलुरु'],
                ['name' => 'Belagavi',     'hindi_name' => 'बेलगावी'],
            ],
            // Chandigarh
'CH' => [
    ['name' => 'Chandigarh', 'hindi_name' => 'चंडीगढ़'],
],

// Punjab
'PB' => [
    ['name' => 'Amritsar',   'hindi_name' => 'अमृतसर'],
    ['name' => 'Ludhiana',   'hindi_name' => 'लुधियाना'],
    ['name' => 'Jalandhar',  'hindi_name' => 'जालंधर'],
    ['name' => 'Patiala',    'hindi_name' => 'पटियाला'],
    ['name' => 'Bathinda',   'hindi_name' => 'बठिंडा'],
],

// Haryana
'HR' => [
    ['name' => 'Gurugram',   'hindi_name' => 'गुरुग्राम'],
    ['name' => 'Faridabad',  'hindi_name' => 'फरीदाबाद'],
    ['name' => 'Ambala',     'hindi_name' => 'अंबाला'],
    ['name' => 'Hisar',      'hindi_name' => 'हिसार'],
    ['name' => 'Rohtak',     'hindi_name' => 'रोहतक'],
],

// Andhra Pradesh
'AP' => [
    ['name' => 'Visakhapatnam', 'hindi_name' => 'विशाखापट्टनम'],
    ['name' => 'Vijayawada',    'hindi_name' => 'विजयवाड़ा'],
    ['name' => 'Guntur',        'hindi_name' => 'गुंटूर'],
    ['name' => 'Tirupati',      'hindi_name' => 'तिरुपति'],
    ['name' => 'Kurnool',       'hindi_name' => 'कुरनूल'],
],

// Telangana
'TS' => [
    ['name' => 'Hyderabad',     'hindi_name' => 'हैदराबाद'],
    ['name' => 'Warangal',      'hindi_name' => 'वारंगल'],
    ['name' => 'Nizamabad',     'hindi_name' => 'निजामाबाद'],
    ['name' => 'Khammam',       'hindi_name' => 'खम्मम'],
    ['name' => 'Karimnagar',    'hindi_name' => 'करीमनगर'],
],

// Kerala
'KL' => [
    ['name' => 'Thiruvananthapuram', 'hindi_name' => 'तिरुवनंतपुरम'],
    ['name' => 'Kochi',         'hindi_name' => 'कोच्चि'],
    ['name' => 'Kozhikode',     'hindi_name' => 'कोझिकोड'],
    ['name' => 'Thrissur',      'hindi_name' => 'त्रिशूर'],
    ['name' => 'Kollam',        'hindi_name' => 'कोल्लम'],
],

// Odisha
'OD' => [
    ['name' => 'Bhubaneswar',   'hindi_name' => 'भुवनेश्वर'],
    ['name' => 'Cuttack',       'hindi_name' => 'कटक'],
    ['name' => 'Rourkela',      'hindi_name' => 'राउरकेला'],
    ['name' => 'Puri',          'hindi_name' => 'पुरी'],
    ['name' => 'Sambalpur',     'hindi_name' => 'संबलपुर'],
],

// Assam
'AS' => [
    ['name' => 'Guwahati',      'hindi_name' => 'गुवाहाटी'],
    ['name' => 'Dibrugarh',     'hindi_name' => 'डिब्रूगढ़'],
    ['name' => 'Silchar',       'hindi_name' => 'सिलचर'],
    ['name' => 'Jorhat',        'hindi_name' => 'जोरहाट'],
    ['name' => 'Nagaon',        'hindi_name' => 'नगांव'],
],

// Jharkhand
'JH' => [
    ['name' => 'Ranchi',        'hindi_name' => 'रांची'],
    ['name' => 'Jamshedpur',    'hindi_name' => 'जमशेदपुर'],
    ['name' => 'Dhanbad',       'hindi_name' => 'धनबाद'],
    ['name' => 'Bokaro',        'hindi_name' => 'बोकारो'],
    ['name' => 'Hazaribagh',    'hindi_name' => 'हजारीबाग'],
],

// Chhattisgarh
'CG' => [
    ['name' => 'Raipur',        'hindi_name' => 'रायपुर'],
    ['name' => 'Bilaspur',      'hindi_name' => 'बिलासपुर'],
    ['name' => 'Durg',          'hindi_name' => 'दुर्ग'],
    ['name' => 'Korba',         'hindi_name' => 'कोरबा'],
    ['name' => 'Jagdalpur',     'hindi_name' => 'जगदलपुर'],
],

// Uttarakhand
'UK' => [
    ['name' => 'Dehradun',      'hindi_name' => 'देहरादून'],
    ['name' => 'Haridwar',      'hindi_name' => 'हरिद्वार'],
    ['name' => 'Nainital',      'hindi_name' => 'नैनीताल'],
    ['name' => 'Roorkee',       'hindi_name' => 'रुड़की'],
    ['name' => 'Haldwani',      'hindi_name' => 'हल्द्वानी'],
],

// Himachal Pradesh
'HP' => [
    ['name' => 'Shimla',        'hindi_name' => 'शिमला'],
    ['name' => 'Manali',        'hindi_name' => 'मनाली'],
    ['name' => 'Dharamshala',   'hindi_name' => 'धर्मशाला'],
    ['name' => 'Solan',         'hindi_name' => 'सोलन'],
    ['name' => 'Mandi',         'hindi_name' => 'मंडी'],
],

// Jammu & Kashmir
'JK' => [
    ['name' => 'Jammu',         'hindi_name' => 'जम्मू'],
    ['name' => 'Srinagar',      'hindi_name' => 'श्रीनगर'],
    ['name' => 'Anantnag',      'hindi_name' => 'अनंतनाग'],
    ['name' => 'Baramulla',     'hindi_name' => 'बारामुला'],
    ['name' => 'Udhampur',      'hindi_name' => 'उधमपुर'],
],
        ];

        $totalDistricts = 0;
        foreach ($districts as $stateCode => $dists) {
            $state = StateMaster::where('code', $stateCode)->first();
            if (!$state) continue;

            foreach ($dists as $dist) {
                DistrictMaster::updateOrCreate(
                    ['state_id' => $state->id, 'name' => $dist['name']],
                    array_merge($dist, ['state_id' => $state->id, 'is_active' => true])
                );
                $totalDistricts++;
            }
        }

        $this->command->info("✅ {$totalDistricts} districts seeded for priority states!");
    }
}