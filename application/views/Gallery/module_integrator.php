<?php $iterator = 0;
    for($i=0; $i<count($photos);$i=$i):
      if(empty($setup[$iterator])):
         $iterator = 0; 
      endif;
      $current_module_id = $setup[$iterator]->module_id;
      $module = $modules[$current_module_id];
      if(!empty($photos[$i+$module->max_number])){
          $this->load->view('Gallery/modules/'.$module->module_name, [
              'mini' => 'mini',
              'photos' => array_slice($photos, $i, $module->max_number)
          ]);
          $i += $module->max_number;
      } else {
          $module = gallery_find_module(count($photos) - $i);
          if(!empty($module)): 
          $this->load->view('Gallery/modules/'.$module->module_name, [
              'mini' => 'mini',
              'photos' => array_slice($photos, $i, $module->max_number)
          ]);
          $i = count($photos)+$module->max_number;
          else:
              $i++;
          endif;
      }
      $iterator++;
    endfor;
    ?>