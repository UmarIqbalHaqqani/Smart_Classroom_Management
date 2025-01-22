<?php

namespace Database\Seeders\Communicate;

use App\SmNoticeBoard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Factories\SmNoticeBoardFactory;

class SmNoticeBoardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count)
    {
        DB::table('sm_notice_boards')->delete();

        DB::table('sm_notice_boards')->insert([
            [
                'notice_title' => 'A Meeting Between Teachers and Parents to Discuss Academic Objectives and Collaborative Goals',

                'notice_message' => "We are delighted to invite you to our upcoming Parent-Teacher Meeting, scheduled for 12-12-2024 from 09:00 AM to 11:59 AM. This meeting is a vital opportunity for teachers and parents to come together and engage in meaningful discussions about academic objectives, collaborative goals, and the overall development of our students. During this session, you will have the chance to review your child's progress, explore ways to address any challenges, and work closely with our dedicated educators to set shared goals for a successful academic journey. Your active participation is key to fostering a strong partnership that ensures the best possible outcomes for your child's education. We look forward to welcoming you and collaborating to create a supportive learning environment.",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Celebrate National Day with Us: Join the Festivities at Infix School on December 16th',

                'notice_message' => "We are thrilled to announce a series of remarkable events at InfixEdu School as we proudly honor National Day with a grand celebration on December 16th. This special occasion is an opportunity for us to come together as a community and celebrate our shared heritage, achievements, and aspirations. The day will feature a diverse lineup of activities, including a vibrant Book Fair showcasing a variety of literary works, exhilarating Sports competitions promoting teamwork and sportsmanship, and intellectually stimulating Debate events designed to foster critical thinking and communication skills. We wholeheartedly invite all students, parents, and teachers to actively engage in these festivities and make lasting memories as part of our dynamic school community. Your participation and enthusiasm will be the driving force behind the success of these events, ensuring they are both meaningful and enjoyable for everyone. Together, let’s make this National Day celebration a moment to cherish, filled with learning, joy, and togetherness. Don’t miss out on this wonderful opportunity to be a part of something truly special!",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Infix School Hosts an Innovative ICT Training Program for Teachers and Students During ICT Week 2024',

                'notice_message' => "We are delighted to announce that Infix School will be organizing an innovative ICT training program for both teachers and students as a highlight of our ICT Week 2024 celebrations! This program aims to equip participants with advanced digital skills and practical knowledge of cutting-edge technologies, empowering them to effectively integrate these tools into educational practices and everyday life. Through interactive sessions, workshops, and hands-on activities, attendees will have the opportunity to explore innovative tech practices, gain insights into the latest advancements in the field of information and communication technology, and discover new ways to make learning more engaging and impactful. This initiative reflects our commitment to fostering digital literacy and preparing our school community to excel in an increasingly tech-driven world. We encourage everyone to participate actively in this transformative experience, as it promises to open doors to exciting opportunities for growth and innovation. Don’t miss this unique chance to expand your horizons, enhance your digital proficiency, and become a part of our vision for a brighter, tech-enabled future!",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Infix School Affirms the Importance of Proper Uniform Compliance for All Students on Campus',
                'notice_message' => "At Infix School, we believe that proper uniform compliance is an essential part of maintaining a positive, respectful, and disciplined learning environment. By adhering to the prescribed dress code, students not only present themselves as responsible members of our school community but also foster a sense of unity and equality among their peers. We kindly request your continued cooperation in ensuring that all students wear the approved uniform and follow the dress code guidelines at all times while on campus. This collective effort plays a significant role in upholding the values and standards that define our school. Your support is greatly appreciated as we work together to create an environment conducive to academic success and personal growth for every student.",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Infix EDU School Enhances Campus Safety and Security Measures for Students and Staff',

                'notice_message' => "At Infix EDU School, we prioritize the safety and security of both our students and staff. As part of our ongoing commitment to maintaining a secure campus environment, we are enhancing our safety measures to ensure that all individuals on campus feel protected and valued. We kindly ask all parents to follow the designated drop-off and pick-up points to help maintain smooth and safe traffic flow. Additionally, we encourage parents to inform the school in advance of any changes in transportation arrangements to ensure that our security team is well-prepared to handle the adjustments. Together, we can continue fostering a safe and supportive environment for everyone. We appreciate your cooperation and commitment to our shared goal of keeping the school community secure.",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Announcement: Winter Vacation 2024 Scheduled from December 16 to December 27',

                'notice_message' => "We are excited to announce that InfixEdu School will be on a winter vacation from December 16, 2024, to December 27, 2024. This break provides a wonderful opportunity for students, teachers, and staff to relax, recharge, and spend quality time with their families. As we approach the holiday season, we encourage our students to enjoy this well-deserved break and engage in activities that inspire creativity and learning in a relaxed setting. Please note that all school activities, including classes and extracurricular events, will resume after the break, starting in early January. We extend our warmest wishes to all our students and their families for a joyful and peaceful holiday season filled with happiness and rest. Let’s return in the new year with renewed energy for a successful term ahead!",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'An Invitation to Our Students and Esteemed Parents: We Welcome Your Valuable Feedback and Suggestions',

                'notice_message' => "At InfixEdu School, we firmly believe that collaboration with our students and parents is key to our ongoing success and improvement. As part of our commitment to fostering an inclusive and transparent environment, we invite all students and their esteemed parents to share any feedback, suggestions, or concerns you may have. Your insights are invaluable in helping us shape a better learning experience and continue improving the quality of education we provide. Whether it’s about school policies, teaching methods, extracurricular activities, or any other aspect of school life, we encourage open communication. Please feel free to reach out to the school administration via email, phone, or in-person meetings. We look forward to hearing from you and appreciate your continued support in making InfixEdu School an even better place for our students to thrive and succeed.",

                'notice_date' => date("Y-m-d", strtotime('2023-12-12')),
                'publish_on' => date("Y-m-d", strtotime('2023-12-12')),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
        ]);
    }
}
