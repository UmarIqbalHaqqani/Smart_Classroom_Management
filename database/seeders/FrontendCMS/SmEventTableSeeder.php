<?php

namespace Database\Seeders\FrontendCMS;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SmEventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sm_events')->delete();
        DB::table('sm_events')->insert([
            [
                'event_title' => 'Illuminating Innovation: Celebrating Scientific Breakthroughs',
                'event_location' => 'Science and Innovation Center',
                
                'event_des' => "<div style='text-align: justify;'><b>Illuminating Innovation: A Celebration of Scientific Discoveries</b></div>
                <div style='text-align: justify;'>Join us for a spectacular event dedicated to the wonders of science and invention. Witness groundbreaking ideas that light up the path to progress and celebrate the spirit of innovation.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Highlights:</b></div>
                <div style='text-align: justify;'>This event is a tribute to the power of creativity and scientific discovery. Participants will share their pioneering ideas and projects, demonstrating how they aim to revolutionize the future through science.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Key Activities:</b></div>
                <div style='text-align: justify;'>1. Innovation Gallery:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Explore an exhibition of inventive projects showcasing real-world applications and futuristic designs.</div>
                <div style='text-align: justify;'>2. Live Demonstrations:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Watch scientists and inventors present their creations in action, bringing their visions to life.</div>
                <div style='text-align: justify;'>3. Knowledge Exchange Panels:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Engage in thought-provoking discussions with industry leaders, researchers, and innovators.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div style='text-align: justify;'><b>How to Participate:</b></div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Submit your innovative project or invention for review by our expert panel.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Register as an attendee to experience the excitement and learn from scientific pioneers.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Attend workshops to gain insights and skills from industry experts.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div style='text-align: justify;'><b>Prizes and Recognition:</b></div>
                <div style='text-align: justify;'>Outstanding projects will be honored with prestigious awards and cash prizes. Celebrate your contribution to the world of science and be recognized as a trailblazer in innovation.</div>
                <div style='text-align: justify;'>Don't miss this inspiring event where ideas turn into reality and science lights the way to the future. Be part of the journey to innovation!</div>
                <div style='text-align: justify;'>For more details and registration, contact +96897002784.</div>",
                
                'from_date' => '2024-12-10',
                'to_date' => '2024-12-14',
                'uplad_image_file' => 'public/uploads/news/news8.jpg',
            ],

            [
                'event_title' => 'Graduation Convocation Ceremony 2024',
                'event_location' => 'Grand Convocation Hall, School Campus',
                
                'event_des' => "<div style='text-align: justify;'><b>Graduation Convocation Ceremony 2024</b></div>
                <div style='text-align: justify;'>Celebrate the achievements of our graduating class of 2024 in a grand convocation ceremony. Join us as we honor the dedication, hard work, and success of our students.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Highlights:</b></div>
                <div style='text-align: justify;'>1. Degree Awarding Ceremony:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Watch as graduates receive their well-earned degrees and certificates in the presence of esteemed faculty and family members.</div>
                <div style='text-align: justify;'>2. Keynote Speech:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- An inspiring address by a distinguished guest speaker who will share valuable insights and wisdom for the graduates.</div>
                <div style='text-align: justify;'>3. Alumni Recognition:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Acknowledging the achievements of alumni and their contributions to the community.</div>
                <div style='text-align: justify;'>4. Celebratory Moments:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Memorable group photos, cap-tossing celebration, and a formal dinner for graduates and their families.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Guidelines for Graduates:</b></div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Ensure timely arrival and bring your convocation gown and ID card.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Register for the event at the convocation help desk before the deadline.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Invite your family and loved ones to join in celebrating this milestone.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div style='text-align: justify;'><b>Event Schedule:</b></div>
                <div style='text-align: justify;'>- Registration and Gown Collection: 9:00 AM - 11:00 AM</div>
                <div style='text-align: justify;'>- Ceremony Start: 12:00 PM</div>
                <div style='text-align: justify;'>- Group Photos and Lunch: 2:30 PM</div>
                <div style='text-align: justify;'>- Celebration Dinner: 6:00 PM</div>
                <div style='text-align: justify;'>For more details or any queries, contact +96897002784.</div>",
                
                'from_date' => '2024-12-15',
                'to_date' => '2024-12-15',
                'uplad_image_file' => 'public/uploads/events/events5.jpg',
            ],

            [
                'event_title' => 'Annual Baseball Championship 2024: A Thrilling Success',
                'event_location' => 'School Sports Ground, Main Campus',
            
                'event_des' => "<div style='text-align: justify;'><b>Annual Baseball Championship 2024 - Event Concluded Successfully!</b></div>
                <div style='text-align: justify;'>We are excited to announce that the <b>Annual Baseball Championship 2024</b> was a resounding success, showcasing incredible talent, teamwork, and sportsmanship. This exhilarating event brought together players, coaches, and fans in a celebration of athletic excellence.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Highlights:</b></div>
                <div style='text-align: justify;'>1. Championship Match:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- The final match was a nail-biting showdown, with both teams displaying remarkable skill and determination, keeping the audience on the edge of their seats.</div>
                <div style='text-align: justify;'>2. MVP Award:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- The Most Valuable Player (MVP) award was presented to an outstanding athlete who showcased exceptional performance throughout the tournament.</div>
                <div style='text-align: justify;'>3. Team Spirit Award:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- A special award was given to the team that demonstrated the best sportsmanship and teamwork, fostering a spirit of camaraderie and fair play.</div>
                <div style='text-align: justify;'>4. Fan Engagement:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Enthusiastic fans cheered for their favorite teams, participated in fun activities, and enjoyed a day full of excitement and entertainment.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'>This memorable championship not only highlighted the athletic prowess of our students but also strengthened the sense of community and school spirit.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'>We extend our heartfelt congratulations to all participating teams and thank everyone who contributed to making this event a grand success.</div>",
            
                'from_date' => '2024-06-10',
                'to_date' => '2024-06-10',
                'uplad_image_file' => 'public/uploads/events/event2.jpg',
            ],

            [
                'event_title' => 'Annual Class Party 2024: A Celebration of Fun and Togetherness',
                'event_location' => 'Main Hall, School Campus',
            
                'event_des' => "<div style='text-align: justify;'><b>Annual Class Party 2024</b></div>
                <div style='text-align: justify;'>Join us for the much-awaited <b>Annual Class Party 2024</b>, where students can unwind, celebrate achievements, and create lasting memories together. This fun-filled event promises a day of joy, games, and laughter!</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Highlights:</b></div>
                <div style='text-align: justify;'>1. Decoration Extravaganza:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Enjoy a vibrant, colorful atmosphere filled with balloons, streamers, and festive decor.</div>
                <div style='text-align: justify;'>2. Fun Games and Activities:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Participate in a variety of exciting games and team activities designed for all age groups.</div>
                <div style='text-align: justify;'>3. Refreshments and Treats:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Indulge in delicious snacks, sweets, and beverages throughout the event.</div>
                <div style='text-align: justify;'>4. Music and Dance:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Groove to lively music and showcase your dance moves on the dance floor!</div>
                <div style='text-align: justify;'>5. Special Awards and Prizes:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Exciting awards and prizes for the best performances and game winners.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Guidelines for Students:</b></div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Arrive on time and bring your party pass.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Follow the instructions of the event coordinators for a smooth and enjoyable experience.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Participate with enthusiasm and respect others to maintain a joyful environment.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Schedule:</b></div>
                <div style='text-align: justify;'>- Welcome and Opening Remarks: 10:00 AM</div>
                <div style='text-align: justify;'>- Games and Activities: 10:30 AM - 12:30 PM</div>
                <div style='text-align: justify;'>- Refreshment Break: 12:30 PM - 1:30 PM</div>
                <div style='text-align: justify;'>- Music and Dance: 1:30 PM - 3:00 PM</div>
                <div style='text-align: justify;'>- Closing Ceremony and Awards: 3:00 PM</div>
                <div style='text-align: justify;'>For more details or any queries, contact +96897002784.</div>",
            
                'from_date' => '2024-10-25',
                'to_date' => '2024-10-25',
                'uplad_image_file' => 'public/uploads/events/event11.jpg',
            ],
            
            [
                'event_title' => 'Family Picnic Day 2024: Parents & Students Celebration',
                'event_location' => 'Green Meadows Park, City Center',
                
                'event_des' => "<div style='text-align: justify;'><b>Family Picnic Day 2024</b></div>
                <div style='text-align: justify;'>Join us for the exciting <b>Family Picnic Day 2024</b>, an event designed to bring together parents, students, and teachers in a vibrant outdoor setting. It’s a perfect day to relax, bond, and create cherished memories with family and friends.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Highlights:</b></div>
                <div style='text-align: justify;'>1. Outdoor Fun:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Enjoy a lush green park ambiance, perfect for picnics and relaxation.</div>
                <div style='text-align: justify;'>2. Interactive Games:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Join in team games, family competitions, and activities suitable for all ages.</div>
                <div style='text-align: justify;'>3. Delicious Picnic Snacks:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Relish a variety of snacks, sandwiches, and refreshing beverages.</div>
                <div style='text-align: justify;'>4. Live Entertainment:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Enjoy live music, storytelling, and kids’ performances.</div>
                <div style='text-align: justify;'>5. Photo Booths:</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Capture joyful moments with themed photo setups and props.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Guidelines for Participants:</b></div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Bring your own picnic mats or blankets for a comfortable experience.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Participate actively in games and activities.</div>
                <div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Maintain cleanliness and respect the natural surroundings.</div>
                <div style='text-align: justify;'><br></div>
                <div style='text-align: justify;'><b>Event Schedule:</b></div>
                <div style='text-align: justify;'>- Welcome and Registration: 9:00 AM</div>
                <div style='text-align: justify;'>- Games and Activities: 9:30 AM - 12:00 PM</div>
                <div style='text-align: justify;'>- Picnic and Refreshments: 12:00 PM - 2:00 PM</div>
                <div style='text-align: justify;'>- Closing Remarks and Farewell: 2:00 PM</div>
                <div style='text-align: justify;'>For more details or any queries, contact +96897002784.</div>",
                
                'from_date' => '2024-11-15',
                'to_date' => '2024-11-15',
                'uplad_image_file' => 'public/uploads/events/event3.jpg',
            ],
            
            [
                'event_title' => 'Biggest Robotics Competition in Campus',
                'event_location' => 'Main Campus',
                
                'event_des' => "<div style='text-align: justify;'><b>Robotics Competition on Campus: Unleashing Innovation and Ingenuity</b></div><div style='text-align: justify;'>Prepare for an electrifying event as InfixEdu proudly announces its upcoming robotics competition set to ignite the campus with technological brilliance and inventive spirit.</div><div style='text-align: justify;'><br></div><div style='text-align: justify;'><b>Event Overview:</b></div><div style='text-align: justify;'>In the spirit of fostering creativity and technological prowess, our robotics competition provides a platform for students to showcase their engineering skills and problem-solving acumen. Participants will design and program robots to navigate challenges, promoting teamwork, critical thinking, and hands-on application of robotics concepts.</div><div style='text-align: justify;'><br></div><div style='text-align: justify;'><b>Competition Categories:</b></div><div style='text-align: justify;'>1. Autonomous Robot Challenge:</div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Robots operate independently, completing predefined tasks using onboard sensors and programming.</div><div style='text-align: justify;'>2. Sumo Robot Showdown:</div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Robots face off in a Sumo-style ring, aiming to push opponents out or disable them within the arena.</div><div style='text-align: justify;'>3. Innovation Showcase:</div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Teams present innovative robotic projects, emphasizing real-world applications and creativity.</div><div style='text-align: justify;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</div><div style='text-align: justify;'><b>How to participate:</b></div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Assemble a team of innovative minds (2-4 members).</div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Register your team by the [Registration Deadline].</div><div style='text-align: justify;'>&nbsp;&nbsp;&nbsp;&nbsp;- Attend a pre-competition workshop for guidance on building and programming.</div><div style='text-align: justify;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</div><div style='text-align: justify;'><b>Prizes and recognition:</b></div><div style='text-align: justify;'>Outstanding teams will not only earn recognition for their skills but also compete for exciting prizes, fostering a competitive yet collaborative atmosphere.&nbsp; Instructors and mentors:</div><div style='text-align: justify;'>Expert mentors from our robotics faculty will be available to guide and support teams throughout the competition, ensuring a rich learning experience.</div><div style='text-align: justify;'>Join us for a day of innovation, competition, and celebration!</div><div style='text-align: justify;'>This robotics competition promises to be an exhilarating showcase of talent and technology. Don't miss the chance to be a part of this thrilling event, where creativity and robotics collide on our campus.</div><div style='text-align: justify;'>For inquiries and registration details, contact [Insert Contact Information]. Let the robotics revolution begin!</div>",
                
                'from_date' => '2024-10-12',
                'to_date' => '2024-10-16',
                'uplad_image_file' => 'public/uploads/events/event6.png',
            ], 
        ]);
    }
}
