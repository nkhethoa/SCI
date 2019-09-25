 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');
   ?>
        <?php   
        if(!empty($mailList)){
            foreach ($mailList as $list) { ?>
           <tr><td><?php echo $list->lName.' '.$list->fName; ?></td>
             <td><?php echo $list->email; ?></td>
             <td class="text-right"></td>
             <td class="text-right">
                <input type="checkbox" name="checkMail[]" class="checkMail" value="<?php echo $list->email; ?>">
              </td>
           </tr>
  <?php }
  } 
  else{ ?>
  <tr>
    <td colspan="3"><h3><b>NO SUCH CONTACT!</b></h3></td>
  </tr>
<?php
  }?>