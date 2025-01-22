<?php

namespace Database\Seeders\FrontendCMS;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SmCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sm_courses')->delete();

        DB::table('sm_course_categories')->insert([
            [
                'category_name' => 'Academic',
                'school_id' => 1,
            ],
            [
                'category_name' => 'Professional Development',
                'school_id' => 1,
            ],
            [
                'category_name' => 'Personal Development',
                'school_id' => 1,
            ],
            [
                'category_name' => 'Technical Skills',
                'school_id' => 1,
            ],
        ]);

        DB::table('sm_courses')->insert([
            [
                'title' => 'MERN Full Stack Web Development',
                'image' => 'public/uploads/theme/edulia/course/1.jpg',
                'category_id' => 2,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Dive into the world of web development with our MERN Full Stack Web Development Course. Designed for beginners, this course provides an interactive and practical approach to mastering MongoDB, Express.js, React.js, and Node.js. Develop your skills, build dynamic web applications, and kickstart your journey as a full-stack developer.
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- Comprehensive understanding of the MERN stack</li>
                            <li>- Hands-on projects to enhance real-world skills</li>
                            <li>- Modern best practices for front-end and back-end development</li>
                            <li>- Responsive design and API integration</li>
                            <li>- Career-focused guidance and mentorship</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The MERN Full Stack Web Development course is divided into several modules to ensure a structured and comprehensive learning experience. Gain hands-on experience with project-based learning to solidify your skills in both front-end and back-end development.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Front-End Development:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> React.js Basics</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> State and Props Management</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Component Lifecycle</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Building Reusable Components</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Integrating APIs in React</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Back-End Development:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Node.js Fundamentals</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> RESTful APIs with Express.js</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> MongoDB for Database Management</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Authentication and Authorization</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Deployment and Scaling</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            No prior coding experience is required, but basic knowledge of HTML, CSS, and JavaScript will be helpful.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A laptop or computer with an internet connection</li>
                            <li>- Enthusiasm to learn and explore web development</li>
                            <li>- Basic understanding of how the web works</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Our experienced instructors bring a wealth of knowledge and industry expertise to the table.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. John Doe - Expert in React.js and Modern JavaScript</li>
                            <li>2. Jane Smith - Backend Developer specializing in Node.js</li>
                            <li>3. Alex Johnson - Database Architect and MongoDB Specialist</li>
                            <li>4. Sarah Lee - Full Stack Developer with MERN expertise</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "This course is an excellent starting point for anyone looking to become a full-stack developer. The hands-on projects and detailed instruction helped me build my first web application. Highly recommend!" - Anna M.
                        </p>
                        <p class="text-muted">
                            "I loved the practical approach of this course. The instructors were very knowledgeable and supportive." - Michael R.
                        </p>
                    </div>
                ',
                
                'active_status' => 1,
            ],
            
            [
                'title' => 'Mastering Laravel',
                'image' => 'public/uploads/theme/edulia/course/5.jpg',
                'category_id' => 2,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Master the art of web development with Laravel, one of the most popular PHP frameworks. This course is perfect for those who want to dive deep into Laravel, from basic to advanced concepts, and build robust and scalable applications. Learn how to leverage Laravel’s powerful features to create professional web applications with ease.
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- In-depth understanding of Laravel framework</li>
                            <li>- Hands-on projects to enhance real-world skills</li>
                            <li>- Master database migrations, Eloquent ORM, and query building</li>
                            <li>- Build RESTful APIs and integrate third-party services</li>
                            <li>- Advanced concepts like middleware, job queues, and testing</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The Mastering Laravel course is structured to provide a deep understanding of the framework, along with project-based learning to solidify your skills in Laravel development.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Basic Concepts:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Introduction to Laravel</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Routing and Controllers</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Blade Templating Engine</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Database Migrations and Seeding</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Eloquent ORM and Relationships</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Advanced Concepts:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Middleware and Request Lifecycle</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Job Queues and Event Broadcasting</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> RESTful APIs with Laravel</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Authentication and Authorization</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Testing and Debugging in Laravel</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            Basic understanding of PHP and web development is recommended. Prior knowledge of object-oriented programming (OOP) will be beneficial.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A laptop or computer with an internet connection</li>
                            <li>- Basic knowledge of PHP and OOP concepts</li>
                            <li>- Understanding of HTML, CSS, and JavaScript</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Our instructors are seasoned Laravel developers with years of industry experience. They bring practical knowledge and insights to guide you through the learning process.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. Mark Taylor - Senior Laravel Developer and Architect</li>
                            <li>2. Emily Clark - PHP Expert and Laravel Specialist</li>
                            <li>3. David Lee - Backend Developer with a focus on API Development</li>
                            <li>4. Laura King - Laravel Developer and DevOps Specialist</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "The best Laravel course I’ve taken! I learned everything from setting up a basic Laravel application to building complex systems. The real-world examples were extremely helpful." - John D.
                        </p>
                        <p class="text-muted">
                            "I’ve been using Laravel for a while, but this course took my skills to the next level. The advanced topics were explained clearly and concisely." - Sarah P.
                        </p>
                    </div>
                ',
                
                'active_status' => 1,
            ],
            
            [
                'title' => 'Mastering Docker',
                'image' => 'public/uploads/theme/edulia/course/6.jpg',
                'category_id' => 2,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Dive into the world of containerization with Docker! This course is designed to help you master Docker from the ground up. You’ll learn how to build, deploy, and scale applications using containers, optimizing your development workflow and enhancing your ability to manage software environments efficiently. 
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- Deep understanding of Docker and containerization concepts</li>
                            <li>- Hands-on experience with Docker containers and Docker Compose</li>
                            <li>- Learn how to deploy applications in isolated environments</li>
                            <li>- Implement Docker in real-world use cases for development and production</li>
                            <li>- Advanced Docker topics, including multi-stage builds and networking</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The Mastering Docker course is divided into comprehensive modules, each designed to give you a thorough understanding of Docker and containerization. The course combines theoretical knowledge with hands-on projects to help you implement Docker in real-world scenarios.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Docker Basics:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Introduction to Containers and Docker</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Installing Docker and Setup</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Docker Images and Containers</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Running and Managing Containers</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Docker Commands and Best Practices</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Advanced Docker Concepts:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Docker Compose for Multi-Container Applications</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Docker Networking and Volumes</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Multi-Stage Builds in Docker</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Docker Swarm and Orchestration</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> CI/CD with Docker</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            This course assumes basic knowledge of software development and systems administration. No prior Docker experience is required.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A laptop or computer with an internet connection</li>
                            <li>- Familiarity with basic development tools (e.g., text editor, command-line interface)</li>
                            <li>- Basic understanding of Linux/Unix commands is recommended</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Our instructors are experienced professionals who have worked with Docker in large-scale production environments. They’ll guide you through the course with a hands-on approach, offering insights into best practices and advanced concepts.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. Peter Harris - DevOps Engineer with extensive Docker experience</li>
                            <li>2. Lisa White - Cloud Infrastructure Specialist and Docker Expert</li>
                            <li>3. Mark Robinson - Software Engineer with expertise in containerization and orchestration</li>
                            <li>4. James Green - Senior Developer focusing on scalable microservices architectures</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "The best Docker course I’ve taken! It’s clear, practical, and filled with examples that helped me implement Docker in production environments. Highly recommend!" - Brian L.
                        </p>
                        <p class="text-muted">
                            "This course made Docker easy to understand. The hands-on approach helped me build real-world applications using Docker containers." - Emily S.
                        </p>
                    </div>
                ',
                
                'active_status' => 1,
            ],

            [
                'title' => 'Creative Design Mastery: From Basics to Professional Graphics',
                'image' => 'public/uploads/theme/edulia/course/7.jpg',
                'category_id' => 4,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Unleash your creativity with our comprehensive Graphic Design course! This course is designed to help you master the principles, tools, and techniques of graphic design. Whether you’re a beginner or looking to enhance your skills, you’ll learn to create stunning visuals, professional designs, and effective branding materials.
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- Deep understanding of design principles and color theory</li>
                            <li>- Hands-on projects to build your design portfolio</li>
                            <li>- Learn industry-standard tools like Adobe Photoshop, Illustrator, and Figma</li>
                            <li>- Master typography, layout design, and branding techniques</li>
                            <li>- Explore advanced topics like UI/UX design and motion graphics</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The Creative Design Mastery course is divided into structured modules that focus on both the theoretical and practical aspects of graphic design. From basic design principles to advanced tools and techniques, this course is perfect for aspiring graphic designers.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Graphic Design Essentials:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Introduction to Graphic Design</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Design Principles and Color Theory</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Basics of Adobe Photoshop</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Working with Adobe Illustrator</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Typography and Layout Design</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Advanced Graphic Design Concepts:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> UI/UX Design with Figma</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Branding and Identity Design</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Advanced Photoshop and Illustrator Techniques</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Motion Graphics Basics</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Portfolio Development</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            This course is designed for all skill levels, from beginners to experienced designers. Basic familiarity with computers is required.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A computer with a stable internet connection</li>
                            <li>- Adobe Creative Cloud (Photoshop, Illustrator) or equivalent tools</li>
                            <li>- A passion for creativity and design</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Our instructors are professional graphic designers with years of experience in the design industry. They’ll guide you through every step of the course, ensuring you gain both technical skills and artistic confidence.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. Emily Carter - Graphic Designer with expertise in branding and identity</li>
                            <li>2. Alex Johnson - UI/UX Specialist with a background in product design</li>
                            <li>3. Sarah Lee - Illustrator and Photoshop Guru with 10+ years of experience</li>
                            <li>4. David Martin - Motion Graphics Artist specializing in animation</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "A must-take course for anyone starting their graphic design journey! The hands-on projects were incredibly helpful." - Jessica P.
                        </p>
                        <p class="text-muted">
                            "The instructors were fantastic! I especially loved the sections on UI/UX and motion graphics." - Daniel T.
                        </p>
                    </div>
                ',
            
                'active_status' => 1,
            ],
            
            [
                'title' => 'SQA Mastery: Ensuring Excellence in Software Quality',
                'image' => 'public/uploads/theme/edulia/course/8.jpg',
                'category_id' => 5,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Ensure the quality and reliability of software products with our comprehensive Software Quality Assurance (SQA) course. This course equips you with the essential skills and techniques to excel in the field of software testing and quality assurance. From foundational principles to advanced testing methodologies, you’ll gain the expertise needed to deliver defect-free and high-performing software solutions.
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- Learn the fundamentals of Software Quality Assurance</li>
                            <li>- Master manual and automated testing techniques</li>
                            <li>- Gain hands-on experience with popular testing tools like Selenium and JIRA</li>
                            <li>- Understand test planning, execution, and defect tracking</li>
                            <li>- Explore advanced topics like performance testing, security testing, and CI/CD integration</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The SQA Mastery course is designed to provide you with in-depth knowledge and practical skills. The course is structured into modules covering everything from basic SQA concepts to advanced testing frameworks and real-world implementations.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">SQA Fundamentals:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Introduction to Software Quality Assurance</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Understanding the Software Development Life Cycle (SDLC)</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Basics of Manual Testing</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Writing Effective Test Cases</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Test Plan and Strategy Development</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Advanced Testing Concepts:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Automated Testing with Selenium</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Performance and Load Testing with JMeter</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Security Testing Basics</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Bug Reporting and Defect Management using JIRA</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> CI/CD Integration in Testing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            This course is suitable for beginners and professionals looking to enhance their skills. Basic knowledge of software development and tools is recommended but not mandatory.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A computer with an internet connection</li>
                            <li>- Familiarity with basic software concepts</li>
                            <li>- Passion for ensuring software quality</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Learn from seasoned SQA professionals with extensive experience in software testing and quality assurance. They’ll guide you through the practical and theoretical aspects of SQA, ensuring you’re industry-ready.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. Maria Smith - Senior QA Engineer with expertise in automation</li>
                            <li>2. Kevin Brown - Performance Testing Specialist with a focus on large-scale systems</li>
                            <li>3. Laura Wilson - Security Testing Expert and QA Consultant</li>
                            <li>4. Michael Clark - QA Lead with experience in CI/CD and agile testing practices</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "A perfect course for both beginners and experienced testers! The practical approach made it easy to grasp complex concepts." - John D.
                        </p>
                        <p class="text-muted">
                            "The instructors were excellent, and the course covered everything I needed to advance my career in QA." - Anna R.
                        </p>
                    </div>
                ',
            
                'active_status' => 1,
            ],
            
            
            [
                'title' => 'WordPress Theme Development',
                'image' => 'public/uploads/theme/edulia/course/3.jpg',
                'category_id' => 2,
            
                'overview' => '
                    <div class="container">
                        <h4 class="text-primary">Overview:</h4>
                        <p class="text-muted">
                            Unlock the power of WordPress theme development with this hands-on course! Designed for beginners and intermediate developers, this course will guide you through the essentials of building custom WordPress themes from scratch. Learn how to create themes with clean, reusable code, implement best practices, and customize WordPress to meet your needs.
                        </p>
                        <ul class="list-unstyled ml-4">
                            <li><strong>Course Highlights:</strong></li>
                            <li>- Learn how to create a custom WordPress theme from the ground up</li>
                            <li>- Understand WordPress theme structure and template files</li>
                            <li>- Master theme customization with the WordPress Customizer API</li>
                            <li>- Best practices for responsive, SEO-friendly theme development</li>
                            <li>- How to deploy and maintain WordPress themes on live websites</li>
                        </ul>
                    </div>
                ',
            
                'outline' => '
                    <div class="container mt-4">
                        <h4 class="text-success">Outline:</h4>
                        <p class="text-muted">
                            The WordPress Theme Development course is structured into modules that gradually take you from understanding basic WordPress theme structure to developing advanced, custom themes with full features and functionality.
                        </p>
                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-secondary">WordPress Theme Basics:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Understanding WordPress theme structure</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Creating a theme folder and basic files</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Using `index.php`, `style.css`, and `functions.php`</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Template Hierarchy and Template Tags</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Implementing WordPress Loop in themes</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Advanced Theme Development:</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Using the WordPress Customizer API</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Integrating widgets, menus, and custom post types</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Responsive design techniques for WordPress themes</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Optimizing themes for speed and SEO</li>
                                        <li class="mb-2"><i class="bi bi-check-circle-fill"></i> Deploying themes and ensuring theme updates</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ',
            
                'prerequisites' => '
                    <div class="container mt-4">
                        <h4 class="text-danger">Prerequisites:</h4>
                        <p class="text-muted">
                            This course is suitable for developers with a basic understanding of HTML, CSS, and PHP. No prior WordPress theme development experience is required.
                        </p>
                        <ul class="list-unstyled">
                            <li>- A laptop or computer with an internet connection</li>
                            <li>- Basic knowledge of HTML, CSS, and PHP</li>
                            <li>- Familiarity with WordPress installation and management is helpful but not required</li>
                        </ul>
                    </div>
                ',
            
                'resources' => '
                    <div class="container mt-4">
                        <h4 class="text-warning">Instructors:</h4>
                        <p class="text-muted">
                            Our instructors are experienced WordPress developers with a deep understanding of theme development. They will guide you through the process of creating high-quality WordPress themes, sharing insights from real-world projects.
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Course Instructors:</strong></li>
                            <li>1. Emily Taylor - Expert WordPress Developer with years of theme development experience</li>
                            <li>2. David Smith - Full Stack Developer specializing in WordPress and PHP</li>
                            <li>3. Sarah Jones - Front-End Developer focused on creating responsive WordPress themes</li>
                            <li>4. Mark Miller - WordPress developer and performance optimization specialist</li>
                        </ul>
                    </div>
                ',
            
                'stats' => '
                    <div class="container mt-4">
                        <h4 class="text-info">Reviews:</h4>
                        <p class="text-muted">
                            "This course provided me with all the knowledge I needed to build my first custom WordPress theme. The instructors were great and helped me troubleshoot every challenge along the way." - John K.
                        </p>
                        <p class="text-muted">
                            "I learned everything about WordPress theme development in this course, from setting up the theme to customizing it with advanced features. It’s a must for any WordPress developer!" - Laura D.
                        </p>
                    </div>
                ',
                
                'active_status' => 1,
            ],
            
        ]);
    }
}
