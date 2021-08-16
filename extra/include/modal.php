<div class="modal fade in signin_signup" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="padding-right: 0 !important">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                <h4 class="modal-title"><?php echo $lang_array['account_login']?></h4>
                <h5 class="modal-subtitle"><?php echo $lang_array['not_member_yet_register_now_its_free']?></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
						<a href="<?php echo rootpath()?>/fbLogin/fbconfig.php"><button class="btn btn-lg signup-fb signup-btn"><i class="fa fa-facebook"></i> &nbsp;<?php echo $lang_array['login_with_facebook']?></button></a>
						<a href="<?php echo rootpath()?>/twitterLogin/login.php"><button class="btn btn-lg signup-tw signup-btn"><i class="fa fa-twitter"></i> &nbsp;<?php echo $lang_array['login_with_twitter']?></button></a>
						<hr>

						<p><?php echo $lang_array['dont_have_an_account_click_a_service_to_make_one']?></p>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->