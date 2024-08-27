<!-- footer start-->
 <!-- Site footer -->
 <section class="">
    <footer class="site-footer">
      <div class="container-fluid footer_inner">
        <div class="row">
          <div class="col-sm-12 col-md-5 footer-pdg">
            <h3>About</h3>
            <p class="text-justify">Orange Education is a group of educational evangelists, mentors and strategists where we have 
		devised to branch out the educational system, exercising the digital modus operandi and products for online solutions.</p>
	<div class="col-md-12 col-sm-12 col-xs-12 footer_icon">
            
          </div>
          </div>

         <div class="col-sm-12 col-md-7">
	 <div class="row">
          <div class="col-xs-6 col-md-4 footer-pdg">
            <h3>Quick Link</h3>
            <ul class="footer-links">
              <li><a href="#">Home</a></li>
              <li><a href="#">School Login</a></li>
              <li><a href="#">Student Login </a></li>
              <li><a href="http://thetouchpad.com/contact.php">Contact Us</a></li>
            </ul>
          </div>
        
		  
	    <div class="col-xs-6 col-md-4 footer-pdg">
            <h3>Our Media</h3>            
             <ul class="footer-links">
              <li><a class="facebook" href="https://www.facebook.com/TouchpadComputerSeries"><i class="fa fa-facebook"></i></a></li>
              <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a class="dribbble" href="https://www.instagram.com/touchpadcomputerseries/"><i class="fa fa-instagram"></i></a></li>
              <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>   
            </ul>
            </ul>
          </div> 
		  
		   <div class="col-xs-6 col-md-4 footer-pdg">
            <h3>Contact Us</h3>
            <ul class="footer-links">
              <li><a href="#">+011-43776600</a></li>
              <li><a href="#">editorial@orangeeducation.in</a></li>
              <li><a href="#">info@orangeeducation.in</a></li>
            </ul>
          </div>
		  </div>
		  </div>
        </div>
        <hr>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <p class="copyright-text" style="text-align:center;">Copyright ©2019 All rights reserved Orange Education</p>
          </div>
      </div>
</footer>
<!-- Register Student Form -->
<div class="modal fade" id="add-new-student" tabindex="-1" role="dialog" aria-labelledby="add-new-student" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addStudent" class="smooth-submit" method="post" action="<?= base_url('admin_master/add_student') ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowance-deduction">New Student Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-body">
                    <div class="row m-0 p-2">
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_name">Full Name *</label>
                                <input type="text" class="form-control" id="student_name" name="name" required="true">
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_mobile">Mobile *</label>
                                <input type="text" class="form-control" id="student_mobile" name="mobile"  pattern="[1-9]{1}[0-9]{9}" title="10 digit Mobile number" required="true">
                            </div>
                        </div>
                        <div class="col-lg-5 p-2">
                            <div class="form-group">
                                <label for="student_email">Email *</label>
                                <input type="email" class="form-control" id="student_email" name="email" required="true">
                                <div id="getemail_desc"></div>
                            </div>
                        </div>
						<div class="col-lg-5 p-2">
                            <div class="form-group">
                                <label for="student_password">Password *</label>
                                <input type="password" class="form-control" id="student_password" name="password" pattern=".{8,}" title="Must contain at least 8 or more characters" required="true">
                                <div id="getemail_desc"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 p-2">
                            <div class="form-group">
                                <label for="student_pin">Pin Code *</label>
                                <input type="text" class="form-control" id="student_pin" pattern="[0-9]{6}" title="Six digit zip code" name="pin" required="true">
                            </div>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="student_address">Address *</label>
                                <textarea class="form-control" id="student_address" name="address" required="true"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_state">State *</label>
                                <select class="form-control" name="state" id="student_state" required="true">
                                    <option value="">--Select State--</option>
                                    <?php foreach($count as $cou): ?>
                                    <option value="<?= $cou->StateID ?>"><?= $cou->StateName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_city">City *</label>
                                <input type="text" class="form-control" id="student_city" name="city" required="true">
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_class">Class *</label>
                                <select class="form-control" name="class" id="student_class" required="true">
                                    <option value="">--Select Class--</option>
                                    <?php foreach($classes as $class): ?>
                                    <option value="<?= $class->id ?>"><?= $class->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_subject">Subject *</label>
                                <select class="form-control" name="subject" id="student_subjectr" required="true">
                                   <?php foreach($msubject as $class): ?>
                                    <option value="<?= $class->id ?>"><?= $class->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						<div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="session_slot">Session Slot *</label>
                                <select class="form-control" name="session_slot" id="session_slot" required="true">
                                    <option value="">--Select Slot--</option>
                                     <option value='1'>Janaury</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
                                </select>
                            </div>
                        </div>
						<div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="stu_teacher_id">Teacher Id *</label>
                                 <input type="text" class="form-control" id="stu_teacher_id" name="stu_teacher_id" required="true">
                            </div>
                        </div>
						
						
                    </div>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button  class="btn btn-primary float-right">Register</button>
                </div>
            </form>
        </div>    
    </div>
