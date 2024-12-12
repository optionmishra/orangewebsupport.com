<style>
    .site-footer {
        padding: 10px 0 0 !important;
        background-color: transparent !important;
    }

    .backvideo {
        position: absolute;
        max-height: 224px;

    }

    .bt2 {
        background-color: #007bff;
        margin-top: 20px;
    }

    .bor-gr {
        border-color: orange !important;
    }

    .ft_img {
        width: 70px;
        height: 50px;
    }

    .wa-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        margin: 20px;


    }

    @media(max-width:680px) {
        .nw-footer-list {
            text-align: left !important;
        }

        .nw-footer-list .nw-img {
            margin: 0 !important;
        }

        .footer-pdg .ft-lst {
            text-align: left !important;
        }

        .footer-pdg .ft-lst .nw-ft-img {
            float: left;
            width: 50%;
        }

        .footer-pdg .ft-lst .li-list-nw .footer-align {
            float: left;
        }

        .nw-footer-list li::after {
            right: unset;
        }
    }
</style>
<!-- Footer Area -->
<div class="icon-bar">
    <a href="<?php echo $weblink1['value']; ?>" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a>
    <a href="<?php echo $weblink2['value']; ?>" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a>
    <a href="<?php echo $weblink3['value']; ?>" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a>

</div>

<!-- <footer class="site-footer">
      <div class="container-fluid footer_inner">
        <div class="row">
          <div class="col-sm-12 col-md-4 footer-pdg">
            <h3 style="margin-bottom:10px;font-size:20px;font-weight:400;color:#fff;font-family:'Roboto',sans-serif;padding-top:27px;">More from us:</h3>
          <div class="row">
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/touchcode.svg" style="width:92px;"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/ogo.svg" style="width:63px;"></div>
		  <div class="col-md-4 col-sm-4 col-4 p-0"><img class="ft_img" src="assets/footer_img/logos/nbse.svg" style="width:63px"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/atl.svg" style="width:40px;"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/mindmaps.svg" style="width:77px;"></div>
		  </div>
          </div>

  <div class="col-sm-12 col-md-4 footer-pdg">
          <ul class="footer-links text-center nw-footer-list">
              <li><img class="nw-img" src="assets/footer_img/logos/orangefull.svg"></li>
              <li style="color:#eee;"> 
			    <?php foreach ($mobile1 as $mo): ?>
                                <span><a style="font-weight:300;font-size:13px;"> <i class="fa fa-phone"></i> <?= $mo->value ?></a></span>&nbsp;
                                <?php endforeach; ?>
                                 <?php foreach ($mobile2 as $mob): ?>
                                <span><a style="font-weight:300;font-size:13px;"> <i class="fa fa-phone"></i> <?= $mob->value ?></a></span>
                                <?php endforeach; ?>
							  <p> <?php foreach ($email1 as $em): ?>
                                <span><a data-toggle="modal" data-target="#add-new-contact" style="color:#fff;font-weight:300;font-size:13px;"><i class="fa fa-envelope"></i>&nbsp;<?= $em->value ?></a></span>&nbsp;&nbsp;
                                <?php endforeach; ?>
                                <?php foreach ($email2 as $email): ?>
                                <span><a data-toggle="modal" data-target="#add-new-contact" style="color:#fff;font-weight:300;font-size:13px;"><i class="fa fa-envelope"></i>&nbsp;<?= $email->value ?></a></span>
                                <?php endforeach; ?></p>
			  </li>
              <li style="color:#eee;margin-top:1px;margin-bottom:3px;font-weight:300;font-size:13px;">Copyright ©2021 All rights reserved Orange Education</li>
            </ul>
          </div>
		    <div class="col-sm-12 col-md-4 footer-pdg">
         <ul class="footer-links text-right ft-lst">
              <li class="li-list-nw"><img class="nw-ft-img" src="assets/footer_img/logos/touchpadfooterright.svg"></li>
              <li class="li-list-nw">
			  <ul class="footer-align mb-2">
			  <li class=""><a class="ftr-icon" href="https://www.facebook.com/TouchpadComputerSeries/"><i class="fa fa-facebook"></i></a></li>
			  <!--<li class=""><a class="ftr-icon" href="#"><i class="fa fa-twitter"></i></a></li>
			  <li class=""><a class="ftr-icon" href="https://www.instagram.com/touchpadcomputerseries/"><i class="fa fa-instagram"></i></a></li>
			  <li class=""><a class="ftr-icon" href="#"><i class="fa fa-youtube-play"></i></a></li>
			  <li class=""><a class="ftr-icon" href="#"><i class="fa fa-linkedin"></i></a></li>
			  </ul>
			  </li>
              <li class="li-list-nw" style="color:#fff;">Be in touch with us on social media<br>for latest updates</li>
            </ul>
          </div>
        </div>
        
      </div>

