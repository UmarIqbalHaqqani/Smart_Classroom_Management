<?php

namespace Database\Seeders\FrontendCMS;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpeechSliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $records = [
            [
                'name' => $principalName = "Genoveva Lebsack",
                'designation' => 'Principal',
                'title' => 'A Heartfelt Message from the Principal: A Journey of Heart and Hope at infixEDU School',

                'speech' => "
                    <div><span>Dear Students, Faculty, Parents, and Guests, <br> As Principal of infixEDU, I am honored to address you today as we begin another exciting chapter. InfixEDU is more than a school; it is a place where dreams come alive, talents flourish, and lasting memories are created.<br><br>To our students, you are the heart of our community, each bringing unique colors to our tapestry. To our faculty, your dedication as mentors inspires growth and success. To our parents, your unwavering support fuels this journey of learning and discovery.<br><br>This year, let us continue striving for excellence, embracing challenges with resilience and hope. Together, we can achieve greatness.<br><br>Warm regards,<br>Genoveva Lebsack,<br>Principal, infixEDU School</span></div>
                ",

                'image' => "public/backEnd/assets/img/staff/principal.png",
                'school_id' => 1,
            ],
            
            [
                'name' => $vicePrincipalName = 'Joseph Forster',
                'designation' => 'Vice Principal',
                'title' => 'A Message from the Vice Principal: Nurturing Brilliance, Inspiring Excellence at infixEDU School',
            
                'speech' => "
                    <div><span>Dear infixEDU Family, <br> It is my pleasure to address you as Vice Principal as we embark on a new academic chapter. At infixEDU, we embrace excellence as a journey of growth and self-discovery.<br><br>To our students, you inspire us with your unique talents and aspirations. To our faculty, your dedication as mentors creates an environment where students thrive. To our parents, your partnership and support are vital to our success.<br><br>Together, let us embrace the opportunities and challenges ahead with resilience and collaboration.<br><br>Warm regards,<br>Joseph Forster,<br>Vice Principal, infixEDU School</span></div>
                ",
            
                'image' => "public/backEnd/assets/img/staff/2.jpg",
                'school_id' => 1,
            ],
            [
                'name' => $founderName = $faker->name,
                'designation' => 'Founder',
                'title' => "A Message from Our Founder, {$founderName}: Nurturing Dreams, Inspiring Futures ",

                'speech' => "
                    <div class='container'>
                        <p class='text-muted'>Dear Beloved infixEDU Community,</p>
                        <p class='text-justify'>As I stand before you today, my heart swells with gratitude and pride. It feels like just yesterday when infixEDU was a seed of an idea, a dream nurtured with passion, dedication, and a profound belief in the transformative power of education.</p>
            
                        <h5 class='text-secondary'>A Vision Takes Flight:</h5>
                        <p>InfixEDU was envisioned as more than a school; it was designed to be a sanctuary where dreams take flight, where the pursuit of knowledge intertwines with the spirit of exploration, and where every student is empowered to chart their unique path to success.</p>
            
                        <h5 class='text-secondary'>A Tapestry of Achievements:</h5>
                        <p>Reflecting on the journey thus far, I am humbled by the achievements of our students, the dedication of our faculty, and the unwavering support of our parents. Each success story, each milestone, is a testament to the collective spirit and resilience that defines infixEDU.</p>
            
                        <h5 class='text-secondary'>Beyond Education, a Community:</h5>
                        <p>InfixEDU is not just an educational institution; it is a community. A community of learners, educators, and parents, bound together by the shared vision of creating a nurturing environment that goes beyond textbooks and exams – one that fosters character, compassion, and a love for learning.</p>
            
                        <h5 class='text-secondary'>A Heartfelt Thank You:</h5>
                        <p>To our exceptional faculty and staff, your dedication to shaping young minds is the heartbeat of infixEDU. Your passion, creativity, and tireless efforts lay the foundation for the dreams that take root within our walls.</p>
            
                        <h5 class='text-secondary'>Parents, the True Partners:</h5>
                        <p>To the parents who entrust us with their most precious treasures, your partnership is invaluable. Your trust, involvement, and shared commitment to the educational journey make infixEDU a truly special place.</p>
            
                        <h5 class='text-secondary'>Looking Forward:</h5>
                        <p>As we venture into the future, let us carry forward the spirit of innovation, collaboration, and compassion that defines infixEDU. Together, we will continue to explore new horizons, inspire brilliance, and create a legacy that extends beyond the boundaries of classrooms.</p>
            
                        <p class='font-italic text-right'>With heartfelt appreciation,</p>
                        <p class='text-right font-weight-bold'><br>{$founderName}, Founder</p>
                    </div>
                ",
                'image' => "public/backEnd/assets/img/staff/1.jpg",
                'school_id' => 1,
            ],
        ];

        DB::transaction(function () use ($records) {
            DB::table('speech_sliders')->delete();
        
            foreach ($records as $index => $record) {
                DB::table('speech_sliders')->updateOrInsert(
                    ['id' => $index + 1, 'designation' => $record['designation'], 'school_id' => $record['school_id']],
                    $record
                );
            }
        });
        
    }
}