</div>

<!-- //Register Student Form End -->
<!-- Register Teacher Form -->
<div class="modal fade" id="add-new-teacher" tabindex="-1" role="dialog" aria-labelledby="add-new-teacher" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url();?>admin_master/add_teacher">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowance-deduction">New Teacher Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-body">
                    <div class="row m-0 p-2">
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="teacher_name">Full Name *</label>
                                <input type="text" class="form-control" id="teacher_name" name="name" required="true">
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="teacher_mobile">Mobile *</label>
                                <input type="text" class="form-control" id="teacher_mobile" name="mobile" pattern="[1-9]{1}[0-9]{9}" title="10 digit Mobile number" required="true">
                            </div>
                        </div>
                        <div class="col-lg-5 p-2">
                            <div class="form-group">
                                <label for="teacher_email">Email *</label>
                                <input type="email" class="form-control" id="teacher_email" name="email" required="true">
                                <div id="getemail_descc"></div>
                            </div>
                        </div>
						<div class="col-lg-5 p-2">
                            <div class="form-group">
                                <label for="teacher_password">Password *</label>
                                <input type="password" class="form-control" id="teacher_password" name="password" pattern=".{8,}" title="Must contain at least 8 or more characters" required="true">
                                <div id="getemail_desc"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 p-2">
                            <div class="form-group">
                                <label for="teacher_pin">Pin Code *</label>
                                <input type="text" class="form-control" id="teacher_pin" pattern="[0-9]{6}" title="Six digit zip code" name="pin" required="true">
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="teacher_address">Address(School) *</label>
                                <textarea class="form-control" id="teacher_address" name="address" required="true"></textarea>
                            </div>
                        </div>
						<div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="teacher_addresss">Address(Personal)</label>
                                <textarea class="form-control" id="teacher_addresss" name="addresss"></textarea>
                            </div>
                        </div>
                       </div>
						
						<div class="row m-0 p-2">
						
							<div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="session_slot">Session Slot *</label>
                                <select class="form-control" name="session_slot" id="session_slot" required="true">
                                    <option value="">--Select Slot--</option>
                                     <option value='1'>Janaury</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
                                </select>
                            </div>
                        </div>
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_state">Country *</label>
									<select class="form-control" name="country_type" id="country_type" required="true">
										<option value="">--Select country--</option>
										<option value="India">India</option>
										<option value="Others">Others</option>
									</select>
								</div>
							</div>
							
                        </div>
						
						<div class="row m-0 p-2" id="Others">
							<div class="col-lg-4 p-2">
								<div class="form-group">
									<label for="teacher_state">Country-Name</label>
									<input type="text" class="form-control" id="oth_country" name="oth_country">
								</div>
							</div>
							<div class="col-lg-4 p-2">
								<div class="form-group">
									<label for="teacher_state">State *</label>
									<input type="text" class="form-control" id="oth_state" name="oth_state">
								</div>
							</div>
							<div class="col-lg-4 p-2">
								<div class="form-group">
									<label for="teacher_city">City *</label>
									<input type="text" class="form-control" id="oth_city" name="oth_city">
								</div>
							</div>
						</div>
						
						<div class="row m-0 p-2" id="India">
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_state">State *</label>
									<select class="form-control" name="state" id="state" >
										<option value="">--Select State--</option>
										<?php foreach($count as $cou): ?>
										<option value="<?= $cou->StateID ?>"><?= $cou->StateName ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_city">City *</label>
									<input type="text" class="form-control" name="city" id="city">								</div>
							</div>
						</div>
						<div class="row m-0 p-2">
						<div class="col-lg-6 p-2">
							<div class="form-group">
								<label for="teacher_dob">DOB</label>
								<input type="date" class="form-control" id="teacher_dob" name="dob">
								</div>
							</div>

							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_emails">Email(Personal)</label>
									<input type="text" class="form-control" id="teacher_emails" name="emailss">
								</div>
							</div>
						</div> 
						<div class="row m-0 p-2">
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_name">Person/Referrel Name</label>
									<input type="text" class="form-control" id="referrel_name" name="referrel_name" required="true">
								</div>
							</div>
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="teacher_mobile">Person/Referrel Contact</label>
									<input type="text" class="form-control" id="referrel_mobile" name="referrel_mobile" required="true">
								</div>
							</div>
                        </div>
			
			          <div class="row m-0 p-2">
							<div class="col-lg-6 p-2">
								<div class="form-group">
									<label for="principal_name">Principal's Name *</label>
									<input type="text" class="form-control" id="principal_name" name="principal_name" required="true">
								</div>
							</div>
							
                        </div>
			            <div class="row p-2">
						<div class="col-lg-2">
                        <span>Series *</span>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="row">
                               <!-- <div class="col-lg-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-control-custom" id="teacher_subject" name="checkall" value="check All"> 
                                        <label class="form-check-label" for="student_subject_0">
                                            Check All
                                        </label>
                                    </div>
                                </div>-->
                                <?php foreach($msubject as $sub): ?>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input type="radio" class="form-control-custom ss" id="student_subject_<?= $sub->id ?>" name="subject[]" value="<?= $sub->id ?>"> 
                                        <label class="form-check-label" for="student_subject_<?= $sub->id ?>">
                                           <?= $sub->name ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <span>Classes *</span>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-control-custom" id="teacher_class" name="checkall" value="check All"> 
                                        <label class="form-check-label" for="student_class_0">
                                            Check All
                                        </label>
                                    </div>
                                </div>
                                <?php foreach($classes as $class): ?>
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-control-custom cc" id="student_class_<?= $class->id ?>" name="class[]" value="<?= $class->id ?>"> 
                                        <label class="form-check-label" for="student_class_<?= $class->id ?>">
                                           <?= $class->name ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>                        
                   </div>
				   </div>                
                <div class="modal-footer">
                    <button  class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button  class="btn btn-primary float-right">Register</button>
                </div>
            </form>
        </div>    
    </div>
