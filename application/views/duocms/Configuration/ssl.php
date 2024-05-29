<div class="col-sm-12">
        <h3>Konfiguracja ssl</h3>
        <p>STATUS:
        <?php if($status): ?>
        <span style="color:green"> WŁĄCZONY </span>
        <?php else: ?>
        <span style="color:red"> WYŁĄCZONY</span>
        <?php endif; ?>
        </p>
        
        <div class="row">
            <div class="col-sm-6">
                <a href="<?= site_url('duocms/configuration/enable_ssl');?>" class="btn btn-block btn-success">AKTYWUJ SSL</a>
            </div>
            <div class="col-sm-6">
                <a href="<?= site_url('duocms/configuration/disable_ssl');?>" class="btn btn-block btn-danger">DEZAKTYWUJ SSL</a>
            </div>
        </div>
</div>