<div class="container">
    <div class="col-sm-12 title">
        <?php $this->load->view('partials/breadcrumbs'); ?>
        <h1><?= (new CustomElementModel('9'))->getField('Agenci'); ?></h1>
    </div>
</div>

<style>
    .nav-pills li.active a {
        background-color: #e60f7e !important;
        color: #ffffff;
    }
</style>

<section class="row">
    <div class="container">
        <p><br><br>
            <?= (new CustomElementModel('9'))->getField('Wybierz litere'); ?>
        </p>
        <div id="exTab2" class="container">
        <?php
        $city = null;
        $name = null;
        $address = null;
        $tel = null;
        $email = null;
        $char;
        $cityagents = [];
        if(!empty($all_agents)){
            echo '<ul class="nav nav-tabs" style="margin-bottom:20px;">';
            $counti = 1;
            foreach($all_agents as $agent){
                if(empty($char) || $char != mb_substr($agent->city, 0,1,'utf-8')){
                    $char = mb_substr($agent->city, 0,1,'utf-8');
                    echo '<li class="' . (($counti == 1) ? 'active' : '') . '">
                        <a  href="#' . $char . '_char" data-toggle="tab"><strong>' . $char . '</strong></a>
                    </li>';
                }
                $counti++;
            }
            echo '</ul>';

            foreach($all_agents as $agent){
                if($char != mb_substr($agent->city, 0,1,'utf-8')){
                    $char = mb_substr($agent->city, 0,1,'utf-8');

                }
                if($city != $agent->city){
                    $city = $agent->city;
                    //$cities++;
                    echo '';
                }
                $cityagents[$char][$city][] = [$agent->id, $agent->city, $agent->name, $agent->address, $agent->tel, $agent->email];
                $counti++;
            }
            echo '<div class="tab-content ">';
            $counti = 1;
            foreach($cityagents as $key=>$chars){
                echo '<div class="tab-pane ' . (($counti == 1) ? 'active' : '') . '" id="' . $key . '_char">';
                $counti++;
                $countj = 1;
                echo '<ul class="nav nav-pills nav-stacked col-md-2" role="tablist">';
                foreach($chars as $key1=>$cities){
                    echo '<li class="' . (($countj == 1) ? 'active' : '') . '"><a href="#'. str_replace(" ","",$key1) .'_city" role="tab" data-toggle="tab">'. $key1 .'</a></li>';
                    $countj++;
                }
                echo '</ul>';
                echo '<div class="tab-content col-md-10">';
                $countk = 1;
                foreach ($chars as $key1=>$cities){
                    echo '<div class="tab-pane ' . (($countk == 1) ? 'active' : '') . '" id="'.str_replace(" ","",$key1).'_city">';
                    $countk++;
                    ?>
                        <table class="table table-striped">
                        <tr>
                            <th><?= (new CustomElementModel('11'))->getField('Miasto'); ?></th>
                            <th><?= (new CustomElementModel('11'))->getField('Nazwa'); ?></th>
                            <th><?= (new CustomElementModel('11'))->getField('Adres'); ?></th>
                            <th><?= (new CustomElementModel('11'))->getField('Telefon'); ?></th>
                            <th><?= (new CustomElementModel('11'))->getField('Email'); ?></th>
                        </tr>
                    <?php
                    foreach ($cities as $agents1){
                        echo '<tr>';
                        echo '<td style="padding:5px;">' . $agents1[1] . '</td>';
                        echo '<td style="padding:5px;">' . $agents1[2] . '</td>';
                        echo '<td style="padding:5px;">' . $agents1[3] . '</td>';
                        echo '<td style="padding:5px;">' . $agents1[4] . '</td>';
                        echo '<td style="padding:5px;">' . $agents1[5] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
        </div>
        <hr>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>