</div>
<!-- //Register Teacher Form End -->
<!-- Contact Form -->
<div class="modal fade" id="add-new-contact" tabindex="-1" role="dialog" aria-labelledby="add-new-contact" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <form id="addContact" class="smooth-submit" method="post" action="<?= base_url('admin_master/add_contact') ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowance-deduction">Contact Us</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-body">
                    <div class="row m-0 p-2">
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="">Your Name *</label>
                                <input type="text" class="form-control" id="" name="name" required="true">
                            </div>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="">Mobile *</label>
                                <input type="text" class="form-control" id="" name="mobile" required="true">
                            </div>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="">Email *</label>
                                <input type="email" class="form-control" id="" name="email" required="true">
                                <div id="getemail_desc"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="">Message *</label>
                                <textarea class="form-control" id="student_address" name="message" required="true"></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button  class="btn btn-primary float-right">Send</button>
                </div>
            </form>
        </div>    
    </div>
</div>

    <div class="modal fade" id="forgot_pass" tabindex="-1" role="dialog" aria-labelledby="forgot_pass" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowance-deduction">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="checkForgot" class="smooth-submit" method="post" action="<?= base_url('admin_master/check_forgot') ?>"
                      <div class="form-body">
                        <div class="row m-0 p-2">
                            <div class="col-lg-12 p-2">
                                <div class="form-group">
                                    <label for="ch_email">Enter Your Register Email</label>
                                    <input type="email" class="form-control" id="ch_email" name="email" required="true">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer col-lg-12">
                            <button  class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                            <button  class="btn btn-primary float-right">Submit</button>
                        </div>
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

