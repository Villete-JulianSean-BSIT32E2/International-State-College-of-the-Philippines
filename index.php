<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ISCP</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>

    html {
    scroll-behavior: smooth;
  }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Open Sans', sans-serif; background: #f4f8fc; }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 10%;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

   .logo {
    position: absolute;
    top: 20px;
    left: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: bold;
    color: #0b2f69;
    z-index: 1000;
  }

  .logo-img {
    height: 40px;
    width: auto;
  }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      padding: 0;
      margin: 0;
    }

    nav ul li a {
      text-decoration: none;
      color: #333;
      padding: 8px 12px;
      border-radius: 4px;
      transition: all 0.3s ease;
    }

    nav ul li a:hover {
      background-color: #2e5cfa;
      color: white;
    }

    .hero {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 60px 10%;
      background: #e8f6fd;
    }

    .hero-text {
      max-width: 50%;
    }

    .hero-text h1 {
      font-size: 48px;
      color: #0b2f69;
      margin-bottom: 20px;
    }

    .hero-text p {
      font-size: 18px;
      color: #555;
      margin-bottom: 30px;
    }

    .hero-img img {
      width: 400px;
      height: auto;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: opacity 1s ease-in-out;
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        text-align: center;
      }

      .hero-text, .hero-img {
        max-width: 100%;
      }

      .hero-img img {
        width: 100%;
        margin-top: 30px;
      }
    }

    /* Admin login modal styles */
    #loginModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    #loginModal .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
      width: 300px;
    }

    #loginModal input {
      padding: 10px;
      width: 100%;
      margin: 15px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    #loginModal button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin: 5px;
    }

    #loginModal .submit-btn {
      background: #2e5cfa;
      color: #fff;
    }

    #loginModal .cancel-btn {
      background: #ccc;
      color: #333;
    }

    #studentLoginModal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6);
  justify-content: center;
  align-items: center;
  z-index: 999;
}

#studentLoginModal .modal-content {
  background: #fff;
  padding: 30px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
  width: 300px;
}

#studentLoginModal input {
  padding: 10px;
  width: 100%;
  margin: 15px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
}

#studentLoginModal button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin: 5px;
}

#studentLoginModal .submit-btn {
  background: #2e5cfa;
  color: #fff;
}

#studentLoginModal .cancel-btn {
  background: #ccc;
  color: #333;
}

    .floating-object {
      position: absolute;
      font-size: 30px;
      animation: float 10s infinite ease-in-out;
      opacity: 0.4;
      pointer-events: none;
      z-index: 0;
    }

    @keyframes float {
      0% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(15deg); }
      100% { transform: translateY(0) rotate(0deg); }
    }

    /* Dark Mode Toggle Switch */
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 26px;
      margin-left: 20px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0;
      right: 0; bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 20px; width: 20px;
      left: 4px; bottom: 3px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #2e5cfa;
    }

    input:checked + .slider:before {
      transform: translateX(24px);
    }

    /* Dark Mode Styles */
    body.dark-mode {
      background-color: #121212;
      color: #f1f1f1;
    }

    body.dark-mode header {
      background-color: #1f1f1f;
      box-shadow: none;
    }

    body.dark-mode nav ul li a {
      color: #ddd;
    }

    body.dark-mode nav ul li a:hover {
      background-color: #444;
    }

    body.dark-mode .hero {
      background: #1a1a1a;
    }

    body.dark-mode .hero-text h1 {
      color: #e1e1e1;
    }

    body.dark-mode .hero-text p {
      color: #ccc;
    }

    body.dark-mode .modal-content {
      background: #2a2a2a;
      color: #f1f1f1;
    }

    body.dark-mode input {
      background: #333;
      color: white;
      border: 1px solid #555;
    }

    /* Floating Enroll Button */
    .floating-enroll-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #2e5cfa;
      color: white;
      padding: 14px 28px;
      font-size: 16px;
      border: none;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      transition: background 0.3s, transform 0.3s;
      z-index: 1000;
    }

    .floating-enroll-btn:hover {
      background: #1b45c9;
      transform: scale(1.05);
    }

    body.dark-mode .floating-enroll-btn {
      background: #444;
      color: #f1f1f1;
    }

    body.dark-mode h4 {
  color: #3498db; /* Light text color for headings in dark mode */
}

