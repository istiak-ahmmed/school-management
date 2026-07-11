<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Dummy Notices
        $notices = [
            [
                'title' => 'মাদ্রাসায় নতুন শিক্ষাবর্ষের ভর্তি চলছে',
                'content' => '২০২৬ শিক্ষাবর্ষে নূরানী, নাজেরা, হেফজ ও কিতাব বিভাগে ভর্তি চলছে। বিস্তারিত জানতে যোগাযোগ করুন।',
                'category' => 'admission',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'আগামী মাসের প্রথম সাময়িক পরীক্ষার সময়সূচি',
                'content' => 'প্রথম সাময়িক পরীক্ষার সময়সূচি প্রকাশ করা হয়েছে। সকল ছাত্র-ছাত্রীকে প্রস্তুতি নেওয়ার নির্দেশ দেওয়া হলো।',
                'category' => 'exam',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'পবিত্র ঈদুল ফিতর উপলক্ষে ছুটি',
                'content' => 'আসন্ন পবিত্র ঈদুল ফিতর উপলক্ষে আগামী ৫ দিন মাদ্রাসা বন্ধ থাকবে।',
                'category' => 'general',
                'published_at' => now()->subDays(10),
            ]
        ];

        foreach ($notices as $notice) {
            \App\Models\Notice::create($notice);
        }

        // 2. Create Dummy Gallery items
        $galleries = [
            [
                'title' => 'মাদ্রাসার বার্ষিক মাহফিল',
                'type' => 'image',
                'path' => 'demo/gallery1.jpg',
                'order_column' => 1,
            ],
            [
                'title' => 'পুরস্কার বিতরণী অনুষ্ঠান',
                'type' => 'image',
                'path' => 'demo/gallery2.jpg',
                'order_column' => 2,
            ],
            [
                'title' => 'ক্লাস চলাকালীন সময়ের দৃশ্য',
                'type' => 'image',
                'path' => 'demo/gallery3.jpg',
                'order_column' => 3,
            ]
        ];

        foreach ($galleries as $gallery) {
            \App\Models\Gallery::create($gallery);
        }
    }
}
