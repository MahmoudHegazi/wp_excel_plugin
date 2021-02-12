<?php
/*
Template Name: search

*/
?>
<?php get_header(); ?>

    <!-- Content
    ============================================= -->
    <section id="content">

      <div class="content-wrap">


        <div class="container clearfix">

          <!-- Post Content
          ============================================= -->
          <div class="postcontent nobottommargin clearfix">
            <!-- this will be in another page to controll the data used by admin -->

            <form action="" enctype="multipart/form-data" method="post">
             <input type="file" name="file">
             <input type="submit" name="submit">
            </form>


            </div><!-- #posts end -->

            <!-- Pagination
            ============================================= -->
            <div class="row mb-3">
              <div class="col-12">
                <?php
                    next_posts_link( '&larr; Older' );

                    previous_posts_link( 'Newer &rarr;' );

                ?>
                <!--
                <a href="#" class="btn btn-outline-secondary float-left">

                </a>
                <a href="#" class="btn btn-outline-dark float-right">

                </a> -->
              </div>
            </div>
            <!-- .pager end -->

          </div><!-- .postcontent end -->

          <!-- Sidebar
          ============================================= -->
          <?php get_sidebar(); ?>
<!-- .sidebar end -->

        </div>

      </div>

    </section><!-- #content end -->
<?php echo tyre_search(); ?>
<br /><br />

<?php
//include 'excel_reader.php';     // include the class

// creates an object instance of the class, and read the excel file data



?>


<?php

// function to parse the excel sheet

function handle_sheet($excel_file_path) {

$file_path = $excel_file_path;
$excel = new PhpExcelReader;

$excel->read($file_path);

$row_numbers = $excel->sheets[0]['numRows'];

$sheet_count = count($excel->sheets);


if ($sheet_count == 0) {
   return false;
}

if ($row_numbers == 0) {
   return false;
}

$sheet_rows_arrays = $excel->sheets[0]['cells'];
$error_message = null;
$counter = 0;
$html_message = '<table id="style_table"><tr><th>Make</th><th>Model</th><th>Year</th><th>Width</th><th>Profile</th><th>Rim Size</th><th>Tire Width</th></tr>';
foreach ($sheet_rows_arrays as $row) {
  // this to skip titles to not added to database

  if ($counter == 0) {
    $counter += 1;
    continue;
  }
  if (isset($row)) {

    // the add to database here
    $make = $row[1];
    $model = $row[2];
    $year = $row[3];
    $width = $row[4];
    $profile = $row[5];
    $rim_size = $row[6];
    $tire_width = $row[7];


$html_message .= '<tr>';

    $html_message .= '<td>'.$make.' </td>';
    $html_message .= '<td>'.$model.' </td>';
    $html_message .= '<td>'.$year.' </td>';
    $html_message .= '<td>'.$width.' </td>';
    $html_message .= '<td>'.$profile.' </td>';
    $html_message .= '<td>'.$rim_size.' </td>';
    $html_message .= '<td>'.$tire_width.' </td>';

$html_message .= '</tr>';



  } else {
    $error_message = 'Excel Sheet Not accecpted';

  }

}
$html_message .= '</table>';

echo $html_message;
return $sheet_rows_arrays;
}





// function to handle upload file
function handle_logo_upload($file){

require_once(ABSPATH.'wp-admin/includes/file.php');
$uploadedfile = $file;

$movefile = wp_handle_upload($uploadedfile, array('test_form' => false));

if ( $movefile ){
    //or return
    return $movefile['file'];
  }

}
if (isset($_POST['submit'])) {
   $sheet_path = handle_logo_upload($_FILES['file']);
   $returned_sheet = handle_sheet($sheet_path);

   // here call the database function
   if (isset($returned_sheet)){
      echo 'db command can excute';

    // start of db function


////////////////////////////////////////////////////


function my_plugin_insert_data($excel_data_arrays) {

// insert
$counter = 0;

global $wpdb;
$tablename=$wpdb->base_prefix . "cli_cars";

  foreach ($excel_data_arrays as $row) {
    // this to skip titles to not added to database

    if ($counter == 0) {
      $counter += 1;
      continue;
    }
    if (isset($row)) {

      // the add to database here
      $make = $row[1];
      $model = $row[2];
      $year = $row[3];
      $width = $row[4];
      $profile = $row[5];
      $rim_size = $row[6];
      $tire_width = $row[7];

      $data=array(
          'make' => $make,
          'model' => $model,
          'year' => $year,
          'width' => $width,
          'profile' => $profile,
          'rim_size' => $rim_size,
          'tire_width' => $tire_width
        );


       $wpdb->insert( $tablename, $data);


    } else {
      $error_message = 'Excel Sheet Not accecpted';

    }

  }

}


//////////////////////////////////////////////////

//namespace WP_CLI_Login;


function my_plugin_delete_database() {
     global $wpdb;
     $table_name = $wpdb->base_prefix . "cli_cars";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}

register_deactivation_hook( __FILE__, 'my_plugin_delete_database' );

function upgrade_200($wpdb) {
    global $wpdb;
    my_plugin_delete_database();
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE `{$wpdb->base_prefix}cli_cars` (
      id int(11) NOT NULL AUTO_INCREMENT,
      make varchar(255) NOT NULL,
      model varchar(255) NOT NULL,
      year varchar(255) NOT NULL,
      width varchar(255) NOT NULL,
      profile varchar(255) NOT NULL,
      rim_size varchar(255) NOT NULL,
      tire_width varchar(255) NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
    $success = empty($wpdb->last_error);

    return $success;
}

function upgrade() {
    $saved_version = (int) get_site_option('wp_cli_login_db_version');

    if ($saved_version < 200 && upgrade_200()) {
        update_site_option('wp_cli_login_db_version', 200);
    }
}


upgrade_200($wpdb);
my_plugin_insert_data($returned_sheet);









      // end of db function
   }
}

echo '<br /><br />';
// end


//handle_sheet();
/*
$make =
$model =
$year =
$width =
$profile =
$rim_size =
$tire_width =
*/






?>


<script>


</script>


<?php get_footer(); ?>
