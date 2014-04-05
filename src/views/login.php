<div class="container">    
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title"><?php echo trans("auth::form.login.signin");?></div>
            </div>     
            <div style="padding-top:30px" class="panel-body" >
                <?php echo Form::open(array('method' => 'post'), array("class"=>"form-horizontal") );?>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <?php echo Form::text("email", null, array("class"=>"form-control", "placeholder"=>trans("auth::form.login.email"))); ?>
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <?php echo Form::password("pass", array("class"=>"form-control", "placeholder"=>trans("auth::form.login.password"))); ?>
                    </div>
                    <div class="input-group">
                      <div class="checkbox">
                        <label>
                            <?php echo Form::checkbox('remember', '1');?> <?php echo trans("auth::form.login.remember");?>
                        </label>
                      </div>
                    </div>
                    <div style="margin-top:10px" class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <?php echo Form::button(trans("auth::form.login.login"), array("class"=>"btn btn-success", "type"=>"submit"));?>
                            <div class="extra_actions"><a href="<?php echo  Url::route('recover');?>"><?php echo trans("auth::form.login.forgot");?></a></div>
                        </div>

                    </div>  
                <?php echo Form::close();?>
            </div>                     
        </div>  
    </div>
</div>