body.dark-mode p {
  color: #3498db; /* Lighter text color for paragraphs in dark mode */
}

body.dark-mode .hero-text h1 {
  color: #3498db;
}
.icon-hover {
    transition: transform 0.3s ease, filter 0.3s ease;
    display: inline-block;
  }

.icon-hover:hover {
    transform: scale(1.2) rotate(5deg);
    filter: brightness(1.2);
  }

  .faq-question {
    background-color: #2e5cfa;
    color: white;
    cursor: pointer;
    padding: 15px 20px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 16px;
    transition: background-color 0.3s ease;
    border-radius: 5px;
    margin-top: 10px;
  }

  .faq-question:hover {
    background-color: #0b2f69;
  }

  .faq-answer {
    padding: 15px 20px;
    background-color: #f9f9f9;
    display: none;
    overflow: hidden;
    border-left: 4px solid #2e5cfa;
    border-radius: 0 0 5px 5px;
  }

  .faq-item.active .faq-answer {
    display: block;
  }
a:hover {
    color: #3498db; /* Change to any color you want */
    transform: scale(1.1); /* Optional: adds a slight zoom effect */
  }

  </style>
</head>
<body>

  <!-- Floating School Items -->
  <div class="floating-object" style="top: 100px; left: 20px;">📘</div>
  <div class="floating-object" style="top: 200px; right: 50px;">✏️</div>
  <div class="floating-object" style="bottom: 150px; left: 100px;">🎓</div>
  <div class="floating-object" style="bottom: 100px; right: 120px;">📐</div>

  <header>
    <div class="logo">
      <img src="logo.png" alt="ISCP Logo" class="logo-img" />
      <span>ISCP</span>
    </div>

    <nav>
  <ul>
    <li><a href="#">Home</a></li>
    <li><a href="#" onclick="showLogin()">ADMIN</a></li>
<li><a href="#" onclick="showStudentLogin()">STUDENT</a></li>
    <li><a href="#faq">FAQ</a></li>
  </ul>
</nav>

    <div style="margin-left: auto;">
      <label class="switch">
        <input type="checkbox" id="darkToggle" />
        <span class="slider"></span>
      </label>
    </div>
  </header>

  <section class="hero">
    <div class="hero-text">
      <h1>International State College of the Philippines</h1>
      <p>One of the most prestigious colleges that exist in the universe. <br> BECOME AN ASPIN. </p>
    </div>
    <div class="hero-img">
      <img id="slideshow-image" src="Image/Deep_learning.jpg" alt="Slideshow Image" />
    </div>
  </section>

 <section style="padding: 60px 10%; background: #fff;">
  <h2 style="text-align: center; color: #0b2f69; margin-bottom: 40px;">Why Choose ISCP?</h2>
  <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px;">
    <!-- Top Row -->
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🎓</div>
      <h4 class="dark-mode-text" style="color: #333;">World-Class Faculty</h4>
      <p class="dark-mode-text" style="color: #555;">Our faculty consists of leading experts from diverse fields, offering personalized attention to help students excel.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🌐</div>
      <h4 class="dark-mode-text" style="color: #333;">Global Opportunities</h4>
      <p class="dark-mode-text" style="color: #555;">Gain international exposure through study abroad, global internships, and partnerships with global institutions.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">💼</div>
      <h4 class="dark-mode-text" style="color: #333;">Career Support</h4>
      <p class="dark-mode-text" style="color: #555;">We provide coaching, internships, resume building, and job support for a strong start to your career.</p>
    </div>

    <!-- Bottom Row -->
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">📝</div>
      <h4 class="dark-mode-text" style="color: #333;">Cutting-Edge Facilities</h4>
      <p class="dark-mode-text" style="color: #555;">Study in a modern campus equipped with advanced labs, digital tools, and collaborative spaces.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🏆</div>
      <h4 class="dark-mode-text" style="color: #333;">Student Achievements</h4>
      <p class="dark-mode-text" style="color: #555;">ISCP students are recognized nationally and globally for excellence in academics and innovation.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">💡</div>
      <h4 class="dark-mode-text" style="color: #333;">Innovative Learning</h4>
      <p class="dark-mode-text" style="color: #555;">Our programs combine theory and hands-on experience to foster creativity, problem-solving, and leadership.</p>
    </div>
  </div>
