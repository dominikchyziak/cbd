<h2>Blog</h2>

<p>
    <a href="<?php echo site_url('duocms/news/create'); ?>" class="btn btn-primary">+ Dodaj</a>
</p>

<?php if ($news): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Data opublikowania</th>
                    <th width="85%">Tytuł</th>
                    <?php /*<th>Kategoria</th> */?>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $singleNews): ?>
                    <tr>
                        <td class="whsnw"><?php echo $singleNews->started_at; ?></td>
                        <td><?php echo $singleNews->getTranslation('pl')->title; ?></td>
                        <?php /*<td><?php
                            switch ($singleNews->category_id) {
                                case 1:
                                    echo 'Odbiorcy wycen';
                                    break;
                                case 2:
                                    echo 'Banki';
                                    break;
                                case 3:
                                    echo "Moi klienci";
                                    break;
                                default:
                                    echo 'brak';
                                    break;
                            }
                            ?></td> */?>
                        <td><?php printf(ADMIN_BUTTON_EDIT, site_url('duocms/news/edit/' . $singleNews->id)); ?></td>
                        <td><?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/news/delete/' . $singleNews->id)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <p>Brak wyników.</p>
<?php endif; ?>