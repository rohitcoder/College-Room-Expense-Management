<?php
error_reporting(0);
defined("APP") or die();
if (!isset($_SESSION))  session_start();
include 'include/header.php';
include 'admin/captchalib.php';
$csrfError = false;
$webdata=getWebDate();

$adsdata=getAdsData();
?>
<title>Contact Us</title>
<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/contact.php'?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />

<?php
include("include/header_under.php");

$error  = false;

if(isset($_POST['submit']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
    $csrfError = true;
	$name = xssClean(mres(trim($_POST["name"])));
	
	$from = xssClean(mres(trim($_POST["email"])));
	
	$subject = xssClean(mres(trim($_POST["subject"])));
	
	$message = xssClean(mres(trim($_POST["message"])));
	
	if(!validName($name))
	{
		$nameError = '<span class="label label-danger">'.$lang_array['only_letters_a-Z_allowed'].'</span>';
		$error = true;
	}
	
	if(!validEmail($from))
	{
		$emailError = '<span class="label label-danger">'.$lang_array['please_enter_valid_email_address'].'</span>';
		$error =  true;
	}
	
	if(!isAlpha($subject))
	{
		$subjectError = '<span class="label label-danger">'.$lang_array['only_letters_a-Z_and_numbers_0-9_allowed'].'</span>';
		$error = true;
	}
	
	if($message == "")
	{
		$messageError = '<span class="label label-danger">'.$lang_array['should_not_be_empty'].'</span>';
		$error = true;
	}
	
	if(onOffContactCaptcha()) 
	{
		if (trim(strtolower($_POST['captcha'])) != $_SESSION['captcha'])
		{
			$captchaError = '<span class="label label-danger">'.$lang_array['invalid_captcha_code'].'</span>';
			$error = true;
		}
	} 
	
	if(!$error && !$csrfError)
	{
		sendEmailThroughContact(getAdminEmail(), $from, $name, $subject, $message);
		?>
		<script>
			$(function() {
				$("input[name='name']").val("");
				$("input[name='email']").val("");
				$("input[name='subject']").val("");
				document.getElementById('messageBox').value = "";
			});
			</script>
		<?php
	}
	unset($_SESSION['captcha']);
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<section class="theme-card mtop50">
	<section class="content-header">
		<h1>
			<?php echo $lang_array['contact_us']?>
			<small><?php echo $lang_array['contact_using_form_below']?></small>
		</h1>
		<?php
		if($adsdata['largeRect2Status'])
		{
		?>
		<div class="ad-tray-728 <?php echo ($adsdata['largeRect2StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
			<?php echo $adsdata['largeRect2'];?>
		</div>
		<?php
		}
		?>
	</section>
	<div class="row">
		<div class="col-xs-12">
		<?php
			if(isset($_POST['submit']) && !$error)
			{
				?>
				<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-7 col-lg-offset-1 col-md-8">
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong><?php echo $lang_array['success']?></strong> <?php echo $lang_array['success_msg_main']?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<div class="col-xs-12">
			<form role="form" action="contact" method="POST">
				<div class="form-group">
					<div class="col-sm-2 col-lg-1"><label for="name"><?php echo $lang_array['name']?></label></div>
					<div class="col-sm-8 col-lg-7">
						<input type="text" name="name" class="form-control" placeholder="<?php echo $lang_array['enter_your_name']?>" value="<?php echo $name ?>" required />
						<?php if($nameError) echo $nameError ?>
					</div>
				</div>
				
				<div class="clearfix"></div>
				<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
				<div class="form-group contact-margin-top">
					<div class="col-sm-2 col-lg-1"><label for="email"><?php echo $lang_array['email']?></label></div>
					<div class="col-sm-8 col-lg-7">
						<input type="email" class="form-control" id="email" name="email" value="<?php echo $from ?>" placeholder="<?php echo $lang_array['enter_your_email']?>" required />
						<?php if($emailError) echo $emailError ?>					
					</div>
				</div>
				
				<div class="clearfix"></div>
			 
				<div class="form-group contact-margin-top">
					<div class="col-sm-2 col-lg-1"><label for="subject"><?php echo $lang_array['subject']?></label></div>
					<div class="col-sm-8 col-lg-7">
						<input type="text" class="form-control" name="subject" placeholder="<?php echo $lang_array['enter_your_subject']?>" value="<?php echo $subject ?>" required />
						<?php if($subjectError) echo $subjectError ?>
					</div>
				</div>
			 
				<div class="clearfix"></div>
			 
				<div class="form-group contact-margin-top">
					<div class="col-sm-2 col-lg-1"><label for="subject"><?php echo $lang_array['message']?></label></div>
					<div class="col-sm-8 col-lg-7">
						<textarea type="text" id="messageBox" class="form-control message-box" name="message" placeholder="<?php echo $lang_array['enter_your_message']?>" style="resize:none" required ><?php echo ($message);?></textarea>
						<?php if($messageError) echo $messageError ?>
					</div>
				</div>
				
				<div class="clearfix"></div>
				
				<?php 
				if(onOffContactCaptcha()) 
				{ 
					?>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4 col-lg-offset-1 col-lg-10 contact-margin-top">
						<div class="col-lg-4">
							<img src="admin/captcha/captcha.php" id="captcha" /><br/>
							<a href="#" onclick="
								document.getElementById('captcha').src='admin/captcha/captcha.php?'+Math.random();
								document.getElementById('captcha-form').focus();"
								id="change-image">Not readable? Change text.
							</a><br/>
						</div>
						</div>
					</div>
					
					<div class="clearfix"></div>
					
					<div class="form-group contact-margin-top">
						<div class="col-sm-2 col-lg-1"><label for="captcha" class="col-lg-2 control-label" style="margin-top:10px;">Captcha</label>
						<label><?php echo $lang_array['']?></label></div>
						<div class="col-sm-6 col-md-4">
							<input type="text" class="form-control" name="captcha" placeholder="<?php echo $lang_array['enter_captcha_code']?>" required />
							<?php if($captchaError) echo $captchaError ?>
						</div>
					</div>
					<?php 
				} 
				?>
				<div class="clearfix"></div>
				
				<div class="col-lg-offset-1 col-sm-offset-2 col-sm-7 contact-margin-top">
					<button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span>&nbsp; <?php echo $lang_array['send']?></button>
				</div>
			</form>
		</div>
		<div class="clearfix"></div>
		</br>
		<?php
		if($adsdata['largeRect3Status'])
		{
		?>
		<div class="ad-tray-728 <?php echo ($adsdata['largeRect3StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
		<?php echo $adsdata['largeRect3'];?>
		</div>
		<?php
		}
		?>
		
		<div class="clearfix"></div> 
	</div>
	<br />
</section>
<?php include("include/footer.php") ?>