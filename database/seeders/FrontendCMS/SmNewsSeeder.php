<?php

namespace Database\Seeders\FrontendCMS;

use App\SmNews;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SmNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('sm_news')->delete();
        $faker = Faker::create();
        $cid = [1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3];
        
        $staticNews = [
            [
                'title' => "Digital Transformation in Education: INFIX EDU Paving the Way",
                'body' => "INFIX EDU is leading the charge in digital transformation, revolutionizing the way education is delivered. With cloud-based solutions and real-time data analytics, INFIX EDU enables institutions to streamline operations, enhance student engagement, and foster a culture of innovation. This shift not only modernizes the classroom but also prepares students for a digital-first world.",
            ],
            [
                'title' => "Success Stories: How INFIX EDU ERP Empowers Schools Worldwide",
                'body' => "INFIX EDU's ERP system has empowered schools across the globe to transform their administration and academic processes. By simplifying management, enhancing communication, and improving resource allocation, INFIX EDU has helped schools deliver better educational experiences. Schools in multiple countries report increased efficiency, reduced costs, and greater student satisfaction thanks to this robust ERP solution.",
            ],
            [
                'title' => "INFIX EDU Launches Enhanced Features for a Seamless School Year",
                'body' => "The new features launched by INFIX EDU are designed to support schools in creating a seamless and productive academic year. From updated enrollment tools and attendance tracking to advanced reporting capabilities, these enhancements offer educators and administrators more control and insight than ever before, driving educational success across all levels.",
            ],
            [
                'title' => "Innovations in School Management: The Future of INFIX EDU",
                'body' => "INFIX EDU is pioneering innovations in school management, incorporating AI-driven analytics and mobile-friendly platforms that make educational management more efficient and data-informed. These advancements empower educators and administrators to manage resources, track student performance, and optimize curricula with unprecedented ease, setting new standards in education technology.",
            ]
        ];

        foreach (range(1, 4) as $key => $index) {
            $storeData = new SmNews();

            if ($key < 4) {
                $storeData->news_title = $staticNews[$key]['title'];
                $storeData->news_body = "<div class='news-body' style='font-size: 16px; line-height: 1.6;'>
                                            <p class='lead'>{$staticNews[$key]['body']}</p>
                                            <div class='text-muted'>Learn more about our journey to enhance education for all.</div>
                                         </div>";
            } else {
                $title = $faker->sentence(6);
                $description = "In the world of education, " . strtolower($title) . " This approach is setting new benchmarks in learning outcomes and operational efficiency.";
                
                $storeData->news_title = $title;
                $storeData->news_body = "<div class='news-body' style='font-size: 16px; line-height: 1.6;'>
                                            <h3 class='text-primary'>{$title}</h3>
                                            <p class='lead'>{$description}</p>
                                            <div class='text-muted'>Learn more about our journey to enhance education for all.</div>
                                         </div>";
            }

            $storeData->view_count = $faker->randomDigit;
            $storeData->active_status = 1;
            $storeData->image = 'public/uploads/news/news' . ($key + 1) . '.jpg';
            $storeData->publish_date = '2019-06-02';
            $storeData->category_id = $cid[$key];
            $storeData->order = $key + 1;
            $storeData->created_at = now();
            $storeData->save();
        }

        $staticArchives = [
            [
                'title' => "Creative Spaces: Enhancing Learning and Productivity at Home",
                'body' => "Designing a functional and inspiring workspace can transform the way we learn and create. This organized desk setup features vibrant tools like colorful pencils, a diary for jotting down ideas, and a sleek desk lamp for focused lighting. The corkboard adds a personal touch, perfect for pinning reminders, art, or inspiration. Such setups not only encourage productivity but also make studying and working more enjoyable, proving that creativity thrives in well-designed environments.",
            ],
            [
                'title' => "A World of Knowledge: The Beauty of an Organized Library",
                'body' => "A well-organized library is more than just a collection of books; it’s a gateway to endless adventures and learning. With rows of colorful book spines neatly arranged, this library inspires curiosity and exploration. Each shelf tells a story, offering readers the opportunity to dive into diverse genres, from fiction to history and science. Libraries like this create an atmosphere that fuels the imagination and fosters a lifelong love for learning.",
            ],
            [
                'title' => "Blending Tradition and Technology: The Book Meets the Technology",
                'body' => "The fusion of a traditional book and a modern laptop symbolizes the harmony between timeless knowledge and cutting-edge innovation. This image captures the essence of modern education, where physical books provide depth and digital platforms offer access to limitless resources. Together, they create a balanced approach to learning, ensuring that the richness of traditional methods meets the efficiency of technology in shaping the future of education.",
            ],
            [
                'title' => "A Minimalist Workspace Inspiration",
                'body' => "This setup highlights a perfect balance between functionality and aesthetics. Featuring neatly stacked books wrapped in elegant patterns, a sleek black and gold clock, and a refreshing touch of greenery from a small potted plant, it creates an inviting and productive environment. Ideal for anyone looking to stay organized while adding style to their workspace.",
            ],
            [
                'title' => "Late-Night Study Essentials: Creating a Cozy and Productive Space",
                'body' => "This cozy study setup is perfect for late-night learners. With a bright desk lamp illuminating the workspace, essentials like colorful markers, a calculator, notebooks, and pens are neatly arranged for productivity. A vibrant blue backpack sits nearby, ready for the next school day. The warm orange backdrop adds a touch of energy, making studying a more inviting experience.",
            ]
        ];

        foreach (range(1, 5) as $key => $index) {
            $storeArchiveData = new SmNews();

            if ($key < 5) {
                $storeArchiveData->news_title = $staticArchives[$key]['title'];
                $storeArchiveData->news_body = "<div class='news-body' style='font-size: 16px; line-height: 1.6;'>
                                            <p class='lead'>{$staticArchives[$key]['body']}</p>
                                            <div class='text-muted'>Learn more about our journey to enhance education for all.</div>
                                         </div>";
            } else {
                $title = $faker->sentence(6);
                $description = "In the world of education, " . strtolower($title) . " This approach is setting new benchmarks in learning outcomes and operational efficiency.";
                
                $storeArchiveData->news_title = $title;
                $storeArchiveData->news_body = "<div class='news-body' style='font-size: 16px; line-height: 1.6;'>
                                            <h3 class='text-primary'>{$title}</h3>
                                            <p class='lead'>{$description}</p>
                                            <div class='text-muted'>Learn more about our journey to enhance education for all.</div>
                                         </div>";
            }

            $storeArchiveData->view_count       = $faker->randomDigit;
            $storeArchiveData->active_status    = 1;
            $storeArchiveData->image            = 'public/uploads/news/archive-' . ($key + 1) . '.jpg';
            $storeArchiveData->publish_date     = date('Y-m-d');
            $storeArchiveData->mark_as_archive  = 1;
            $storeArchiveData->category_id      = $cid[$key];
            $storeArchiveData->order            = $key + 1;
            $storeArchiveData->created_at       = now();
            $storeArchiveData->save();
        }
    }
}
