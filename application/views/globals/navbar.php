<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
	<?php foreach ($row as $print) : ?>
		<div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= base_url('assets/img/' . $print->profile_img . '') ?>" alt="User Image">
			<div>
				<p class="app-sidebar__user-name"><?= $print->name ?></p>
				<p class="app-sidebar__user-designation"><?= $print->level ?></p>
			</div>
		</div>
	<?php endforeach; ?>
	<ul class="app-menu">
		<li><a class="app-menu__item active" href="<?= base_url("superadmin/dashboard"); ?>"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
		<li><a class="app-menu__item" href="<?= base_url("superadmin/Notification"); ?>"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Notifications</span></a></li>
		<li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="fa fa-user app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> User</span><i class="treeview-indicator fa fa-angle-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="superadmin/permission" class="treeview-item"> <i class="icon fa fa-key" aria-hidden="true"></i>Permission</a></li>
				<li><a href="superadmin/user" class="treeview-item"> <i class="icon fa fa-user" aria-hidden="true"></i>User</a></li>
				<li><a href="superadmin/web_user" class="treeview-item"> <i class="icon fa fa-user" aria-hidden="true"></i>Student</a></li>
				<li><a href="superadmin/web_user_teacher" class="treeview-item"> <i class="icon fa fa-user" aria-hidden="true"></i>Teacher</a></li>
				<li><a href="superadmin/salesman" class="treeview-item"> <i class="icon fa fa-user" aria-hidden="true"></i>Add Sales Person</a></li>
			</ul>
		</li>
		<li><a class="app-menu__item" href="superadmin/board"><i class="fa fa-graduation-cap app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Boards</span></a></li>
		<li><a class="app-menu__item" href="superadmin/publication"><i class="fa fa-book app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Publications</span></a></li>
		<li><a class="app-menu__item" href="superadmin/test_question"><i class="fa fa-book app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Test Questions</span></a></li>
		<li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="fa fa-book app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Subjects</span><i class="treeview-indicator fa fa-angle-right"></i></a>
			<ul class="treeview-menu">
				<li><a href="superadmin/msubject" class="treeview-item"> <i class="icon fa fa-book" aria-hidden="true"></i>Main Subject</a></li>

				<li><a href="superadmin/subject" class="treeview-item"> <i class="icon fa fa-book" aria-hidden="true"></i>Books</a></li>

				<!-- <li><a href="superadmin/summativeQuestion" class="treeview-item"> <i class="icon fa fa-book" aria-hidden="true"></i>Summative Question</a></li>

				<li><a href="superadmin/objectQuestion" class="treeview-item"> <i class="icon fa fa-book" aria-hidden="true"></i>Object Question</a></li> -->
			</ul>
		</li>
		<li><a class="app-menu__item" href="superadmin/classes"><i class="fa fa-book app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Classes</span></a>
		<li><a class="app-menu__item" href="superadmin/classess_section"><i class="fa fa-book app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Class Section</span></a>
		<li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="fa fa-question-circle-o app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Question Generator</span><i class="treeview-indicator fa fa-angle-right"></i></a>
			<ul class="treeview-menu">
				<li><a class="treeview-item" href="superadmin/category"><i class="fa fa-list icon" aria-hidden="true"></i> Category</a>
					<?php foreach ($cat as $ca) : ?>
				<li><a href="superadmin/websupport/<?= $ca->id ?>" class="treeview-item"> <i class="icon fa fa-play-circle-o" aria-hidden="true"></i><?= $ca->name ?></a></li>
			<?php endforeach; ?>
			</ul>
		</li>
		<li><a class="app-menu__item" href="superadmin/content"><i class="fa fa-list app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> Web Content</span></a>
		<li><a class="app-menu__item" href="superadmin/content_update"><i class="fa fa-list app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label">Digital Content Update</span></a>
		<li class="treeview">

			<a class="app-menu__item" href="superadmin/state"><i class="fa fa-graduation-cap app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> States</span></a>

			<a class="app-menu__item" href="superadmin/city"><i class="fa fa-graduation-cap app-menu__icon" aria-hidden="true"></i> <span class="app-menu__label"> City</span></a>

	</ul>
</aside>