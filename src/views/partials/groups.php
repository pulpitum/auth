<?php 
if(isset($model->id) ){
	$user = Sentry::findUserById($model->id);
	$groups_selected = $user->getGroups(); 
}else{
	$groups_selected = array();
}

?>
<div class="groups_setup">
	<div class="input-group-btn">
		<button class="btn btn-default" type="button" id="add_group_option"><i class="fa fa-plus"></i></button>';
		<?php echo Former::select('groups_selector')->options($source->getOptions())->raw(); ?>
	</div>
	<?php
		echo '<ul class="groups_selected">';
		if(is_array($old_input) ){
			foreach ($old_input as $group) {
				echo "<li>";
				echo '<button class="btn btn-default remove_groups_selected" type="button" data-id="'.$group.'"><i class="fa fa-times"></i></button>';
				echo Form::checkbox('groups[]', $group, true);
				echo '<input type="text" class="disabled" disabled value="'. $source->getNameByValue($group).'"></input>';
				echo "</li>";
			}
		}else{
			foreach ($groups_selected as $group) {
				echo "<li>";
				echo '<button class="btn btn-default remove_groups_selected" type="button" data-id="'.$group->getId().'"><i class="fa fa-times"></i></button>';
				echo Form::checkbox('groups[]', $group->getId(), true);
				echo '<input type="text" class="disabled" disabled value="'. $group->getName().'"></input>';
				echo "</li>";
			}
		}
		echo "</ul>";
	?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		function removeGroupsSelected(){
			$('.remove_groups_selected').on("click", function(){
				var val = $(this).attr('data-id');
				$('select[name="groups_selector"] option[value='+val+']').removeClass('_active')
				$(this).parent('li').remove();
			});			
		}
		$('#add_group_option').on("click", function(){
			var val = $('select[name="groups_selector"]').val();
			if($('select[name="groups_selector"] option[value='+val+']').hasClass('_active'))
				return false;

			var text = $('select[name="groups_selector"] option[value='+val+']').text();
			$('select[name="groups_selector"] option[value='+val+']').addClass('_active');

			var html = '';
			html += "<li>";
			html += '<button class="btn btn-default remove_groups_selected" type="button" data-id="'+val+'"><i class="fa fa-times"></i></button>';
			html += '<input checked="checked" name="groups[]" type="checkbox" value="'+val+'">';
			html += '<input type="text" class="disabled" disabled value="'+text+'"></input>';
			html += "</li>";
			
			$('ul.groups_selected').append(html);
			removeGroupsSelected();
		});
		removeGroupsSelected();


	});
</script>