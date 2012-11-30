<?php 
/****************************************************************
* get id from wp_posts
* create arry with id from wp_posts
* delete wp_postmeta where post_id = each_id
* delete wp_term_relationships where object_id = each_id
* delete wp_posts where post_type = 'product'
****************************************************************
*/

global $wpdb;
$products   = get_all_post_type($wpdb);
$table_arr  = array('postmeta','term_relationships','posts');
$out_str = truncate_table($wpdb, $products, $table_arr);

function get_all_post_type($wpdb){
    $table  = $wpdb->prefix."posts";
    $q      = 'SELECT ID as id FROM '.$table.' WHERE post_type = "product"';
    $rows   = $wpdb->get_results($wpdb->prepare($q));
  return $rows;
}

function truncate_table($wpdb, $products, $tables){
  $out_str_query = '';
  for ($i=0;$i<sizeof($tables);$i++)
  {
    if ($tables[$i] == "postmeta")          $key = "post_id";
    if ($tables[$i] == "term_relationships") $key = "object_id";
    if ($tables[$i] == "posts")              $key = "post_type";
    $out_str_query .= delete_now($wpdb, $tables[$i], $products, $key)."<br />";
  }
  return $out_str_query;
}

function delete_now($wpdb, $table, $products, $key){
  if ($table == "posts")
  {
    $q = "DELETE FROM ".$wpdb->prefix.$table." WHERE ".$key." = 'product'";
    $str_q = $q."<br />";
    $wpdb->query($wpdb->prepare($q));
  }
  else
  {
    foreach ($products as $p) 
    {
      $q = "DELETE FROM ".$wpdb->prefix.$table." WHERE ".$key." = ".$p->id;
      $str_q .= $q."<br />";
      $wpdb->query($wpdb->prepare($q));
    }
  }
  return $str_q;
  
}







