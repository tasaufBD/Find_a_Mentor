-- Demo login password for every seeded account:
-- Password: Demo@123

START TRANSACTION;
-- USERS

INSERT INTO users
    (user_id, full_name, email, password, role)
VALUES
    (1, 'Arafat Hossain', 'arafat.hossain@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (2, 'Nusrat Jahan', 'nusrat.jahan@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (3, 'Tanvir Ahmed', 'tanvir.ahmed@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (4, 'Mehedi Hasan', 'mehedi.hasan@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (5, 'Sadia Rahman', 'sadia.rahman@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (6, 'Rakibul Islam', 'rakibul.islam@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (7, 'Farhan Karim', 'farhan.karim@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),
    (8, 'Jannatul Ferdous', 'jannatul.ferdous@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'student'),

    (9, 'Nayeem Rahman', 'nayeem.rahman@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (10, 'Fahim Chowdhury', 'fahim.chowdhury@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (11, 'Samiul Haque', 'samiul.haque@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (12, 'Tahsin Ahmed', 'tahsin.ahmed@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (13, 'Mahiya Sultana', 'mahiya.sultana@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (14, 'Imran Kabir', 'imran.kabir@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (15, 'Shahriar Alam', 'shahriar.alam@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (16, 'Rifat Mahmud', 'rifat.mahmud@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),
    (17, 'Nadia Karim', 'nadia.karim@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'mentor'),

    (18, 'System Administrator', 'admin@example.com', '$2y$12$3UfnQr/1ZBpTofbpSBGyi.GAq6eUMc6Qf.D4tNAHG49Vf2T4OGB9S', 'admin');

-- STUDENT PROFILES

INSERT INTO student_profiles
    (user_id, university, department, academic_year, cgpa_range, skills,
     interests, career_goal, target_detail, experience)
VALUES
    (1, 'American International University-Bangladesh', 'Computer Science and Engineering', 3, '3.5-4.0',
     'C++, Python, DSA, Git',
     'Algorithms, software engineering, competitive programming',
     'FAANG',
     'Software Engineer at a major technology company',
     'Two academic projects in C++ and a small Python web scraper.'),

    (2, 'North South University', 'Computer Science and Engineering', 2, '3.0-3.5',
     'Python, SQL, Excel, Statistics',
     'Data analytics, business intelligence',
     'Research',
     'Build a research profile for an international graduate program',
     'Coursework projects using Python, pandas and basic statistics.'),

    (3, 'Bangladesh University of Engineering and Technology', 'Computer Science and Engineering', 4, '3.5-4.0',
     'C++, Java, DSA, Git, System Design',
     'Software engineering, backend development',
     'FAANG',
     'Software Engineering role at Google, Microsoft or Amazon',
     'Competitive programming experience and a final-year backend project.'),

    (4, 'University of Dhaka', 'Computer Science and Engineering', 3, '3.0-3.5',
     'Python, PyTorch, Machine Learning, NumPy',
     'Artificial intelligence, computer vision',
     'MS_Abroad',
     'Fully funded MS in Computer Science in the USA',
     'Built a CNN image-classification project for a university course.'),

    (5, 'BRAC University', 'Computer Science and Engineering', 3, '3.5-4.0',
     'Java, JavaScript, React, SQL',
     'Web development, startups, product engineering',
     'Startup',
     'Join or build a technology startup in Bangladesh',
     'Developed two web applications as course projects.'),

    (6, 'Rajshahi University of Engineering and Technology', 'Computer Science and Engineering', 4, '3.0-3.5',
     'C, Embedded C, Arduino, ESP32',
     'Embedded systems, robotics, IoT',
     'Other',
     'Become an embedded systems engineer',
     'Built an Arduino-based line-following robot and an ESP32 sensor project.'),

    (7, 'Chittagong University of Engineering and Technology', 'Electrical and Electronic Engineering', 4, '3.5-4.0',
     'C, MATLAB, Embedded C, Microcontrollers',
     'Robotics, embedded systems, electronics',
     'Research',
     'Pursue research in robotics and intelligent systems',
     'Final-year embedded control project and undergraduate research experience.'),

    (8, 'Jahangirnagar University', 'Computer Science and Engineering', 2, '2.5-3.0',
     'C, Python, HTML, CSS',
     'Programming fundamentals, web development',
     'MS_Abroad',
     'Improve academic profile and eventually apply abroad',
     'A few introductory programming and web development projects.');

-- MENTOR PROFILES

INSERT INTO mentor_profiles
    (user_id, university, department, cgpa_range, graduation_year,
     current_organization, current_position, skills, achievements,
     career_story, external_profile_url, goal_achieved,
     verification_status)
VALUES
    (9, 'American International University-Bangladesh', 'Computer Science and Engineering', '3.5-4.0',
     2022, 'Microsoft', 'Software Engineer',
     'C++, Python, DSA, Git, System Design',
     'Reached a software engineering role after internship and focused DSA preparation.',
     'Started university with a strong interest in programming, built several projects, practiced DSA consistently, completed an internship, and later joined a global software company.',
     'https://www.linkedin.com/in/example-nayeem',
     'FAANG',
     'verified'),

    (10, 'Bangladesh University of Engineering and Technology', 'Computer Science and Engineering', '3.5-4.0',
     2021, 'Amazon', 'Software Development Engineer',
     'C++, Java, DSA, Algorithms, System Design',
     'Completed competitive programming contests and secured an international software engineering position.',
     'Focused heavily on algorithms during university, participated in programming contests, worked on backend projects and prepared systematically for technical interviews.',
     'https://www.linkedin.com/in/example-fahim',
     'FAANG',
     'verified'),

    (11, 'North South University', 'Computer Science and Engineering', '3.0-3.5',
     2020, 'University of Toronto', 'PhD Student in Computer Science',
     'Python, PyTorch, Machine Learning, Research, LaTeX',
     'Published research papers and received funded admission for graduate study abroad.',
     'Started research during the final two years of undergraduate study, worked with faculty members, developed a strong research portfolio and applied to funded graduate programs.',
     'https://www.linkedin.com/in/example-samiul',
     'PhD_Abroad',
     'verified'),

    (12, 'University of Dhaka', 'Computer Science and Engineering', '3.0-3.5',
     2019, 'University of Illinois Urbana-Champaign', 'MS Student',
     'Python, Machine Learning, Computer Vision, NumPy',
     'Received funded research assistantship during graduate study.',
     'Had an average undergraduate CGPA but built a strong technical and research profile through projects, recommendation letters and research experience.',
     'https://www.linkedin.com/in/example-tahsin',
     'MS_Abroad',
     'verified'),

    (13, 'BRAC University', 'Computer Science and Engineering', '3.5-4.0',
     2022, 'Pathao', 'Software Engineer',
     'Java, JavaScript, React, Node.js, SQL',
     'Joined a leading Bangladeshi technology company after completing internships.',
     'Built web applications, participated in software engineering internships and gradually moved toward product development and startup environments.',
     'https://www.linkedin.com/in/example-mahiya',
     'Startup',
     'verified'),

    (14, 'Rajshahi University of Engineering and Technology', 'Electrical and Electronic Engineering', '3.0-3.5',
     2018, 'Walton Hi-Tech Industries', 'Embedded Systems Engineer',
     'Embedded C, C, STM32, Arduino, ESP32, PCB Design',
     'Worked on embedded control and consumer electronics projects in Bangladesh.',
     'Started with Arduino projects at university, moved to microcontrollers and embedded C, completed an industrial internship and entered the embedded systems industry.',
     'https://www.linkedin.com/in/example-imran',
     'Other',
     'verified'),

    (15, 'Chittagong University of Engineering and Technology', 'Electrical and Electronic Engineering', '3.5-4.0',
     2019, 'University of Melbourne', 'PhD Researcher',
     'MATLAB, Embedded C, Robotics, Control Systems, Research',
     'Built an undergraduate research portfolio and moved into funded robotics research.',
     'Combined embedded systems projects with undergraduate research before applying to robotics and intelligent systems programs abroad.',
     'https://www.linkedin.com/in/example-shahriar',
     'Research',
     'verified'),

    (16, 'Jahangirnagar University', 'Computer Science and Engineering', '2.5-3.0',
     2018, 'Freelance / Software Consultancy', 'Software Engineer',
     'Python, Django, JavaScript, SQL, Git',
     'Built a successful freelance software career despite starting with a modest CGPA.',
     'Focused on practical skills and portfolio projects rather than relying only on grades, started freelancing during university and later moved into software consultancy.',
     'https://www.linkedin.com/in/example-rifat',
     'Other',
     'pending'),

    (17, 'University of Chittagong', 'Computer Science and Engineering', '3.0-3.5',
     2021, 'Grameenphone', 'Data Analyst',
     'Python, SQL, Power BI, Statistics, Excel',
     'Moved from academic projects into a data analytics role at a major Bangladeshi organization.',
     'Built analytics projects, learned SQL and visualization tools, completed an internship and transitioned into a data-focused industry role.',
     'https://www.linkedin.com/in/example-nadia',
     'Other',
     'rejected');

-- SESSION REQUESTS

INSERT INTO session_requests
    (request_id, student_id, mentor_id, message, status, requested_at, responded_at)
VALUES
    (1, 1, 9,
     'I am a third-year CSE student at AIUB and want to prepare for software engineering interviews. I would like guidance on DSA and internships.',
     'accepted',
     '2026-08-20 18:30:00',
     '2026-08-21 10:15:00'),

    (2, 3, 10,
     'I want to target major software companies after graduation. Could you share how you prepared for technical interviews?',
     'pending',
     '2026-08-22 20:10:00',
     NULL),

    (3, 4, 12,
     'I am interested in pursuing a funded MS abroad and would like advice about research experience and applications.',
     'accepted',
     '2026-08-19 15:45:00',
     '2026-08-20 09:20:00'),

    (4, 6, 14,
     'I am working with ESP32 and Arduino and want to move into embedded systems professionally. I would appreciate some career advice.',
     'pending',
     '2026-08-23 17:00:00',
     NULL),

    (5, 7, 15,
     'I want to pursue research in robotics. Could you tell me how you built your undergraduate research profile?',
     'rejected',
     '2026-08-18 12:10:00',
     '2026-08-19 14:40:00'),

    (6, 8, 16,
     'My CGPA is currently below 3.0. I want to improve my skills and eventually apply for opportunities abroad. I would like to hear about your experience.',
     'pending',
     '2026-08-24 21:05:00',
     NULL);

COMMIT;
