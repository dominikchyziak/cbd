<div class="col-sm-12">
    <h2>Wizerunki</h2>
    <p><a href="<?php echo site_url('duocms/wizerunek/create'); ?>" class="btn btn-primary">+ Dodaj</a></p>

    <?php if ($wizerunki): ?>
            <table class="table table-striped table-hover">
                    <thead>
                            <tr>
                                    <th width="100%">Zdjęcie</th>
                                    <th colspan="2"></th>
                            </tr>
                    </thead>
                    <tbody>
                            <?php foreach ($wizerunki as $wizerunek): ?>
                                    <tr>
                                            <td style="padding: 8px">
                                                    <?php if ($wizerunek->image): ?>
                                                            <img src="<?php echo $wizerunek->getUrl('mini'); ?>" alt="" style="display: block; max-width: 200px">
                                                    <?php else: ?>
                                                            brak
                                                    <?php endif; ?>
                                            </td>
                                            <td><?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/wizerunek/edit/'.$wizerunek->id)); ?></td>
                                            <td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/wizerunek/delete/'.$wizerunek->id)); ?></td>
                                    </tr>
                            <?php endforeach; ?>
                    </tbody>
            </table>
    <?php else: ?>
            <p>Brak wyników.</p>
    <?php endif; ?>
</div>
