<?php if ($field->type == 'text'): ?>
<p><strong><?php echo $field->title; ?> </strong></p>
	<?php echo form_input('fields['.$field->id.']', $field->value ," class='form-control'"); ?>
<?php endif; ?>

<?php if ($field->type == 'textarea'): ?>
	<p><strong><?php echo $field->title; ?></strong></p>
	<?php echo form_textarea('fields['.$field->id.']', $field->value," class='form-control'"); ?>
<?php endif; ?>

<?php if ($field->type == 'ckeditor'): ?>
	<p><strong><?php echo $field->title; ?></strong> </p>
	<?php echo form_textarea('fields['.$field->id.']', $field->value, 'class="ckeditor"'); ?>
<?php endif; ?>

<?php if ($field->type == 'image'): ?>
	<p><strong><?php echo $field->title; ?></strong></p>
	<?php echo form_input('fields['.$field->id.']', $field->value, 'onclick="openKCFinderImage(this, \'images\')"  class="form-control" readonly'); ?>
<?php endif; ?>

<?php if ($field->type == 'file'): ?>
	<p><strong><?php echo $field->title; ?></strong></p>  
	<?php echo form_input('fields['.$field->id.']', $field->value, 'onclick="openKCFinderImage(this, \'files\')" class="form-control"  readonly'); ?>
<?php endif; ?>
<?php if(ENVIRONMENT == 'development') { ?>
        <a href="<?= base_url('duocms/custom_elements/delete_element/'. $field->id."/".$element->id);?>">[usuń]</a>
        kod:<input value="&lt;?= (new CustomElementModel('<?= $element->id;?>'))->getField('<?= $field->title;?>'); ?>" class="form-control"> 
<?php } ?>
        <hr>

<script>
	function openKCFinderImage(field, type) {
		var self = $(this);

		window.KCFinder = {
			callBack: function (url) {
				field.value = url;
				window.KCFinder = null;
			}
		};

		window.open('<?php echo assets('duocms/ckeditor/kcfinder/browse.php?type='); ?>' + type, 'kcfinder_textbox', 'status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0, width=800, height=600');
	}
</script>