</footer>-->

<!--   ----------------------------------Home Footer code--------------------------------------- -->

<footer class="site-footer">
    <video class="backvideo" autoplay muted playsinline loop preload="auto">
        <source src="<?= base_url('/assets/home/footvid.mp4') ?>" type="video/mp4">
    </video>
    <div class="container-fluid footer_inner">
        <div class="row">
            <!--<div class="col-sm-12 col-md-4 footer-pdg">
          <h3 style="margin-bottom:10px;font-size:18px;font-weight:400;padding-top: 27px;">More from us:</h3>
          <div class="row">
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/touchcode.svg" style="width:92px;"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/ogo.svg" style="width:63px;"></div>
		  <div class="col-md-4 col-sm-4 col-4 p-0"><img class="ft_img" src="assets/footer_img/logos/nbse.svg" style="width:63px"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/atl.svg" style="width:40px;"></div>
		  <div class="col-md-4 col-sm-4 col-4"><img class="ft_img" src="assets/footer_img/logos/mindmaps.svg" style="width:77px;"></div>
		  </div>
          </div>-->

            <div class="col-md-12 footer-pdg">
                <ul class="footer-links text-center nw-footer-list">
                    <li><img class="nw-img" src="assets/footer_img/logos/orangefull.svg" style="width:250px;"></li>
                    <li style="color:#eee;">9,Daryaganj, New Delhi-110002 <br>
                        <i class="fa fa-envelope"></i> info@orangeeducation.in &nbsp&nbsp; <i class="fa fa-phone"></i> 011-43776600
                    </li>
                    <li style="color:#eee;margin-top:3px;font-size:11px;margin-bottom:3px;">Copyright ©2021 All rights reserved Orange Education</li>
                </ul>
            </div>


            <div class="wa-icon">
                <a href="https://wa.me/918588814859?text=I%27m%20interested%20in%20Orange%20Education%27s%20web%20support"><img style="width: 50px" src="assets/home/img/whatsapp.png" alt="" /></a>
            </div>


            <!--<div class="col-sm-12 col-md-4 footer-pdg">
         <ul class="footer-links text-right ft-lst">
              <li class="li-list-nw"><img class="nw-ft-img" src="assets/footer_img/logos/touchpadfooterright.svg"></li>
              <li class="li-list-nw">
			  <ul class="footer-align mb-2">
			  <li class=""><a class="ftr-icon" href="https://www.facebook.com/TouchpadComputerSeries/"><i class="fa fa-facebook"></i></a></li>
			  <!--<li class=""><a class="ftr-icon" href="#"><i class="fa fa-twitter"></i></a></li>
			  <li class=""><a class="ftr-icon" href="https://www.instagram.com/touchpadcomputerseries/"><i class="fa fa-instagram"></i></a></li>
			  <li class=""><a class="ftr-icon" href="#"><i class="fa fa-youtube-play"></i></a></li>
			  <li class=""><a class="ftr-icon" href="#"><i class="fa fa-linkedin"></i></a></li>
			  </ul>
			  </li>
              <li class="li-list-nw">Be in touch with us on social media<br>for latest updates</li>
            </ul>
          </div>-->

</footer>




<!--   ----------------------------------------------------------------------------------------- -->

<!--<footer id="footer" class="footer-area footer--2">
    
    <div class="footer__contact__area bg__cat--2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__contact__wrapper d-flex flex-wrap justify-content-between">
                        <div class="single__footer__address">
                            <div class="ft__contact__icon">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="ft__contact__details">
                                <?php foreach ($address as $add): ?>
                                <p><?= $add->value ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="single__footer__address">
                            <div class="ft__contact__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="ft__contact__details">
                                 <?php foreach ($mobile1 as $mo): ?>
                                <p><a><?= $mo->value ?></a></p>
                                <?php endforeach; ?>
                                 <?php foreach ($mobile2 as $mob): ?>
                                <p><a><?= $mob->value ?></a></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="single__footer__address">
                            <div class="ft__contact__icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="ft__contact__details">
                                <?php foreach ($email1 as $em): ?>
                                <p><a data-toggle="modal" data-target="#add-new-contact"><?= $em->value ?></a></p>
                                <?php endforeach; ?>
                                <?php foreach ($email2 as $email): ?>
                                <p><a data-toggle="modal" data-target="#add-new-contact"><?= $email->value ?></a></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright  bg--theme">
        <div class="container">
            <div class="row align-items-center copyright__wrapper justify-content-center">
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <div class="coppy__right__inner text-center">
                        <p><i class="fa fa-copyright"></i>All Right Reserved.<?php foreach ($copyright as $copy): ?><a href="#"> <?= $copy->value; ?></a><?php endforeach; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>-->
