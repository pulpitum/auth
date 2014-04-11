<<<<<<< HEAD
<div class="row-fluid">

    <div class="panel panel-default">
    	<div class="panel-heading">
			<div class="row">
				<div class="col-md-12">
					<div class="title_wrapper">
			        	<h4 class="pull-left">Perfil</h4>
						<div class="clear"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<fieldset>
								<div class="fieldset_wrapper">
									<?php echo Former::legend("Os meus dados"); ?>
									<div class="form-group">
										<div class="col-sm-12">
											<span class="label"><?php echo trans("Primeiro nome").":</span> ". $model->first_name;?><br />
											<span class="label"><?php echo trans("Último nome").":</span> ". $model->last_name;?><br />
											<span class="label"><?php echo trans("Email").":</span> ". $model->email;?><br />
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</fieldset>
						</div>
						<div class="col-md-6">
							<?php echo Former::horizontal_open()->secure()->action(URL::route("PostChangePassword")); Former::populate($model); ?>
							<fieldset>
								<div class="fieldset_wrapper">
									<?php echo Former::legend("Mudar password"); ?>
									<div class="form-group">
										<div class="col-sm-12">
											<?php echo Former::password("password")->label('Palavra-passe'); ?>
											<?php echo Former::password("password_confirmation")->label("Confirmar Palavra-passe"); ?>
										</div>
									</div>
									<?php echo Former::actions(Former::button_submit(trans('lactiweb::form.save') )->addClass("submit_button")->disabled("disabled") );?>
								</div>
								<div class="clear"></div>
							</fieldset>
							<?php echo  Former::hidden("id", $model->id); ?>
							<?php echo  Former::close(); ?>
						</div>
						<div class="clear"></div>
					</div>
			    </div>
			</div>
	    </div>
    </div>
</div>
=======
<div class="row-fluid">

    <div class="panel panel-default">
    	<div class="panel-heading">
			<div class="row">
				<div class="col-md-12">
					<div class="title_wrapper">
			        	<h4 class="pull-left">Perfil</h4>
						<div class="clear"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<fieldset>
								<div class="fieldset_wrapper">
									<?php echo Former::legend("Os meus dados"); ?>
									<div class="form-group">
										<div class="col-sm-12">
											<span class="label"><?php echo trans("Primeiro nome").":</span> ". $model->first_name;?><br />
											<span class="label"><?php echo trans("Último nome").":</span> ". $model->last_name;?><br />
											<span class="label"><?php echo trans("Email").":</span> ". $model->email;?><br />
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</fieldset>
						</div>
						<div class="col-md-6">
							<?php echo Former::horizontal_open()->secure()->action(URL::route("PostChangePassword")); Former::populate($model); ?>
							<fieldset>
								<div class="fieldset_wrapper">
									<?php echo Former::legend("Mudar password"); ?>
									<div class="form-group">
										<div class="col-sm-12">
											<?php echo Former::password("password")->label('Palavra-passe'); ?>
											<?php echo Former::password("password_confirmation")->label("Confirmar Palavra-passe"); ?>
										</div>
									</div>
									<?php echo Former::actions(Former::button_submit(trans('lactiweb::form.save') )->addClass("submit_button")->disabled("disabled") );?>
								</div>
								<div class="clear"></div>
							</fieldset>
							<?php echo  Former::hidden("id", $model->id); ?>
							<?php echo  Former::close(); ?>
						</div>
						<div class="clear"></div>
					</div>
			    </div>
			</div>
	    </div>
    </div>
</div>
>>>>>>> 7e05397e8dd104beba263c8588a3b09fc8b8e11c
