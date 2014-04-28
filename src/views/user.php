<?php $public = isset($public) ? $public : false; ?>

<div class="row well">
        <div class="col-md-12">
                <div class="panel" style="background-image: url('https://lh6.googleusercontent.com/-5vG8ole8nAI/UYFKqb0Y7YI/AAAAAAAABiA/YQzKopOzN1g/w1600-h900/default_cover_1_c07bbaef481e775be41b71cecbb5cd60.jpg');">
                    <img class="pic img-circle" src="http://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/twDq00QDud4/s120-c/photo.jpg" alt="...">
                    <div class="name"><small><a href="<?php echo url($model->identifier);?>"><?php echo $model->first_name." ".$model->last_name;?></a></small></div>
                    <?php if(!$public) echo '<a href="#" class="btn btn-xs btn-primary pull-right" style="margin:10px;"><span class="glyphicon glyphicon-picture"></span> Change cover</a>';?>
                </div>
     	</div>
</div>