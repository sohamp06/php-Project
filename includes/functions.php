<?php
     function write_to_log($activity, $status, $email_address){
        $today = date("Ymd");
        $now = date("Y-m-d G-i-s");
        $handle =fopen("./logs/".$today." _log.txt", 'a');

        fwrite($handle, $activity ." " .$status." at " .$now. ". User " .$email_address." ".$activity.".\n");

        fclose($handle);
      }

     function display_form($arrayForm){
   
      echo '<form class="form-signin" action="'.$_SERVER['PHP_SELF'].'" method="POST">';
      echo'<h1 class="h3 mb-3 font-weight-normal">Please Enter the following</h1>';

      foreach($arrayForm as $element){
         if($element['type']=='text'|| $element['type']=="email"||
         $element['type']== "password"||$element['type']== "phone" ){
            
            echo'<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';
            echo'<input value = "'.$element['value'].'"type="'.$element['type'].'" name="'.$element['name'].'" id="'.$element['name'].'" class="form-control" placeholder="'.$element['label'].'" autofocus>';
            
         }

         elseif($element['type'] == "select" ){
            echo '<label for="'.$element['name'].'">'.$element['label'].'</label>';
            echo ' <select name="'.$element['name'].'" id="'.$element['name'].'">';
                foreach ($element['options'] as $value => $label) : ?>
                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                <?php
                endforeach; 
            echo '</select>';
           
        }
         elseif
            ($element['type']=="submit"|| $element['type']=="reset"){
            echo'<button class="btn btn-lg btn-primary btn-block" type="'.$element['type'].'">'.$element['label'].'</button>';
            }
         }
         
      echo'</form>';
      }
      ?>
      <?php
   //lab3
   function display_table($fields, $records, $count, $page){
      echo'<div>';
      echo'<table class="table table-striped">';
      echo'<thead>';
      echo'<tr>';
      foreach ($fields as $key => $value) {
         echo '<th scope="col">' . $value . '</th>';
      }
      echo'<tr>';
      echo'</thead>';
      echo'<tbody>';

      for ($i = 0; $i < count($records); $i++) {
         echo'<tr>';
         foreach($records[$i] as $key1 => $value1){
            echo '<td>'.$value1.'</td>';
         }
         
      echo'</tr>';
      }
      
      echo'</tbody>';
      echo'</table>';


      echo '<nav aria-label="Page navigation example">';
   echo '<ul class="pagination">';
      echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.(($page>1)?$page-1:$page).'">Previous</a></li>';

      for($i=0;$i<$count/RECORDS;$i++){
      echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($i+1).'">'.($i+1).'</a></li>';}
      echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.(($page<$count/RECORDS)?$page+1:$page).'">Next</a></li>';

         echo'</ul>';
      echo'</nav>';
      echo'</div>';
   }

?>  