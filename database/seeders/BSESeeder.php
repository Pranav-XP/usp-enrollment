<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BSESeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of courses with required details
        $courses = [
            ['code' => 'CS111', 'title' => 'Introduction to Computing Science', 'year' => 1, 'cost' => 500, 'semester_1' => true, 'semester_2' => false, 'description' => 'An introduction to computer programming and basic computer organization, covering I/O, storage, and the CPU. Students will learn problem-solving and algorithms using a modern high-level programming language. No prior computing experience is required, as foundational knowledge of PCs, Windows, and the programming environment will be taught early in the course.'],
            ['code' => 'CS112', 'title' => 'Data Structures & Algorithms', 'year' => 1, 'cost' => 500, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course teaches software development using C++, with concepts applicable across programming languages. Students will learn syntax, logic, and key data structures like arrays, stacks, queues, and trees, along with algorithms such as searching, sorting, and recursion. Emphasis is placed on writing efficient, object-oriented code using suitable data structures and algorithms.'],
            ['code' => 'CS140', 'title' => 'Introduction to Software Engineering', 'year' => 1, 'cost' => 450, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course introduces the fundamentals of Software Engineering, covering the software development life cycle, development models, requirements elicitation, design, and testing techniques. Students will gain a basic understanding of each phase in the software development process.'],
            ['code' => 'MA111', 'title' => 'Calculus 1 & Linear Algebra 1', 'year' => 1, 'cost' => 400, 'semester_1' => true, 'semester_2' => true, 'description' => 'This course covers Calculus (limits, derivatives, exponential/logarithmic functions, integration) and Linear Algebra (systems of equations, Gaussian elimination, matrices, determinants). Note: Students without recent math experience must complete MAF12 before enrolling.'],
            ['code' => 'MA161', 'title' => 'Discrete Mathematics 1', 'year' => 1, 'cost' => 400, 'semester_1' => false, 'semester_2' => true, 'description' => 'Discrete Mathematics explores structures that are not continuous, covering topics like logic, proofs, functions, set theory, Boolean algebra, algorithms, number theory, induction, recursion, counting, probability, and generating functions. MA161 is compulsory for Computer Science majors and recommended for students in Mathematics, Information Systems, Engineering, or Physics.'],
            ['code' => 'MG101', 'title' => 'Introduction to Management', 'year' => 1, 'cost' => 300, 'semester_1' => true, 'semester_2' => true, 'description' => 'This course covers descriptive statistics, probability, hypothesis testing, and correlation/regression, including binomial/normal distributions and significance tests. Cannot be credited with ST130.'],
            ['code' => 'ST131', 'title' => 'Introduction to Statistics', 'year' => 1, 'cost' => 400, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers descriptive statistics, probability theory, hypothesis testing, and correlation/regression. Topics include data collection, probability measures, binomial/normal distributions, t- and chi-square tests, and significance tests. Cannot be credited with ST130.'],
            ['code' => 'UU100A', 'title' => 'Communications & Information Literacy', 'year' => 1, 'cost' => 200, 'semester_1' => true, 'semester_2' => true, 'description' => 'This course focuses on developing students ability to locate, access, evaluate, and use information efficiently, aligned with the Research Skills Development (RSD) framework.'],
            ['code' => 'UU114', 'title' => 'English Language Skills for Tertiary Studies', 'year' => 1, 'cost' => 200, 'semester_1' => true, 'semester_2' => true, 'description' => 'This core course, required in the first year, enhances listening, reading, speaking, and writing skills in academic English. It involves critical engagement with relevant materials and includes an independent research assignment related to students own disciplines.'],

            ['code' => 'CS211', 'title' => 'Computer Organisation', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers computer systems and architecture, including data representation, digital logic, microarchitecture, instruction set architecture, operating systems, and assembly/machine language programming.'],
            ['code' => 'CS214', 'title' => 'Design & Analysis of Algorithms', 'year' => 2, 'cost' => 550, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers fundamental algorithms (dynamic programming, divide and conquer, greedy approach) for sorting, searching, and optimization. It also addresses data structure selection, time complexity, and computability.'],
            ['code' => 'CS218', 'title' => 'Mobile Computing', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course provides an overview of mobile computing systems, covering mobile telephony, data and wireless networks, and the design of mobile applications. Students will gain hands-on experience with at least one mobile app development framework.'],
            ['code' => 'CS219', 'title' => 'Cloud Computing', 'year' => 2, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers cloud computing services, privacy, security, standards, and user accessibility, along with cloud deployment models and on-demand resource sharing.'],
            ['code' => 'CS230', 'title' => 'Requirements Engineering', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers requirement elicitation techniques, analysis, validation, and prioritization. Students will prepare software requirement specifications and perform initial design, gaining skills in selecting appropriate requirement gathering methods.'],
            ['code' => 'CS241', 'title' => 'Software Design & Implementation', 'year' => 2, 'cost' => 550, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers the design and implementation phases of the software development lifecycle, focusing on quality assurance, testing, documentation, and systematic approaches. It includes project-based labs using both large and small-scale development methodologies.'],
            ['code' => 'IS221', 'title' => 'Web Applications Development', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course provides a practical foundation in high-level languages for web development, covering web design concepts and development tools. Students will be able to create a functional dynamic website upon completion.'],
            ['code' => 'IS222', 'title' => 'Database Managment Systems', 'year' => 2, 'cost' => 550, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers relational database management systems, database design, SQL for data management, and topics like data security, sharing, and integrity. Students will be able to administer a database upon completion.'],
            ['code' => 'UU200', 'title' => 'Ethics & Governance', 'year' => 2, 'cost' => 250, 'semester_1' => true, 'semester_2' => true, 'description' => 'This course explores ethics and governance, covering ethical theories and their application to self, political, corporate, and global governance. It delves into applied ethics, including law, corporate ethics, social justice, and ethical dilemmas, encouraging critical thinking and responsible decision-making.'],

            // Year III
            ['code' => 'CS310', 'title' => 'Computer Networks', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers fundamental concepts of modern computer networks, focusing on TCP/IP, with emphasis on network and transport layers, IP addressing, and routing.'],
            ['code' => 'CS311', 'title' => 'Operating Systems', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers the architecture of operating systems like UNIX, Windows, iOS, and Android, focusing on resource allocation, process management, memory management, and file handling. It helps students understand the functionality and services of operating systems.'],
            ['code' => 'CS324', 'title' => 'Distributed Computing', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers distributed systems, including web and database applications, and topics like Distributed Objects, Interprocess Communications, and Multi-tier Architecture, with applications such as file sharing and content delivery.'],
            ['code' => 'CS341', 'title' => 'Software Quality Assurance & Testing', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers software verification, validation, quality assurance metrics, testing tools, and creating quality plans and risk assessments, using case studies.'],
            ['code' => 'CS352', 'title' => 'Cybersecurity Principles', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers information assurance, cybersecurity threats, defensive controls, and risk management, providing foundational knowledge for managers and decision-makers in various organizations.'],
            ['code' => 'IS314', 'title' => 'Computing Project', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This project-based course applies software engineering techniques to develop a real-world ICT project. Small teams create a software system and provide complete documentation and the system itself as deliverables.'],
            ['code' => 'IS328', 'title' => 'Data Mining', 'year' => 3, 'cost' => 600, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course introduces data mining concepts, methods, and algorithms, with a focus on practical applications. Students gain hands-on experience using data mining software.'],
            ['code' => 'IS333', 'title' => 'Project Management', 'year' => 3, 'cost' => 600, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers foundational project management knowledge based on the PMBOK, focusing on managing projects to create unique products, services, or results. It includes real-world case studies and covers key principles, tools, and processes for effective project planning and documentation in business and government sectors.'],

            ['code' => 'CS415', 'title' => 'Artificial Intelligence', 'year' => 4, 'cost' => 700, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course introduces advanced concepts in software design, management, measurement, metrics, and testing, focusing on quality assessment and process improvement. It emphasizes using software metrics to understand and enhance software and development processes.'],
            ['code' => 'CS403', 'title' => 'Cyber Defence: Governance & Risk Management', 'year' => 4, 'cost' => 700, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course focuses on cybercrime operations and the implementation of governance policies for information systems. It covers system selection, security assurance, and management strategies. Key topics include risk management, cyberattack mitigation, and the application of these policies in e-commerce and e-health sectors.'],
            ['code' => 'CS412', 'title' => 'Artificial Intelligence', 'year' => 4, 'cost' => 750, 'semester_1' => true, 'semester_2' => false, 'description' => 'This course covers AI topics like data science, machine learning, robotics, and pattern recognition, with a focus on algorithms and key research. It also includes evolutionary computation, neural networks, and fuzzy logic.'],
            ['code' => 'CS424', 'title' => 'Big Data Technologies', 'year' => 4, 'cost' => 750, 'semester_1' => false, 'semester_2' => true, 'description' => 'This course covers the fundamentals of big data, its primary sources, and distributed technologies. It includes in-depth study of the Hadoop Ecosystem, Spark framework, and column-based databases like HBase and Cassandra, with a project to solve a real-life big data problem.'],
            ['code' => 'CS400', 'title' => 'Industry Experience Project', 'year' => 4, 'cost' => 1000, 'semester_1' => false, 'semester_2' => true, 'description' => 'This capstone course for BNS and BSE final-year students offers a practical environment to develop both professional and generic skills aligned with the SFIA framework. Students will work on real-life ICT projects provided by clients in the Pacific region, including SMEs, community groups, and NGOs.'],
        ];


        // Insert courses and attach to program
        foreach ($courses as $course) {
            $courseId = DB::table('courses')->insertGetId([
                'course_code' => $course['code'],
                'course_title' => $course['title'],
                'year' => $course['year'],
                'description' => $course['description'],
                'cost' => $course['cost'],
                'semester_1' => $course['semester_1'],
                'semester_2' => $course['semester_2'],
                'created_at' => now(),
                'updated_at' => now(),
                'lecturer_name' => fake()->name(),
            ]);

            // Insert into pivot table
            DB::table('course_program')->insert([
                'course_id' => $courseId,
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