</section>

<!-- Vibrant Campus Life Section -->
<section style="padding: 60px 10%; background: #f8f8f8;">
  <h2 style="text-align: center; color: #0b2f69; margin-bottom: 40px;">Vibrant Campus Life</h2>
  <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 40px;">
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🎉</div>
      <h4 class="dark-mode-text" style="color: #333;">Exciting Events</h4>
      <p class="dark-mode-text" style="color: #555;">From cultural shows to sports and academic fairs, ISCP hosts vibrant campus events year-round.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🤝</div>
      <h4 class="dark-mode-text" style="color: #333;">Student Organizations</h4>
      <p class="dark-mode-text" style="color: #555;">With over 50 active clubs, students can pursue their passions, build networks, and lead projects.</p>
    </div>
    <div style="text-align: center; flex: 1; min-width: 250px; margin-bottom: 40px;">
      <div class="icon-hover" style="font-size: 50px; margin-bottom: 10px;">🏫</div>
      <h4 class="dark-mode-text" style="color: #333;">Social Spaces</h4>
      <p class="dark-mode-text" style="color: #555;">Relax and connect in cozy lounges, cafes, or green spaces designed for collaboration and fun.</p>
    </div>
  </div>
</section>


<!-- FAQ Section -->
<section id="faq" class="faq-section" style="padding: 60px 10%; background: #fff;">
  <h2 style="text-align: center; color: #0b2f69; margin-bottom: 40px;">Frequently Asked Questions</h2>
  <div style="max-width: 900px; margin: auto;">
    <div class="faq-item">
      <button class="faq-question">What programs does ISCP offer?</button>
      <div class="faq-answer">
        <p>ISCP offers a wide range of undergraduate and graduate programs in fields such as Technology, Business, Science, and Liberal Arts</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question">How do I apply for admission?</button>
      <div class="faq-answer">
        <p>You will be assisted by the Admission office or for online enrollment click the Enroll now button that you see on the website</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question">Is there financial aid or scholarship available?</button>
      <div class="faq-answer">
        <p>Yes, ISCP provides various scholarships and financial assistance programs based on merit and need. Please contact our financial aid office for more details.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question">What is the tuition fee structure?</button>
      <div class="faq-answer">
        <p>Tuition fees vary by program and residency status. Visit the “Cashier” section or contact our office for a detailed breakdown.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question">Is there on-campus housing?</button>
      <div class="faq-answer">
        <p>Yes, ISCP offers comfortable and secure on-campus dormitories for local and international students and we also have our friendly security guard "Manong Jemicho Ampong" Be sure to say hi and mano po whenever you meet him!</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer style="background: #0b2f69; color: white; padding: 40px 10%; text-align: center;">
  <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 30px;">
    <!-- Quick Links -->
    <div style="flex: 1; min-width: 200px;">
      <h4 style="color: #fff;">Quick Links</h4>
      <ul style="list-style: none; padding: 0;">
        <li><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s ease;">Home</a></li>
        <li><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s ease;">Admission</a></li>
        <li><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s ease;">Registrar</a></li>
        <li><a href="attendance-dashboard.php" style="color: #fff; text-decoration: none; transition: color 0.3s ease;">Attendance</a></li>
        <li><a href="#faq" style="color: #fff; text-decoration: none; transition: color 0.3s ease;">FAQ</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div style="flex: 1; min-width: 200px;">
      <h4 style="color: #fff;">Contact Us</h4>
      <p>Email: contact@iscp.edu.ph</p>
      <p>Phone: +63 123 456 789</p>
      <p>Address: Cubao Center of the Universe, Philippines</p>
    </div>

    <!-- Social Media -->
    <div style="flex: 1; min-width: 200px;">
  <h4 style="color: #fff;">Follow Us</h4>
  <div style="display: flex; gap: 15px; justify-content: center;">
    <a href="https://www.facebook.com/ISCPhilippines" style="color: #fff; font-size: 20px; transition: color 0.3s ease; padding: 5px; border-radius: 50%;"><i class="fab fa-facebook-f"></i></a>
    <a href="" style="color: #fff; font-size: 20px; transition: color 0.3s ease; padding: 5px; border-radius: 50%;"><i class="fab fa-twitter"></i></a>
    <a href="#" style="color: #fff; font-size: 20px; transition: color 0.3s ease; padding: 5px; border-radius: 50%;"><i class="fab fa-instagram"></i></a>
    <a href="#" style="color: #fff; font-size: 20px; transition: color 0.3s ease; padding: 5px; border-radius: 50%;"><i class="fab fa-linkedin-in"></i></a>
  </div>
