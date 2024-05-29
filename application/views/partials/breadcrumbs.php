<ol class="breadcrumb text-left">
    <?php foreach ($this->breadcrumbs as $breadcrumb): ?>
        <li class="breadcrumb-item">
            <?php echo str_replace("<br>", " ", $breadcrumb); ?>
        </li>
    <?php endforeach; ?>
</ol>