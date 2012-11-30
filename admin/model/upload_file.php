<?php
if(isset($_FILES['attatchment']) && ($_FILES['attatchment']['size'] > 0)) 
{
  $upload_arr = wp_upload_dir();
  $file = wp_handle_upload($_FILES['attatchment'] , array('test_form' => FALSE), false);
  $tmp_file  = explode('/',$file['file']);
  $file_name = $upload_arr['path'].'/'.end($tmp_file);
}		
