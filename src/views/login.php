<div class="account-container">
	
	<div class="content clearfix">
		
		<?php echo Form::open(array('method' => 'post'), array("class"=>"form-horizontal") );?>
		
			<h1>Member Login</h1>		
			
			<div class="login-fields">
				
				<p>Please provide your details</p>
				
				<div class="field">
					<label for="username">Email</label>
					<?php echo Form::text("email", null, array("class"=>"login username-field", "placeholder"=>trans("auth::form.login.email"))); ?>
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<?php echo Form::password("pass", array("class"=>"login password-field", "placeholder"=>trans("auth::form.login.password"))); ?>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<?php echo Form::checkbox('remember', '1', false, array('class'=>"field login-checkbox") );?>
					<label class="choice" for="Field">Keep me signed in</label>
				</span>
				
				<?php echo Form::button(trans("auth::form.login.login"), array("class"=>"btn btn-success btn-large", "type"=>"submit"));?>
				
			</div> <!-- .actions -->
			
			
			
		<?php echo Form::close();?>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->


<?php if(!Request::is('admin/*')){ ?>
<div class="login-extra">
	<a href="#">Reset Password</a>
</div> <!-- /login-extra -->
<?php } ?>