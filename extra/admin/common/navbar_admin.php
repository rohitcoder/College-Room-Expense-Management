 <!-- Logo & Navigation starts -->
      
      <div class="header">
         <div class="container">
            <div class="row">
               <div class="col-xs-6">
                  <!-- Logo -->
                  <div class="logo">
                      <h1><a href="<?php echo(rootpath()) ?>/admin/dashboard.php"><img src="<?php echo rootpath()?>/images/logo/<?php echo frontPageLogo().'?'.time();?>"></a></h1>
                  </div>
               </div>
               <div class="col-xs-6">
                  <div class="navbar navbar-inverse" role="banner">
                      <nav class="navbar-right" role="navigation">
						
                        <ul class="nav navbar-nav">
							<li><a href="<?php echo rootpath()?>" target="_blank">Visit Website</a></li>
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
                            <ul class="dropdown-menu animated fadeInUp">
                              <li><a href="profile.php"><i class="icon-user"></i>&nbsp;Profile</a></li>
                              <li><a href="logout.php"><i class="icon-off"></i>&nbsp;Logout</a></li>
                            </ul>
                          </li>
                        </ul>
                      </nav>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Logo & Navigation ends -->