<?php 
$permissions_selected = json_decode($data, true);
if(isset($old_input) && is_array($old_input) ){
	$permissions_selected = array();
	foreach ($old_input as $value) {
		$permissions_selected[$value] = $value;
	}
}


//var_dump($old_input);


?>
<div class="permissions_setup">
	<div class="input-group-btn">
		<button class="btn btn-default" type="button" id="add_option"><i class="fa fa-plus"></i></button>
		<?php echo Former::select('permissions_selector')->options($source->getOptions())->raw(); ?>
	</div>
	<?php 
		echo '<ul class="selected">';
		if(is_array($permissions_selected)){
			foreach ($permissions_selected as $key => $value) {
				echo "<li>";
				echo '<button class="btn btn-default remove_selected" type="button" data-id="'.$key.'"><i class="fa fa-times"></i></button>';
				echo Form::checkbox('permissions[]', $key, true);
				echo '<input type="text" class="disabled" disabled value="'.$source->getNameByValue($key).'"></input>';
				echo "</li>";
			}
		}
		echo "</ul>";
	?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		function removeSelected(){
			$('.remove_selected').on("click", function(){
				var val = $(this).attr('data-id');
				$('select[name="permissions_selector"] option[value='+val+']').removeClass('_active')
				$(this).parent('li').remove();
			});			
		}
		$('#add_option').on("click", function(){
			var val = $('select[name="permissions_selector"]').val();
			if($('select[name="permissions_selector"] option[value='+val+']').hasClass('_active'))
				return false;

			var text = $('select[name="permissions_selector"] option[value='+val+']').text();
			$('select[name="permissions_selector"] option[value='+val+']').addClass('_active');

			var html = '';
			html += "<li>";
			html += '<button class="btn btn-default remove_selected" type="button" data-id="'+val+'"><i class="fa fa-times"></i></button>';
			html += '<input checked="checked" name="permissions[]" type="checkbox" value="'+val+'">';
			html += '<input type="text" class="disabled" disabled value="'+text+'"></input>';
			html += "</li>";
			
			$('ul.selected').append(html);
			removeSelected();
		});
		removeSelected();


	});
</script>