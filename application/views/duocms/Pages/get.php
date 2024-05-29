<h2>Strony</h2>
<p>
    <a href="<?php echo site_url('duocms/pages/add'); ?>" class="btn btn-primary">Dodaj nową +</a><br>
    
</p>

<?php if ($pages): ?>
<div class="table-responsive">
    <table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Lp.</th>
				<th width="100%">Nazwa</th>
				<th colspan="2"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($pages as $Page): 
                            $tpage = $Page->getTranslation(LANG); ?>
				<tr>
					<td><?php echo $Page->id; ?></td>
					<td><?php echo $tpage->title; ?></td>
					<td><?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/pages/edit/'.$Page->id)); ?></td>
					<?php if(ENVIRONMENT == 'development'): ?>
						<td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/pages/delete/'.$Page->id)); ?></td>
                                        <?php elseif(ENVIRONMENT == 'production'):  $blockedPagesId = array(4); ?>
                                            <td><?php !in_array($Page->id, $blockedPagesId) ? printf(ADMIN_BUTTON_DELETE, site_url('duocms/pages/delete/'.$Page->id)) : ''; ?></td>        
                                        <?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
	
<?php else: ?>
	<p>Brak wyników.</p>
<?php endif; ?>