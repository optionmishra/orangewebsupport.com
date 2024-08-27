<!DOCTYPE html>
<html lang="en">

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
    <div class="container main-wrapper d-flex flex-column justify-content-around">
    
        <div class="row justify-content-center mt-3">
        <img class="bakcolor" src="assets/home/img/orange-logo.svg" alt="" style="max-width: 150px" />
            <h2 class="txtcolor" style="text-align: center " >Orange Education's Websupport</h2>
            
        </div>
        <div class="card shadow p-4 home-card">
        
            <div class="home">
                <span><img src="assets/home/img/home.png" alt="" /></span>
                <span><a href="https://www.orangeeducation.in/home/">Return to <br />Main Site</a></span>
            </div>
            <div class="row">
                <div class="col-md-6 illustration">
                    <img class="left-ill-img" src="assets/home/img/illustration.png" />
                    <div class="ill-2">
                        <a href="https://www.youtube.com/c/orangeeducationpvtltd">Watch Tutorial</a>
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="heading">
                        <p class="mt-2 mb-1">Welcome to</p>
                        <p style="font-size: 26px">One-Stop Platform</p>
                    </div>
                    <form action="<?= base_url('web/process') ?>" method="POST">
                        <div class="email_pw">
                            <i class="email-icon"> <img src="assets/home/img/email.PNG" alt="" /></i>
                            <input class="form-control" type="email" name="username" id="email" placeholder="Registered E-mail" />
                            <i class="pw-icon"> <img src="assets/home/img/pwd.PNG" alt="" /></i>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" />
                        </div>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <p class="text-danger"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
                            <?php $this->session->unset_userdata('error'); ?>
                        <?php endif; ?>
                        <div class="show_pw">
                            <input type="checkbox" name="show_pw" id="show_pw" onclick="showPwd()" />
                            <label for="show_pw">Show Password</label>
                        </div>
                        <div class="d-flex align-items-center mt-2 mr-2">
                            <button class="btn btn-login rounded-pill">
                                LOGIN >
                            </button>
                            <a style="
                  font-size: 0.8rem;
                  width: 120px;
                  text-align: end;
                  color: #333;
                  cursor: pointer;
                  " data-bs-toggle="modal" data-bs-target="#forgot_pass">Forgot Password</a>
                        </div>
                    </form>
                    <div class="reg">
                        <div class="reg-line">
                            <button class="signup-pill" id="registrationBtn">SIGNUP</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <footer class="row">
            <div class="d-flex flex-column justify-content-center align-items-center" style="color: white">
                <h1 style="font-size: 1.5rem">Empowering Computer Education</h1>
                <p style="font-size: 12px">
                    Copyrights <?= date('Y') ?> Orange Education Pvt. Ltd.
                </p>
            </div>
            <div class="wa-icon">
                <a href="https://wa.me/918588814859?text=I%27m%20interested%20in%20Orange%20Education%27s%20web%20support"><img style="width: 50px" src="assets/home/img/whatsapp.png" alt="" /></a>
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
    <script src="js/vendor/jquery-3.2.1.min.js"></script>
    <script src="assets/home/js/bootstrap.min.js"></script>
    <script src="assets/js/smooth-submit.js"></script>
    <script src="assets/home/js/home.js"></script>
</body>

</html>