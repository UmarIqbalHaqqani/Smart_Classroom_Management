<?php

namespace Database\Seeders\FrontendCMS;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SmPhotoGalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sm_photo_galleries')->delete();

        DB::table('sm_photo_galleries')->insert([
            [
                'parent_id' => null,
                'name' => 'Empowering Students: Discovering Joy in Mathematics!',
                
                'description' => "
                    <div><span style='color:#333333'>Step into a world of numbers, equations, and limitless possibilities with a mathematics class that goes beyond the ordinary! Watch as students engage, learn, and take center stage to showcase their understanding and creativity in the world of math.</span></div>
                    
                    <div><br><strong style='color:#000000'>Class Highlights:</strong></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>1. Interactive Learning:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Dive into engaging activities and problem-solving sessions.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Collaborate with peers to unravel mathematical mysteries.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Develop critical thinking skills through hands-on exercises.</span></div>
            
                    <div><br>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'><strong>2. Student-Led Presentations:</strong></span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Witness students confidently presenting solutions and creative approaches.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Encourage collaborative learning and peer appreciation.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Celebrate individuality and unique problem-solving methods.</span></div>
            
                    <div><br>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'><strong>3. Creative Exploration:</strong></span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Experience math through storytelling and visual representation.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Encourage curiosity and innovative thinking in every lesson.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Engage in friendly competitions and team-based challenges.</span></div>
            
                    <div><br><strong style='color:#000000'>Why Participate?</strong></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Build Confidence: Develop presentation and communication skills in a supportive environment.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Inspire Growth: Foster a love for learning and exploration in mathematics.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Enhance Skills: Improve logical reasoning, creativity, and collaboration.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Cultivate Community: Connect with classmates and teachers through shared experiences.</span></div>
            
                    <div><br><strong style='color:#000000'>Event Details:</strong></div>
                    <div><span style='color:#333333'>🗓️ Date: 22 June, 2024</span></div>
                    <div><span style='color:#333333'>🕒 Time: 01:45 PM</span></div>
                    <div><span style='color:#333333'>📍 Location: Room 301, 123 Greenwood St, Greenwood District, Austin, TX 78701</span></div>
            
                    <div><br><strong style='color:#000000'>Join the Journey of Mathematical Exploration!</strong></div>
                    <div><span style='color:#333333'>Discover the joy of learning and the beauty of mathematics. Be part of a class that celebrates curiosity, creativity, and collaboration!</span></div>
                ",
            
                'feature_image' => "public/theme/edulia/img/gallery/large/2.png",
                'gallery_image' => null,
                'position' => 1,
            ],

            [
                'parent_id' => null,
                'name' => 'Explore Your Academic Horizons: A Learning Fiesta for Everyone!',
            
               'description' => "
                    <div><font color='#000000'>Dive into a world of knowledge and discovery at our Academic Expo tailored for individuals of all roles in education. Whether you're a teacher, student, administrator, or industry enthusiast, this expo promises to be an exciting platform for learning, connecting, and advancing.</font></div>
                    <div><font color='#000000'><b>What is In Store:</b></font></div>

                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#000000'>1. Empowering Educators:</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Immerse yourself in innovative teaching techniques and tech advancements.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Attend workshops led by renowned education experts.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Discover resources to spice up your classrooms and engage students.</font></div>
                    <div>&nbsp;</div>
                    <div><b>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#000000'>2. Student Zone Extravaganza:</font></b></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Get career guidance and insights in student-centric sessions.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Explore academic programs and cool extracurricular opportunities.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Engage in interactive exhibits igniting a passion for learning.</font></div>
                    <div>&nbsp;</div>
                    <div><b>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#000000'>3. Admin Brilliance Unleashed:</font></b></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Check out the latest and greatest in school management systems.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Mix and mingle with fellow administrators.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Grab strategies to streamline administrative tasks and boost efficiency.</font></div>
                    <div>&nbsp;</div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#000000'><b>4. Industry Vibes:</b></font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Connect with pros to bridge the gap between school and real-world scenarios.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Chat about aligning academics with industry needs.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- See successful collaborations between the academic and business worlds.</font>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div><font color='#000000'><b>Why Be There?</b></font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Network Power: Build connections with educators, students, administrators, and industry buffs.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Innovation Parade: Spot the newest trends shaping the future of education.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Skill Boost: Pick up fresh skills and knowledge to rock your role in the education community.</font></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<font color='#333333'>- Team Up for Learning: Swap ideas, success tales, and best practices with peers from various educational realms.</font></div>
                    <div>&nbsp;</div>
                    <div><font color='#000000'><b>Event Dates:</b></font></div>
                    <div><font color='#000000'>🗓️ Date: 10 June, 2024</font></div>
                    <div><font color='#000000'>🕒 Time: 02 : 00 PM</font></div>
                    <div><font color='#000000'>📍 Location: 123 Greenwood St, Greenwood District, Austin, TX 78701</font></div>
                    <div>&nbsp;</div>
                    <div><font color='#000000'><b>Claim Your Spot - RSVP Now!</b></font></div>
                    <div><font color='#000000'>Join us at the Academic Fiesta for Everyone and become part of an exhilarating and collaborative learning experience. Reserve your spot now to unlock new possibilities in the world of education. Let's shape the future of academia together!</font></div>
                ",

                'feature_image' => "public/theme/edulia/img/gallery/large/1.png",
                'gallery_image' => null,
                'position' => 2,
            ],

            [
                'parent_id' => Null,
                'name' => 'Dive into the World of Words: Language and Literature Fiesta!',
            
                'description' => '
                    <div><font color="#000000">Dive into a world of knowledge and discovery at our Academic Expo tailored for individuals of all roles in education. Whether you\'re a teacher, student, administrator, or industry enthusiast, this expo promises to be an exciting platform for learning, connecting, and advancing.</font></div>
                    <div><font color="#9c9c9c"><br></font><font color="#000000"><b>What\'s In Store:</b>&nbsp;</font> &nbsp;</div>
                    <div><b><font color="#000000">&nbsp;&nbsp;&nbsp;1. Empowering educators:</font></b></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Immerse yourself in innovative teaching techniques and tech advancements.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Attend workshops led by renowned education experts.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Discover resources to spice up your classrooms and engage students.</font></div>
                    <div><font color="#9c9c9c">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font></div>
                    <div><b><font color="#000000">&nbsp;&nbsp;&nbsp;2. Student Zone Extravaganza:</font></b></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Get career guidance and insights in student-centric sessions.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Explore academic programs and cool extracurricular opportunities.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Engage in interactive exhibits igniting a passion for learning.</font></div>
                    <div><font color="#9c9c9c">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font></div>
                    <div><b><font color="#000000">&nbsp;&nbsp;&nbsp;&nbsp;3. Admin Brilliance Unleashed:</font></b></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Check out the latest and greatest in school management systems.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mix and mingle with fellow administrators.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Grab strategies to streamline administrative tasks and boost efficiency.</font></div>
                    <div><font color="#000000">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font></div>
                    <div><font color="#000000">&nbsp;&nbsp;&nbsp;&nbsp;<b>4. Industry Vibes:</b></font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Connect with pros to bridge the gap between school and real-world scenarios.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Chat about aligning academics with industry needs.</font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- See successful collaborations between the academic and business worlds.</font></div>
                    <div><font color="#000000"><br></font></div>
                    <div><b><font color="#000000">Why Be There?</font></b></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;- Network Power: Build connections with educators, students, administrators, and industry buffs.<br></font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;- Innovation Parade: Spot the newest trends shaping the future of education.<br></font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;- Skill Boost: Pick up fresh skills and knowledge to rock your role in the education community.<br></font></div>
                    <div><font color="#424242">&nbsp;&nbsp;&nbsp;&nbsp;- Team Up for Learning: Swap ideas, success tales, and best practices with peers from various educational realms.</font><br></div>
                    <div><font color="#000000">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font></div>
                    <div><b><font color="#000000">Event Dates:</font></b></div>
                    <div><font color="#000000">🗓️<b> Date: </b></font><font color="#424242">10 June, 2024</font></div>
                    <div><font color="#000000">🕒<b> Time: </b></font><font color="#424242">02 : 00 PM</font></div>
                    <div><font color="#000000">📍 <b>Location:</b> </font><font color="#424242">123 Greenwood St, Greenwood District, Austin, TX 78701</font></div>
                    <div><font color="#000000">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</font></div>
                    <div><b><font color="#000000">Claim Your Spot - RSVP Now!</font></b></div>
                    <div><font color="#000000">Join us at the Academic Fiesta for Everyone and become part of an exhilarating and collaborative learning experience. Reserve your spot now to unlock new possibilities in the world of education. Let\'s shape the future of academia together!</font></div>
                ',
            
                'feature_image' => "public/theme/edulia/img/gallery/large/3.png",
                'gallery_image' => Null,
                'position' => 3,
            ],
                        
            [
                'parent_id' => Null,
                'name' => 'Embrace Change: Environmental Awareness Day!',
                
                'description' => "
                    <div><span style='color:#000000'>Gear up for a day devoted to raising awareness, inspiring action, and celebrating our shared commitment to the environment – it's Environmental Awareness Day! This event unites communities, individuals, and organizations in a collective effort to promote sustainable practices and safeguard our planet for future generations.</span></div>

                    <div><br><strong style='color:#000000'>Event Highlights:</strong></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>1. Informative Sessions:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Engage in enlightening sessions on sustainable living, conservation, and the significance of eco-friendly choices.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Discover how small, everyday actions can collectively contribute to a healthier environment.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>2. Eco-Friendly Showcase:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Explore exhibits featuring green products, sustainable technologies, and local eco-friendly initiatives.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Connect with environmental organizations to learn about ongoing projects and ways to participate.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>3. Nature Appreciation Activities:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Immerse yourself in the beauty of nature through guided nature walks that highlight local ecosystems.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Participate in community clean-up drives, contributing directly to the preservation of our natural surroundings.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>4. Expert Talks:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Listen to influential environmentalists and experts sharing insights on global environmental challenges and solutions.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Gain a deeper understanding of the importance of environmental conservation.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>5. Art for Change:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Experience visually compelling art installations conveying powerful messages about environmental issues.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Join interactive art projects symbolizing our collective responsibility to nurture and protect our planet.</span></div>

                    <div><br><strong style='color:#000000'>Why Participate?</strong></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Learn and Act: Gain practical knowledge and insights to incorporate eco-friendly practices into your daily life.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Community Connection: Connect with like-minded individuals and organizations committed to environmental stewardship.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Hands-On Impact: Contribute directly to positive environmental change through participation in clean-up initiatives.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Inspiration to Adopt Change: Be inspired by experts and fellow participants to make sustainable choices for a greener future.</span></div>

                    <div><br><strong style='color:#000000'>Event Details:</strong></div>
                    <div><span style='color:#333333'>🗓️ Date: 14 Sept, 2024</span></div>
                    <div><span style='color:#333333'>🕒 Time: 10:45 AM</span></div>
                    <div><span style='color:#333333'>📍 Location: 890 Springfield Blvd, Springfield Downtown, Nashville, TN 37203</span></div>

                    <div><br><strong style='color:#000000'>Become the Catalyst for Change - RSVP Now!</strong></div>
                    <div><span style='color:#333333'>Join us at Environmental Awareness Day and play a role in promoting a sustainable and eco-conscious future. Reserve your spot now to be part of this impactful initiative!</span></div>
                ",

                'feature_image' => "public/theme/edulia/img/gallery/large/4.png",
                'gallery_image' => Null,
                'position' => 4,
            ],

            [
                'parent_id' => Null,
                'name' => 'Back-to-School Celebration: A Journey of Learning Begins!',
                
                'description' => "
                    <div><span style='color:#000000'>Get ready to celebrate the beginning of a new academic year with enthusiasm and excitement! Our Back-to-School event brings students, parents, and educators together to mark the start of a rewarding journey filled with learning, growth, and inspiration.</span></div>
            
                    <div><br><strong style='color:#000000'>Event Highlights:</strong></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>1. Welcome Activities:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Engage in interactive ice-breaker games to foster new friendships among students.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Join a guided school tour to get familiar with the campus and facilities.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>2. Parent-Teacher Meet:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Attend informative sessions about school policies, academic expectations, and extracurricular activities.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Participate in Q&A sessions to address any concerns or queries.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>3. Student Talent Show:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Witness incredible performances by students showcasing their unique talents.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Encourage creativity and confidence among young learners.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>4. Academic Goals Workshop:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Learn effective strategies for setting and achieving academic goals.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Discover tools and techniques for improving study habits and time management.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>5. Community Networking:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Connect with fellow parents, teachers, and community leaders.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Build a supportive network to ensure a successful school year.</span></div>
            
                    <div><br><strong style='color:#000000'>Why Join?</strong></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Start the year on a positive note by building meaningful connections.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Equip yourself with resources and information for a successful academic journey.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Encourage children to embrace learning with enthusiasm and confidence.</span></div>
            
                    <div><br><strong style='color:#000000'>Event Details:</strong></div>
                    <div><span style='color:#333333'>🗓️ Date: 5 Sept, 2024</span></div>
                    <div><span style='color:#333333'>🕒 Time: 9:00 AM</span></div>
                    <div><span style='color:#333333'>📍 Location: Greenfield Elementary, 1234 Pine Avenue, Los Angeles, CA 90025</span></div>
            
                    <div><br><strong style='color:#000000'>Let’s Kick Off the Year with Excitement – Join Us!</strong></div>
                    <div><span style='color:#333333'>Be part of this special event to inspire young minds, foster connections, and set the stage for a fantastic school year. We look forward to seeing you there!</span></div>
                ",
            
                'feature_image' => "public/uploads/news/news6.jpg",
                'gallery_image' => Null,
                'position' => 5,
            ],

            [
                'parent_id' => Null,
                'name' => 'Exploring the Microscopic World: A Hands-On Science Workshop',
                
                'description' => "
                    <div><span style='color:#000000'>Dive into the fascinating world of science with our hands-on microscopy workshop! This engaging session allows students to explore the unseen wonders of biology, inspiring curiosity and critical thinking.</span></div>
                
                    <div><br><strong style='color:#000000'>Workshop Highlights:</strong></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>1. Introduction to Microscopy:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Learn the basics of using a microscope and its importance in scientific discovery.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Understand the different types of microscopes and their applications.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>2. Hands-On Exploration:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Examine prepared slides of plant and animal cells.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Discover the intricate details of microorganisms under the lens.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>3. Guided Experiments:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Perform live observations with guidance from expert instructors.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Record and analyze findings in a scientific journal.</span></div>
                    
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#000000'>4. Q&A with Scientists:</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Interact with scientists to learn more about their research and career paths.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Get inspired to pursue science with passion and curiosity.</span></div>
                
                    <div><br><strong style='color:#000000'>Why Attend?</strong></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Cultivate a love for science through interactive learning.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Gain practical skills and knowledge in scientific observation.</span></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#333333'>- Encourage critical thinking and discovery among young minds.</span></div>
                
                    <div><br><strong style='color:#000000'>Event Details:</strong></div>
                    <div><span style='color:#333333'>🗓️ Date: 15 Sept, 2024</span></div>
                    <div><span style='color:#333333'>🕒 Time: 10:00 AM</span></div>
                    <div><span style='color:#333333'>📍 Location: Discovery Science Center, 5678 Innovation Road, San Francisco, CA 94111</span></div>
                
                    <div><br><strong style='color:#000000'>Join the Journey of Discovery – Register Today!</strong></div>
                    <div><span style='color:#333333'>Be part of this educational adventure that opens the door to endless possibilities in the world of science. We can’t wait to see you there!</span></div>
                ",
            
                'feature_image' => "public/uploads/news/news7.jpg",
                'gallery_image' => Null,
                'position' => 6,
            ],
        ]);
    }
}
