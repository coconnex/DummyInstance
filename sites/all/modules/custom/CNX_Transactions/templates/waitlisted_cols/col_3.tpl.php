 <div class="right_section_btn ">
     <!-- <button class="Cancel_btn">Remove From Waitlist</button> -->
     <?php $waitingactions = $row->waiting_list_actions;
    // debug($actions,1);
    if(is_array($waitingactions)){
        for($j = 0; $j < count($waitingactions); $j++){
            $waitingaction = $waitingactions[$j];
            echo $waitingaction->control->html.'</br>';
        }
    }
?>
 </div>