</div>

  <div style="margin-top: 20px;">
    <p>&copy; 2025 International State College of the Philippines. All rights reserved.</p>
  </div>
</footer>
<div id="modalContainer"></div>
<!-- FontAwesome Icons (for social media icons) -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>



  <!-- Admin Login Modal -->
  <div id="loginModal">
    <div class="modal-content">
      <h3>Admin Login</h3>
      <input type="password" id="adminPassword" placeholder="Enter password" />
      <br />
      <button class="submit-btn" onclick="checkPassword()">Enter</button>
      <button class="cancel-btn" onclick="hideLogin()">Cancel</button>
    </div>
  </div>

<div id="studentLoginModal" class="modal" style="display: none;">
  <div class="modal-content">
    <h3>Student Login</h3>
    <input type="text" id="studentUsername" placeholder="Enter username" />
    <input type="password" id="studentPassword" placeholder="Enter password" />
    <br />
    <button onclick="checkStudentLogin()">Login</button>
    <button onclick="hideStudentLogin()">Cancel</button>
  </div>
</div>

  


  <!-- Floating Enroll Button -->
  <a href="addstudent.html" class="floating-enroll-btn">Enroll Now</a>

  <script>
    function showLogin() {
      document.getElementById("loginModal").style.display = "flex";
    }

    function hideLogin() {
      document.getElementById("loginModal").style.display = "none";
      document.getElementById("adminPassword").value = "";
    }

    function checkPassword() {
      const input = document.getElementById("adminPassword").value;
      if (input === "admin123") {
        window.location.href = "main-dashboard.php";
      } else {
        alert("Incorrect password. Access denied.");
      }
    }

    // Slideshow Logic
    const images = [
      "Image/Deep_learning.jpg",
      "Image/Intimacy.jfif",
      "Image/Theology.jfif",
      "Image/Graphics.jfif",
      "Image/ewanko.jfif",
      "Image/Archi.jfif"
    ];

    let index = 0;
    const imgElement = document.getElementById("slideshow-image");

    setInterval(() => {
      index = (index + 1) % images.length;
      imgElement.style.opacity = 0;
      setTimeout(() => {
        imgElement.src = images[index];
        imgElement.style.opacity = 1;
      }, 300);
    }, 4000);

    // Dark Mode Toggle
    document.getElementById('darkToggle').addEventListener('change', function () {
      document.body.classList.toggle('dark-mode', this.checked);
    });


  document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const item = button.parentElement;
      item.classList.toggle('active');
    });
  });

function showStudentLogin() {
  document.getElementById("studentLoginModal").style.display = "flex";
}

function hideStudentLogin() {
  document.getElementById("studentLoginModal").style.display = "none";
  document.getElementById("studentUsername").value = "";
  document.getElementById("studentPassword").value = "";
}

function checkStudentLogin() {
  const user = document.getElementById("studentUsername").value;
  const pass = document.getElementById("studentPassword").value;

  fetch('student_login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `username=${encodeURIComponent(user)}&password=${encodeURIComponent(pass)}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      // Save basic info
      localStorage.setItem('studentName', data.fullname);
      localStorage.setItem('studentCourse', data.course);
      localStorage.setItem('studentSem', data.current_sem);
      localStorage.setItem('studentYear', data.school_year);
      localStorage.setItem('studentSection', data.section);
      localStorage.setItem('studentID', data.student_id);

      // Save other info
      localStorage.setItem('studentGrades', JSON.stringify(data.grades));
      localStorage.setItem('studentSchedule', JSON.stringify(data.schedule));
      localStorage.setItem('studentAttendance', JSON.stringify(data.attendance));
      localStorage.setItem('studentPayments', JSON.stringify(data.payments));

      window.location.href = './studentmodule/studentmodule.php';
    } else {
      alert("Incorrect credentials.");
    }
  })
  .catch(error => {
    console.error("Login error:", error);
    alert("An error occurred. Please try again.");
  });
}



  </script>

</body>
</html>
