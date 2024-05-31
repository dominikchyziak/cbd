<div class="header">Partnerzy</div>
<p><a href="<?php echo site_url('duocms/partnerzy/create'); ?>" class="button">+ Dodaj</a></p>

<?php if ($partnerzy): ?>
	<table class="t1">
		<thead>
			<tr>
				<th width="100%">Zdjęcie</th>
				<th colspan="2"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($partnerzy as $partner): ?>
				<tr>
					<td style="padding: 8px">
						<?php if ($partner->image): ?>
							<img src="<?php echo $partner->getUrl('mini'); ?>" alt="" style="display: block; max-width: 200px">
						<?php else: ?>
							brak
						<?php endif; ?>
					</td>
					<td><?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/partnerzy/edit/'.$partner->id)); ?></td>
					<td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/partnerzy/delete/'.$partner->id)); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>Brak wyników.</p>
<?php endif; ?>