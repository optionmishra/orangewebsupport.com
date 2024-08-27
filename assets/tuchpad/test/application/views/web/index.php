<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/home/css/homehis2.css"/>
    <title>Orange Education Web Support </title>
    
</head>
<body>
<video class="backvideo" autoplay muted playsinline loop preload="auto" >
    <source src="/assets/home/backvid.mp4" type="video/mp4">

  </video>
  <div class="supportimgmain">
  <a href="<?php echo base_url(); ?>support"  > <img class="supportimg" src="/assets/home/support.png"> </a>
  </div>
    <div id="frontlogo1" class="frontlogo" >
      <img src="/assets/home/logo.png"> 
    <h1 class="orange" >Orange Education's </h1>
    <h1 class="web" >Web Support</h1>
    <hr size="1" color="black" >
    <h1 class="welcometext" >Welcome to </h1>
    <h1 class="onestoptext" >One-Stop Platform</h1>
    <form action="<?= base_url('web/process') ?>" method="POST">
        <div id="mainfame">
                        <div class="email_pw" >
                            
                            <input class="form-control" type="email" name="username" id="email" placeholder="Registered E-mail" />
                            
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" />
                        </div>
                       
                       
                        <div class="d-flex align-items-center mt-2 mr-2">
                            <button class="btn btn-login rounded-pill">
                                LOGIN 
                            </button>
                            <a href=""><button class="btn btn-Signup rounded-pill" id="registrationBtn" type="button" >
                  SIGNUP </button></a>
                 
                        </div>
                        <div class="show_pw">
                            <input type="checkbox" name="show_pw" id="show_pw" onclick="showPwd()" />
                            <label for="show_pw">Show Password</label> </div>
                            <div>
                            <?php if ($this->session->flashdata('error')) : ?>
                            <p class="text-danger"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
                            <?php $this->session->unset_userdata('error'); ?>
                        <?php endif; ?> </div>
                            </form>
                           
               <div class="forget">
              <a class="forgetpass"

              href="#" id="popup-link"
              >Forgot Password</a
            ></div> 
    
                            </div>

    </div>
    <!-- forget modal -->
    <div id="popup-window">
            <div class="modal-body">
            <a id="close-button" class="closebtn" >x</a>
                    <form id="checkForgot" class="smooth-submit" method="post" action="<?= base_url('admin_master/check_forgot') ?>">
                        <div class="form-group">
                        <h1 class="forgethead" >Forget Your Password </h1><br><br><br>
                            
                                
                          
                        <input type="email" class="forgetmail" id="ch_email" name="email" required="true" placeholder="Enter your Registered E-mail">
                           
                        </div>
                </div>
                <div class="modal-footer">
                   
                    <button type="submit" class="forgetbtnsub">Submit</button>
                </div>
                </form>
           
  
  <div class="modal fade" id="forgot_passs" tabindex="-1" role="dialog" aria-labelledby="forgot_passs" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content p-5">
                <p class="text-danger text-center swd"></p>
            </div>
        </div>
    </div>







</div>     

<div class="regtext" id="popreg">
<div>
<a id="reg-close-button" class="closebtn" >x</a><br>
<div class="mainbox">
  <div class="btn1">
<a href="<?php echo base_url(); ?>student-registration"><button class="role-btn1"> I am a<br />STUDENT</button></a></div>
<div class="btn2">
                <a href="<?php echo base_url(); ?>teacher-registration"><button class="role-btn2">I am a<br />TEACHER</button></a></div>
                            </div>


</div>

</div>


</div>
   
</div> 
                            </div>
<script>
  // Get the elements by their ID
  var popupLink = document.getElementById("popup-link");
  var popupWindow = document.getElementById("popup-window");
  var popreg = document.getElementById("popreg");
  var regpopupLink = document.getElementById("registrationBtn");
  var mainfamestop = document.getElementById("frontlogo1");
  var closeButton = document.getElementById("close-button");
  var regcloseButton = document.getElementById("reg-close-button");
  // Show the pop-up window when the link is clicked
  popupLink.addEventListener("click", function(event) {
    event.preventDefault();
    popupWindow.style.display = "block";
    mainfamestop.style.display = "none";
   
  });
  // Hide the pop-up window when the close button is clicked
  closeButton.addEventListener("click", function() {
    popupWindow.style.display = "none";
    mainfamestop.style.display = "block";
  });


  regpopupLink.addEventListener("click", function(event) {
    event.preventDefault();
    popreg.style.display = "block";
    mainfamestop.style.display = "none";

  });

  regcloseButton.addEventListener("click", function() {
    popreg.style.display = "none";
    mainfamestop.style.display = "block";


  });

</script>  


      <script src="js/vendor/jquery-3.2.1.min.js"></script>
      <script src="assets/home/js/bootstrap.min.js"></script>
      <script src="assets/js/smooth-submit.js"></script>
      <script src="assets/home/js/home.js"></script>
    
    
</body>
</html>