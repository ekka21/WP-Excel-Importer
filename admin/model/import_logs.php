<?php 
global $wpdb;
 $table  = $wpdb->prefix."ob_import_logs";
 $q      = 'SELECT * from '.$table.' ORDER BY date_add DESC';
 $logs   = $wpdb->get_results($wpdb->prepare($q));
?>
<div class="wrap">
    <div class="icon32 icon32-posts-page" id="icon-edit-pages"><br></div>
          <h2>Import Logs</h2>
          <table border="0" cellspacing="5" cellpadding="5" class="widefat">
            <tr><th></th><th>File Name</th><th>Date</th></tr>
            <?php foreach($logs as $row): ?>
            <tr>
              <td width="30px"><?php print $row->id; ?></td>
              <td><a href="<?php print bloginfo('url').'/assets/'.$row->file_name; ?>" target="_blank"><?php print $row->file_name; ?></td>
              <td><?php print $row->date_add; ?></td>
            </tr>
            <?php endforeach; ?>
          </table>
</div>