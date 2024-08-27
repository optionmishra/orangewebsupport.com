

<?php if ($this->session->flashdata('success')) { ?>

        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><?php echo $this->session->flashdata('success'); ?></strong>
        </div>

<?php } ?>

<?php if ($this->session->flashdata('error')) { ?>

        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><?php echo $this->session->flashdata('error'); ?></strong>
        </div>

<?php } ?>
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb m-0">
            <li class="breadcrumb-item"><?= $this->session->userdata('board_name') ?></li>
            <li class="breadcrumb-item"><?= $this->session->userdata('publication_name') ?></li>
            <?php if ($this->session->userdata('type') == 'Student') { ?>
                <li class="breadcrumb-item">Class <?= $this->session->userdata('classes') ?></li>
            <?php } else { ?>
                <li class="breadcrumb-item">Class <?= $this->session->userdata('classes') ?></li>
            <?php } ?>
            <li class="breadcrumb-item"><?= $sub[0]->name ?></li>
            <li class="breadcrumb-item"><?= $this->session->userdata('category_name') ?></li>
            <input type="text" id="active" value="<?= $this->session->userdata('category') ?>" class="d-none">
        </ul>
    </div>
    <form id="Selform" method="post" class="smooth-submit p-3" action="admin_master/default_product">
        <div class="row">
            <div class="col-lg-1 pl-2 pr-3">
                <select id="select_board" class="col-lg-12 p-0 m-0 custom-select" name="select_board" required="true">
                    <?php foreach ($board as $bo): ?>
                        <option value="<?= $bo->id ?>" <?= $bo->id == $this->session->userdata('board') ? ' selected="selected"' : ''; ?>><?= $bo->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3 pl-2 pr-3">    
                <select id="select_publication" class="col-lg-12 p-0 m-0 custom-select" name="select_publication" required="true">
                    <?php foreach ($publication as $pub): ?>
                        <option value="<?= $pub->id ?>" <?= $pub->id == $this->session->userdata('publication') ? ' selected="selected"' : ''; ?>><?= $pub->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 pl-2 pr-3">
                <select id="select_classes" class="col-lg-12 p-0 m-0 custom-select" name="select_classes" required="true">
                    <?php foreach ($classes as $cl): ?>
                        <option value="<?= $cl->id ?>" <?= $cl->id == $this->session->userdata('classes') ? ' selected="selected"' : ''; ?>><?= $cl->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="text" class="d-none" value="<?= $this->session->userdata('msubject') ?>" id="msub_d" required="true" />
            <div class="col-lg-2 pl-2 pr-3">
                <select id="select_msubject" class="col-lg-12 p-0 m-0 custom-select" name="select_msubject" required="true" >
					<?php if($this->session->userdata('board')==1){?>
                    
                    
					<option value="25" <?= 25 == $msub[0]->id ? ' selected="selected"' : ''; ?>>Touchpad PRIME Ver 1.0</option>
                    <option value="27" <?= 27 == $msub[0]->id ? ' selected="selected"' : ''; ?>>Touchpad PRIME  Ver 2.0</option>
					<option value="26" <?= 26 == $msub[0]->id ? ' selected="selected"' : ''; ?>>Touchpad PLUS Ver 2.0</option>
					<?php }else{?> 
                    <option value="28" <?= 28 == $msub[0]->id ? ' selected="selected"' : ''; ?>>Touchpad iPRIME Ver 1.0</option>
                    <option value="29" <?= 29 == $msub[0]->id ? ' selected="selected"' : ''; ?>>Touchpad iPRIME Ver 2.0</option>
					<?php }?>
                </select> 
            </div>
           <!--<input type="text" class="d-none" value="<?= $this->session->userdata('subject') ?>" id="sub_d" required="true" />
            <div class="col-lg-2 pl-2 pr-3">
                <select id="select_subject" class="col-lg-12 p-0 m-0 custom-select" name="select_subject" required="true" >

                </select>
            </div>-->
            <div class="col-lg-2 pl-2 pr-3">
                <button class="btn btn-primary float-right sp-button">Search</button>
            </div>

        </div>
    </form>

    <div class="row m-0 p-0">
       <div class="col-lg-3 p-2 m-0 home-side">
            <div class="wmain_sidebar">
		<?php 
		$userdata = $this->session->userdata();	

		?>
                <ul>
				<?php 
				foreach ($category as $cat)
				{ 
				
	
					if($cat->allow=='Demo')
					{
					?> 
							<li class="" id="active<?= $cat->id ?>">
								<a tab_id="<?= $cat->id ?>" class="new-search"><?= $cat->name ?></a>
							</li>
					<?php 
					}
				} 
				?>
                </ul>
            </div>
        </div>        <div class="col-lg-9 pl-5 m-0 p-0">
            <div class="row m-0">
                <?php if (empty($default)) { ?>
                    <p class="text-danger" style="font-size: 30px"> Coming Soon.....</p>
                <?php
                } else {
                    foreach ($default as $def):
                        ?>
                        <div class="col-lg-3 p-2 m-0">
                            <a href="assets/files/<?= $def->file_name ?>" class="p-0 m-0 digital-con" target="_blank">
                                <div class="row m-0 p-0">
                                    <div class="col-lg-12 p-2 m-0 top-con">
                                        <h5>Click Here! For Download</h5>
                                    </div>
                                    <div class="col-lg-12 p-3 m-0 middle-con">
                                        <img src="assets/img/download2.png">
                                    </div>
                                    <div class="col-lg-12 p-2 m-0 bottom-con"> 
                                        <h4><?= $def->title ?></h4> 
                                        <h6>Class <?= $def->classes ?></h6>
                                    </div>
									
                                </div> 
                            </a>
							
                        </div>
                    <?php endforeach;
                }
                ?>
            </div>
        </div>
    </div>
</main>
