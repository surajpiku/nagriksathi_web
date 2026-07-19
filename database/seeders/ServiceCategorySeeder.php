<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Hunar service categories...');

        $categories = [
            [
                'name'       => 'Home Services',
                'hindi_name' => 'घर की सेवाएं',
                'icon'       => '🔧',
                'order'      => 1,
                'types'      => [
                    ['Plumber', 'प्लम्बर'],
                    ['Electrician', 'बिजली मिस्त्री'],
                    ['Carpenter', 'बढ़ई'],
                    ['Painter', 'रंग मिस्त्री'],
                    ['Mason', 'राज मिस्त्री'],
                    ['Welder', 'वेल्डर'],
                    ['AC Repair', 'AC मरम्मत'],
                    ['TV/Electronics Repair', 'TV/इलेक्ट्रॉनिक्स मरम्मत'],
                    ['Tile/Flooring', 'टाइल/फ्लोरिंग'],
                    ['Sofa/Furniture Repair', 'फर्नीचर मरम्मत'],
                ],
            ],
            [
                'name'       => 'Agriculture Services',
                'hindi_name' => 'कृषि सेवाएं',
                'icon'       => '🌾',
                'order'      => 2,
                'types'      => [
                    ['Tractor Operator', 'ट्रैक्टर चालक'],
                    ['Farm Labour', 'खेत मजदूर'],
                    ['Irrigation Setup', 'सिंचाई काम'],
                    ['Pesticide Spraying', 'दवाई छिड़काव'],
                    ['Harvesting Help', 'कटाई मजदूर'],
                    ['Animal Care', 'पशु सेवक'],
                    ['Borewell Drilling', 'बोरिंग काम'],
                    ['Garden/Nursery', 'बागवानी'],
                ],
            ],
            [
                'name'       => 'Health & Care',
                'hindi_name' => 'स्वास्थ्य सेवाएं',
                'icon'       => '🏥',
                'order'      => 3,
                'types'      => [
                    ['Doctor (MBBS)', 'डॉक्टर'],
                    ['AYUSH Doctor', 'आयुर्वेदिक डॉक्टर'],
                    ['Nurse/ANM', 'नर्स/ANM'],
                    ['Compounder', 'कम्पाउंडर'],
                    ['Physiotherapist', 'फिजियोथेरेपिस्ट'],
                    ['Elder Care Helper', 'बुजुर्ग सेवा'],
                    ['Midwife/Dai', 'दाई माँ'],
                ],
            ],
            [
                'name'       => 'Education & Tutoring',
                'hindi_name' => 'शिक्षा सेवाएं',
                'icon'       => '📚',
                'order'      => 4,
                'types'      => [
                    ['Home Tutor (Primary)', 'होम ट्यूटर (प्राइमरी)'],
                    ['Home Tutor (Secondary)', 'होम ट्यूटर (माध्यमिक)'],
                    ['Math Teacher', 'गणित शिक्षक'],
                    ['Science Teacher', 'विज्ञान शिक्षक'],
                    ['English Teacher', 'अंग्रेजी शिक्षक'],
                    ['Computer Teacher', 'कंप्यूटर शिक्षक'],
                    ['Music Teacher', 'संगीत शिक्षक'],
                    ['Spoken English Coach', 'स्पोकन इंग्लिश'],
                ],
            ],
            [
                'name'       => 'Transport Services',
                'hindi_name' => 'परिवहन सेवाएं',
                'icon'       => '🚗',
                'order'      => 5,
                'types'      => [
                    ['Auto Driver', 'ऑटो चालक'],
                    ['Taxi/Cab Driver', 'टैक्सी चालक'],
                    ['Truck Driver', 'ट्रक चालक'],
                    ['Bike Taxi', 'बाइक टैक्सी'],
                    ['School Van Driver', 'स्कूल वैन चालक'],
                    ['Goods Transport', 'माल ढुलाई'],
                ],
            ],
            [
                'name'       => 'Women Skills',
                'hindi_name' => 'महिला कौशल',
                'icon'       => '👩',
                'order'      => 6,
                'types'      => [
                    ['Tailoring/Stitching', 'सिलाई/कढ़ाई'],
                    ['Mehendi Artist', 'मेहंदी'],
                    ['Beauty Parlour', 'ब्यूटी पार्लर'],
                    ['Cooking/Catering', 'खाना बनाना/केटरिंग'],
                    ['Pickle/Papad Making', 'अचार/पापड़'],
                    ['Knitting/Weaving', 'बुनाई/करघा'],
                    ['Baby Sitting', 'बेबी सिटिंग'],
                    ['Maid/House Help', 'घरेलू सहायिका'],
                ],
            ],
            [
                'name'       => 'Animal Care',
                'hindi_name' => 'पशु सेवाएं',
                'icon'       => '🐄',
                'order'      => 7,
                'types'      => [
                    ['Veterinary Doctor', 'पशु डॉक्टर'],
                    ['Cattle Care', 'मवेशी देखभाल'],
                    ['Poultry Care', 'मुर्गी पालन'],
                    ['Fish Farming Help', 'मछली पालन'],
                    ['Goat/Sheep Care', 'बकरी/भेड़ देखभाल'],
                ],
            ],
            [
                'name'       => 'Local Business',
                'hindi_name' => 'स्थानीय व्यवसाय',
                'icon'       => '🛒',
                'order'      => 8,
                'types'      => [
                    ['General Store', 'किराना दुकान'],
                    ['Medical Store', 'दवाई दुकान'],
                    ['Flour Mill', 'आटा चक्की'],
                    ['Photography', 'फोटोग्राफी'],
                    ['Mobile Repair', 'मोबाइल मरम्मत'],
                    ['Cycle/Bike Repair', 'साइकिल/बाइक मरम्मत'],
                    ['Printing/Xerox', 'प्रिंटिंग/ज़ेरॉक्स'],
                ],
            ],
            [
                'name'       => 'Professional Services',
                'hindi_name' => 'व्यावसायिक सेवाएं',
                'icon'       => '📋',
                'order'      => 9,
                'types'      => [
                    ['Accountant', 'लेखाकार'],
                    ['Legal Helper', 'कानूनी सहायक'],
                    ['Government Form Help', 'सरकारी फॉर्म सहायता'],
                    ['Document Writer', 'दस्तावेज लेखक'],
                    ['Insurance Agent', 'बीमा एजेंट'],
                    ['Real Estate Agent', 'प्रॉपर्टी एजेंट'],
                ],
            ],
            [
                'name'       => 'Tech & Digital',
                'hindi_name' => 'टेक एवं डिजिटल',
                'icon'       => '💡',
                'order'      => 10,
                'types'      => [
                    ['Computer Operator', 'कंप्यूटर ऑपरेटर'],
                    ['Data Entry', 'डेटा एंट्री'],
                    ['Web Designer', 'वेब डिज़ाइनर'],
                    ['Social Media Manager', 'सोशल मीडिया'],
                    ['Tally/Accounting Software', 'टैली/अकाउंटिंग'],
                    ['CCTV Installation', 'CCTV लगाना'],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = ServiceCategory::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name'          => $catData['name'],
                    'hindi_name'    => $catData['hindi_name'],
                    'slug'          => Str::slug($catData['name']),
                    'icon'          => $catData['icon'],
                    'display_order' => $catData['order'],
                    'is_active'     => true,
                ]
            );

            foreach ($catData['types'] as $type) {
                ServiceType::firstOrCreate(
                    ['slug' => Str::slug($type[0])],
                    [
                        'category_id' => $category->id,
                        'name'        => $type[0],
                        'hindi_name'  => $type[1],
                        'slug'        => Str::slug($type[0]),
                        'is_active'   => true,
                    ]
                );
            }

            $this->command->info("  Added category: {$catData['name']} with " . count($catData['types']) . " types");
        }

        $this->command->info('Hunar categories seeded successfully!');
    }
}