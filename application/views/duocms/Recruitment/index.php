<div class="col-sm-12">
    <h2>Rekrutacja</h2>
    <div class="row">
        <div class="col-sm-12 col-md-7 col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Kandydaci
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>Id</td>
                                    <td>Imie i nazwisko</td>
                                    <td>Telefon</td>
                                    <td>Email</td>
                                    <td>Pliki</td>
                                    <td>Dodany</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($candidates)){
                                    foreach($candidates as $candidate){
                                        ?>
                                <tr>
                                    <td><?= $candidate->id;?></td>
                                    <td><?= $candidate->candidate_name;?></td>
                                    <td><?= $candidate->phone;?></td>
                                    <td><?= $candidate->email;?></td>
                                    <td>
                                        <?php
                                        $links = $recruitment->get_files($candidate);
                                        if(!empty($links)){
                                            foreach ($links as $link){
                                                ?>
                                        <a href="<?= $link;?>" target="_blank"><?= substr($link, strrpos( $link, '/') + 1);?></a> | 
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?= $candidate->created_at;?></td>
                                    <td>
                                        <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/recruitment/delete_candidate/'.$candidate->id)); ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                
                            </tbody>
                        </table>
                        <!--<button class="btn btn-success"> + Dodaj kandydata</button>-->
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-sm-12 col-md-5 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Stanowiska
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>Id</td>
                                    <td>Nazwa</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($positions)){
                                    foreach($positions as $position){
                                        ?>
                                <tr>
                                    <td><?= $position->id;?></td>
                                    <td><?= $position->name;?></td>
                                    <td>
                                        <?php printf(ADMIN_BUTTON_DELETE, site_url('duocms/recruitment/delete_position/'.$position->id)); ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                
                            </tbody>
                        </table>
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_position"> + Dodaj stanowisko</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_position" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nowe stanowisko</h4>
      </div>
        <form method="POST" action="<?= site_url('duocms/recruitment/ajax_add_position');?>" id="add_position_form" class="ajax-form">
            <div class="modal-body">
                <div class="form-group">
                    <p>Nazwa stanowiska</p>
                    <input type="text" name="name" value="" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <input type="submit" class="btn btn-primary" value="Dodaj" />
            </div>
        </form>
    </div>

  </div>
</div>