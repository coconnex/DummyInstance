 <div class="contract_deta">
     <div class="contract_deta_left w-50">
         <span class="heading">
             <h5><strong><?php echo $row->additional_info->stand_no; ?></strong></h5>
         </span>
         <div>

             <p class="mb-0"> Build Height Limits: <?php echo $row->additional_info->stand_height; ?>m</p>
             <p class="mb-0">Open Sides: <?php echo $row->additional_info->stand_opensides; ?></p>
             <div class="bottom_fixed">
                 <p class="contract_submited_common">Waitlisted at <span class="waitlisted_text"><span class="circle_badge"> <?php echo $row->sequence; ?> </span></span></p>
             </div>
         </div>

     </div>
     <div class="contract_deta_right w-50">
         <span class="mb-0 heading">
             <h5><strong><?php echo $row->additional_info->quantity; ?> M<sup>2</sup></strong></h5>
         </span>
     </div>
 </div>