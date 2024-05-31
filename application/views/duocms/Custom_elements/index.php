<h2>Inne elementy</h2>
<?php 
if(ENVIRONMENT == 'development'){ 
    ?>
    <p>
        <a href="<?= base_url('duocms/custom_elements/add_category');?>" class="btn btn-primary">+ Dodaj kategorię</a>
    </p>
<?php } ?>
<?php if ($custom_elements): ?>
<div class="table-responsive">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
                            <th>ID</th>
                            <th></th>
                            <th width="90%">Nazwa</th>
                            <th>Zarządzaj elementami</th>
                            <th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($custom_elements as $element):
                            if(in_array($element->id, [18,20,21,22])){ continue;} ?>
				<tr>
                                    <td><?= $element->id;?></td>
                                    <td><a href="<?php echo site_url('duocms/custom_elements/edit_category/'.$element->id); ?>"><i class="fa fa-pencil"></i></a></td>
					<td><?php echo $element->name; ?></td>
					<td><a href="<?php echo site_url('duocms/custom_elements/edit/'.$element->id); ?>"><i class="fa fa-pencil"></i></a></td>
                                        <td><!--<?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/custom_elements/delete_category/'.$element->id)); ?>--></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
	<p>Brak wyników.</p>
<?php endif; ?>