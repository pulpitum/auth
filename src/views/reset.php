<div class="container">    
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title"><?php echo trans("auth::form.reset.title");?></div>
            </div>     
            <div style="padding-top:30px" class="panel-body" >
                <?php echo Form::open(array('method' => 'post'), array("class"=>"form-horizontal") );?>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <?php echo Form::password("pass", array("class"=>"form-control", "placeholder"=>trans("auth::form.reset.pass"))); ?>
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <?php echo Form::password("pass_confirmation", array("class"=>"form-control", "placeholder"=>trans("auth::form.reset.pass_confirmation"))); ?>
                    </div>  
                    <?php echo Form::hidden('code', Input::get('code'));?>                  
                    <div style="margin-top:10px" class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <?php echo Form::button(trans("auth::form.reset.save"), array("class"=>"btn btn-success", "type"=>"submit"));?>
                        </div>
                    </div>  
                <?php echo Form::close();?>
            </div>                     
        </div>
    </div>
</div>