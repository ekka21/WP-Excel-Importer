<form method="post" action="/wp-admin/admin.php?page=obimport">
  <input type="hidden" name="action" value="import_now" id="action">
  <input type="hidden" name="file_name" value="<?php print $file_name; ?>" id="file_name">
	<div class="wrap">
      <div class="icon32 icon32-posts-page" id="icon-edit-pages"><br></div>
            <h2>Import new seed products</h2>
            <div class="error">
              <p>
              <span style="color:red;">-- WANING --</span> <br />By clicking "Continue", <span style="color:red;">you will LOSE all of the old data and replace with the new one.</span>
              </p>
            </div> 
            
          <p>
           There are <span style="color:green;"><?php print $data->rowcount('0'); ?></span> new rows to import.
           </p>
      <p>
        <a href="/wp-content/plugins/ob-import/export" class="add-new-h2">Export</a>
      </p>
      <p>
              <!-- See it in action 
                            <select name="debug" id="debug">
                              <option value=""></option>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select> -->
              <input type="submit" name="next" value="Continue &rarr;" id="next" style="cursor:pointer;">
      </p>
        <?php print $data->dump(true,true,'0','widefat'); ?>
      <p>
        <input type="submit" value="Continue &rarr;">
      </p>
  </div>
</form>
