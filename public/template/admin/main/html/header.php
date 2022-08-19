<?php
	$linkControlPanel 		= URL::createLink('admin', 'index', 'index');
	$linkMyProfile			= URL::createLink('admin', 'index', 'profile');
	$linkUserManager		= URL::createLink('admin', 'user', 'index');
	$linkAddUser			= URL::createLink('admin', 'user', 'form');
	$linkGroupManager		= URL::createLink('admin', 'group', 'index');
	$linkAddGroup			= URL::createLink('admin', 'group', 'form');
	$linkCategoryManager	= URL::createLink('admin', 'category', 'index');
	$linkAddCategory		= URL::createLink('admin', 'category', 'form');
	$linkBookManager		= URL::createLink('admin', 'book', 'index');
	$linkAddBook			= URL::createLink('admin', 'book', 'form');
	$linkLogout				= URL::createLink('admin', 'index', 'logout');
	$linkViewSite			= URL::createLink('default', 'index', 'index');
?>
<div id="border-top" class="h_blue">
	<span class="title"><a href="#">Administration</a></span>
</div>

<!-- HEADER -->
<div id="header-box">
	<div id="module-status">
		<span class="viewsite"><a href="<?= $linkViewSite?>" target="_blank">View Site</a></span>
		<span class="no-unread-messages"><a href="<?= $linkLogout?>">Log out</a></span>
	</div>
	<div id="module-menu">
		<!-- MENU -->
		<ul id="menu">
			<li class="node"><a href="#">Site</a>
				<ul>
					<li><a class="icon-16-cpanel" href="<?= $linkControlPanel;?>">Control Panel</a></li>
					<li class="separator"><span></span></li>
					<li><a class="icon-16-profile" href="<?= $linkMyProfile;?>">My Profile</a></li>
				</ul>
			</li>
			<li class="separator"><span></span></li>
			<li class="node"><a href="#">Users</a>
				<ul>
					<li class="node">
						<a class="icon-16-user" href="<?= $linkUserManager;?>">User Manager</a>
						<ul id="menu-com-users-users" class="menu-component">
							<li>
								<a class="icon-16-newarticle" href="<?= $linkAddUser;?>">Add New User</a>
							</li>
						</ul>
					</li>
						
					<li class="node">
						<a class="icon-16-groups" href="<?= $linkGroupManager;?>">Groups</a>
						<ul id="menu-com-users-groups" class="menu-component">
							<li>
								<a class="icon-16-newarticle" href="<?= $linkAddGroup;?>">Add New Group</a>
							</li>
						</ul>
					</li>

				</ul>
			</li>
			<li class="node"><a href="#">Book Shopping</a>
				<ul>
					<li class="node">
						<a class="icon-16-category" href="<?= $linkCategoryManager;?>">Category</a>
						<ul id="menu-com-users-groups" class="menu-component">
							<li>
								<a class="icon-16-newarticle" href="<?= $linkAddCategory;?>">Add New Category</a>
							</li>
						</ul>
					</li>
					
					<li class="node">
						<a class="icon-16-article" href="<?= $linkBookManager;?>">Book</a>
						<ul id="menu-com-users-groups" class="menu-component">
							<li>
								<a class="icon-16-newarticle" href="<?= $linkAddBook;?>">Add New Book</a>
							</li>
						</ul>
					</li>

				</ul>
			</li>
		</ul>
	</div>

	<div class="clr"></div>
</div>