<!-- //Footer Area -->

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
                                <input type="text" class="form-control" id="student_mobile" name="mobile" required="true">
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
                                <input type="text" class="form-control" id="student_pin" name="pin" required="true">
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
                                    <?php foreach ($count as $cou): ?>
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
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class->id ?>"><?= $class->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 p-2">
                            <div class="form-group">
                                <label for="student_subject">Subject *</label>
                                <select class="form-control" name="subject" id="student_subjectr" required="true">
                                    <?php foreach ($msubject as $class): ?>
                                        <option value="<?= $class->id ?>"><?= $class->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary float-right">Register</button>
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
            <form id="addTeacher" class="smooth-submit" method="post" action="<?= base_url('admin_master/add_teacher') ?>">
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
                                <input type="text" class="form-control" id="teacher_mobile" name="mobile" required="true">
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
                                <input type="text" class="form-control" id="teacher_pin" name="pin" required="true">
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
                                <label for="teacher_addresss">Address(Teacher)</label>
                                <textarea class="form-control" id="teacher_addresss" name="addresss"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 p-2">
                            <div class="form-group">
                                <label for="teacher_state">State *</label>
                                <select class="form-control" name="state" id="teacher_state" required="true">
                                    <option value="">--Select State--</option>
                                    <?php foreach ($count as $cou): ?>
                                        <option value="<?= $cou->StateID ?>"><?= $cou->StateName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 p-2">
                            <div class="form-group">
                                <label for="teacher_city">City *</label>
                                <input type="text" class="form-control" id="teacher_city" name="city" required="true">
                            </div>
                        </div>
                        <div class="col-lg-3 p-2">
                            <div class="form-group">
                                <label for="teacher_dob">DOB</label>
                                <input type="date" class="form-control" id="teacher_dob" name="dob">
                            </div>
                        </div>
                        <div class="col-lg-3 p-2">
                            <div class="form-group">
                                <label for="teacher_emails">Email(Personal)</label>
                                <input type="text" class="form-control" id="teacher_emails" name="emailss">
                            </div>
                        </div>


                        <div class="col-lg-2 p-2">
                            <span>Subject *</span>
                        </div>
                        <div class="col-lg-10 p-2">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-control-custom" id="teacher_subject" name="checkall" value="check All">
                                        <label class="form-check-label" for="student_subject_0">
                                            Check All
                                        </label>
                                    </div>
                                </div>
                                <?php foreach ($msubject as $sub): ?>
                                    <div class="col-lg-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-control-custom ss" id="student_subject_<?= $sub->id ?>" name="subject[]" value="<?= $sub->id ?>">
                                            <label class="form-check-label" for="student_subject_<?= $sub->id ?>">
                                                <?= $sub->name ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-lg-2 p-2">
                            <span>Classes *</span>
                        </div>
                        <div class="col-lg-10 p-2">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-control-custom" id="teacher_class" name="checkall" value="check All">
                                        <label class="form-check-label" for="student_class_0">
                                            Check All
                                        </label>
                                    </div>
                                </div>
                                <?php foreach ($classes as $class): ?>
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
                    <button class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary float-right">Register</button>
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
                    <button class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary float-right">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- //Register Student Form End -->

<!-- //Main wrapper -->
<div class="modal" id="pleasewait" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width:100px;height:100px;border-radius:50%;background-color:#fff;margin:50% auto;">
            <div class="modal-body">
                <img class="fit-image" src="<?= base_url('assets/img/tp-gif.gif') ?>" style="object-fit:none;">
            </div>
        </div>
    </div>
</div>
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
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#srch_tbl').DataTable();
    });
</script>
<!--<script>
$(document).ready(function() {
    $('#techr_tbl').DataTable();
} );
</script>-->
<script>
    $(document).ready(function() {
        $('#assign').DataTable();
    });
</script>

<!--<script type="text/javascript">
   	$(document).ready(function(){
  		$("#myModal").modal({
  			keyboard: true,
  			backdrop: "static",
  			show: false,
  
  		}).on("show.bs.modal", function(event){
  		  var button = $(event.relatedTarget); 
  			var personId = button.data("id"); 
  		
  			//displays values to modal
  			$(this).find("#personDetails").html($("<b>ID: " + personId +  "</b>"))
  		}).on("hide.bs.modal", function (event) {
			$(this).find("#personDetails").html("");
		});
  	});
</script>-->


</body>

</html>