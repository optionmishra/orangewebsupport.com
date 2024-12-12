<!DOCTYPE html>
<html lang="en">
    <style>
       
        .download-button{
            position: absolute;
  bottom: 0;
  left: 0;
 
  
 
  border: none;
  border-radius: 5px; 
  cursor: pointer;}

  .popup {
    position: fixed;
    top: 50%; /* Center vertically */
    left: 50%; /* Center horizontally */
    transform: translate(-50%, -50%); /* Adjust for element size */
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    display: none; /* Initially hidden */
    z-index: 100;
    max-width: 100%; /* Added max-width for responsiveness */
    max-height: 100vh; /* Added max-height to reduce height */
    overflow: hidden;
}

.popup-content {
    text-align: center;
    
}

.close-popup {
    position: absolute; /* Position relative to the popup */
    top: 5px; /* Distance from the top */
    right: 10px; /* Distance from the right */
    color: black;
    border: none;
    font-size: 22px; /* Adjusted font size for better visibility */
    cursor: pointer;
    background-color: transparent; 
}

.popup-image {
    width: 100%; /* Make image fully responsive */
    height: auto; /* Maintain aspect ratio */
    max-height: 100vw;
    border-radius: 10px; /* Match popup border radius */
   
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: none; /* Initially hidden */
    z-index: 80;
}
    </style>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <title>Touchpad</title> -->
    <link rel="stylesheet" href="assets/home/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/home/css/home-style.css" />
    <title>touchpadwebsupport.com</title>
    <link rel="icon" type="image/png" href="assets/img/tp-favicon.png">
</head>

<body>
<video class="backvideo" autoplay muted playsinline loop preload="auto" >
    <source src="/assets/home/backvid.mp4" type="video/mp4">
</video>
<!-- <div class="overlay"></div>

<div class="popup">
    <div class="popup-content">
        <button class="close-popup">&#10799;</button>
        <img src="/images/diwali.jpg" alt="Image" class="popup-image">
    </div>
</div> -->
   
<div class="container main-wrapper d-flex flex-column justify-content-around">
    <div class="row justify-content-center mt-3">
        <img class="bakcolor" src="assets/home/img/orange-logo.svg" alt="" style="max-width: 150px" />
        <h2 class="txtcolor text-center">Orange Education's Websupport</h2>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-8 d-flex flex-column">
            <div class="card shadow p-4 home-card flex-fill mb-4">
                <div class="home">
                    <span><img src="assets/home/img/home.png" alt="" /></span>
                    <span><a href="https://www.orangeeducation.in/home/">Return to <br />Main Site</a></span>
                </div>
                <div class="row">
                    <div class="col-md-6 illustration ">
                        <img class="left-ill-img img-fluid" src="assets/home/img/illustration.png" alt="Illustration" />
                        <div class="ill-2">
                            <a href="https://www.youtube.com/c/orangeeducationpvtltd" class="btn btn-link">Watch Tutorial</a>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <div class="heading ">
                            <p class="mt-2 mb-1">Welcome to</p>
                            <p style="font-size: 26px">One-Stop Platform</p>
                        </div>
                        <form action="<?= base_url('web/process') ?>" method="POST">
                            <div class="email_pw mb-3">
                                <i class="email-icon"><img src="assets/home/img/email.PNG" alt="Email Icon" /></i>
                                <input class="form-control" type="email" name="username" id="email" placeholder="Registered E-mail" required />
                                <i class="pw-icon"><img src="assets/home/img/pwd.PNG" alt="Password Icon" /></i>
                                <input class="form-control" type="password" name="password" id="password" placeholder="Password" required />
                            </div>
                            <?php if ($this->session->flashdata('error')) : ?>
                                <p class="text-danger"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
                                <?php $this->session->unset_userdata('error'); ?>
                            <?php endif; ?>
                            <div class="show_pw mb-3">
                                <input type="checkbox" name="show_pw" id="show_pw" onclick="showPwd()" />
                                <label for="show_pw">Show Password</label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-login rounded-pill">LOGIN ></button>
                                <a style="font-size: 0.8rem; color: #333;" data-bs-toggle="modal" data-bs-target="#forgot_pass">Forgot Password</a>
                            </div>
                        </form>
                        <div class="reg text-center mt-3">
                            <button class="signup-pill btn btn-primary" id="registrationBtn">SIGNUP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Bootstrap Box/Card on the right -->
        <div class="col-md-4 d-flex"> 
            <div class="card shadow p-4 flex-fill custom-card">
                <div class="card-header text-center">
                    <h5 class="mb-0">What's New</h5>
                </div>
                <div class="card-body p-0">
                    <div class="notification-body">
                        <ul class="notification-list">
                            <?php if (!empty($notifications)): ?>
                                <?php foreach ($notifications as $notification): ?>
                                    <li class="notification-item">
                                        <strong><?php echo htmlspecialchars($notification['title']); ?></strong>
                                        <p class="notification-description"><?php echo htmlspecialchars($notification['description']); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="notification-item">
                                    <strong>No new notifications</strong>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="row mt-4">
        <div class="d-flex flex-column justify-content-center align-items-center text-white">
            <h1 style="font-size: 1.5rem">Empowering Computer Education</h1>
            <p style="font-size: 12px">
                Copyrights <?= date('Y') ?> Orange Education Pvt. Ltd.
            </p>
        </div>
        <div class="wa-icon">
            <a href="https://wa.me/918588814859?text=I%27m%20interested%20in%20Orange%20Education%27s%20web%20support"><img style="width: 50px" src="assets/home/img/whatsapp.png" alt="WhatsApp Icon" /></a>
        </div>
    </footer>
</div>
    <!-- The Registration Modal -->
    <div id="registrationModal" class="registration-modal">
        <!-- Modal content -->
        <div class="registration-modal-content">
            <div class="modal-header">
                <h5>Select Your Role</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body d-flex justify-content-around">
                <a href="<?php echo base_url(); ?>student-registration"><button class="role-btn student-reg-btn"> I am a<br />STUDENT</button></a>
                <a href="<?php echo base_url(); ?>teacher-registration"><button class="role-btn teacher-reg-btn">I am a<br />TEACHER</button></a>
            </div>
        </div>
    </div>
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgot_pass" tabindex="-1" aria-labelledby="forgot_pass" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkForgot" class="smooth-submit" method="post" action="<?= base_url('admin_master/check_forgot') ?>">
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="ch_email" class="col-form-label">Enter Your Register Email</label>
                                <input type="email" class="form-control" id="ch_email" name="email" required="true">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="forgot_passs" tabindex="-1" role="dialog" aria-labelledby="forgot_passs" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content p-5">
                <p class="text-danger text-center swd"></p>
            </div>
        </div>
        </div>
        <!-- created by harmeet singh -->
    </div>
    <script src="js/vendor/jquery-3.2.1.min.js"></script>
    <script src="assets/home/js/bootstrap.min.js"></script>
    <script src="assets/js/smooth-submit.js"></script>
    <script src="assets/home/js/home.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.popup').style.display = 'block'; // Show the popup
        document.querySelector('.overlay').style.display = 'block'; // Show the overlay
    });

    document.querySelector('.close-popup').addEventListener('click', function() {
        document.querySelector('.popup').style.display = 'none'; // Hide the popup
        document.querySelector('.overlay').style.display = 'none'; // Hide the overlay
    });
</script>
    
</body>

</html>