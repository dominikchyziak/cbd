<tr>
	<td>
		<?php
                for($i = 0; $i < $category['level']; $i++){
                    echo '-';
                }
                ?>
		<?php echo $category['category']->getTranslation('pl')->name; ?>
	</td>
	<td><a href="<?php echo site_url('duocms/offer_categories/edit/'.$category['category']->id); ?>"><i class="fa fa-pencil"></i></a></td>
	<td><a href="<?php echo site_url('duocms/offer_categories/delete/'.$category['category']->id); ?>"><i class="fa fa-trash" onclick="javascript:return confirm('Ta operacja jest nieodwracalna. Kontyunować?')"></i></a></td>
</tr>