<!-- //Register Student Form End -->

<!-- //Main wrapper -->
<div class="modal" id="pleasewait" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="background-color:transparent!important;border:none!important;">
            <div class="modal-body" style="background-color:transparent!important;border:none!important;">
                <img class="fit-image" src="<?= base_url('assets/img/loading_3.gif') ?>">
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$("#India").hide();
  	$("#Others").hide();
  
  $("#country_type").on('change', function(){
  
  var country = $(this).val();
  
  if ( country == 'India'){
    $("#India").show();
    $("#Others").hide();
	
  }
  
  if ( country == 'Others'){
    $("#Others").show();
    $("#India").hide();
  }
  
 });

 /* $('#state').on('change', function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
				url:'admin_master/get_city',
                data:'state_id='+stateID,
                success:function(html){
                    $('#city').html(html);
                }
            }); 
        }else{
            $('#city').html('<option value="">Select state first</option>'); 
        }
    });
	*/


});
</script>
<!-- JS Files -->
<script src="js/vendor/jquery-3.2.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>
<script src="assets/js/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="assets/js/plugins/pace.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/toastr.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/smooth-submit.js') ?>"></script>
<script src="<?= base_url('assets/js/custom.js') ?>"></script>
</section>
	</div>
<style>
#mypop .modal-header{
background-color:#ccc;
padding:1.5rem!important;
}
#mypop .modal-body{
padding:20px 60px;
}

#mypop .modal-body img{
margin:12px auto;
margin-bottom:25px;
}

#mypop .modal-body a{
display:block;
width:100%;
border:2px solid #fff;
box-shadow:0 0 10px rgba(0,0,0,0.5);
padding:10px 10px;
border-radius:15px;
text-align:center;
color:#fff;
font-size: 17px;
}

.bt1{
background-color:orange;
}

#mypop .modal-body button{
display:block;
width:100%;
border:2px solid #fff;
box-shadow:0 0 10px rgba(0,0,0,0.5);
padding:10px 10px;
border-radius:15px;
text-align:center;
color:#fff;
font-size: 17px;
cursor:pointer;
}

#mypop .modal-body button span{
background:orange;
display: inline-block;
color: #fff;
padding: 0 5px;
margin-right: 3px;
}

.bt2{
background-color:#007bff;
margin-top:20px;
}
.bor-gr{
border-color:orange!important;
}
</style>
<script type="text/javascript">
 $(document).ready(function(){
setTimeout(function () {
       	$("#mypop").modal('show');
    }, 3000);
	});

function myfunc1() {
  	$("#mypop").modal('hide');
      // document.getElementById("spn").css('box-shadow', '0 0 10px rgba(0,0,0,0.5)');  
   $('#spn').css({
        'box-shadow': '0 0 15px rgba(0,0,0,0.3)'});
}


function myfunc2() {
  	$("#mypop").modal('hide');
  //$(.form-control).find('input[type="text"]').focus();
 $('#em').focus();
//$('#pw').focus();
}
</script>
<div id="mypop" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <!-- <h5 class="modal-title">Subscribe our Newsletter</h5>-->
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
            </div>
            <div class="modal-body">
				<img src="assets/img/tp-logo.png" alt="Touchpad">
                                <a href="https://www.touchpadwebsupport.com/demo/" class="bt1"><strong>NOT A TOUCHPAD SUBSCRIBER YET!</strong><br> Click here</a>
                                <button class="bt2 bor-gr" onclick="myfunc1()"><strong>ARE YOU A<br><span>NEW</span>TOUCHPAD SUBSCRIBER</strong><br> Click here</button>
                                <button class="bt2" onclick="myfunc2()"><strong>EXISTING TOUCHPAD SUBSCRIBER</strong><br> Click here</button>
                       </div>
        </div>
    </div>
</div>

</body>
</html>