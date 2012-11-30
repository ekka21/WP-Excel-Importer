<script type="text/javascript" charset="utf-8">
  jQuery.noConflict();  
  jQuery(document).ready(function() {
  
  });
</script>
<form method="post" action="/wp-admin/admin.php?page=obimport" enctype="multipart/form-data">
  <input type="hidden" name="action" value="view_data" id="action">
	<div class="wrap">
	   <h2>Import new seed products</h2>
      <div class="error">
        <p>
          Please use .xls file extension only.
        </p>
      </div>

      <p>
        File: <input type="file" id="attatchment" name="attatchment">
        <input type="submit" value="Next &rarr;">
      </p>
  </div>
</form>
