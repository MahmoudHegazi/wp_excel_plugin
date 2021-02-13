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

    </section><!-- #content end  handle it in ajax if value == '' or here in jquery -->



<?php
// function to get all  years

function get_all_years() {
function create_year_options() {
global $wpdb;
$tablename=$wpdb->base_prefix . "cli_cars";
$years = $wpdb->get_results("SELECT id, year FROM $tablename");
return $years;
}

$all_years = create_year_options();
$html_message = '<option value="">Year</option>';
foreach ($all_years as $value) {
  $html_message .= '<option value="' . $value->year .'">' . $value->year . '</option>';
}
return $html_message;
}
?>

<select id="myyear" class="year-value">
  <?php echo get_all_years(); ?>
</select>

<select id="mymakes" class="make-value">
  <option value="">Make</option>
</select>

<select id="mymodels" class="model-value">
  <option value="">Model</option>
</select>

<p id="tire_width" style="background:lightblue;padding:30px;width:150px;font-size:1.2em;font-family:lemon;display:none"></p>
    <script>

     // get dynamic makes ajax if you not advanced developer do not edit this
     jQuery(".year-value").on("change", function(){
       $('#mymakes').html('<option value="">Make</option>');
       $('#mymodels').html('<option value="">Model</option>');
       $("#tire_width").css("display","none");
       var year = jQuery(this).val();
      //var year = jQuery(this).attr("data-year");
      //var make = jQuery(this).attr("data-make");
      //var model = jQuery(this).attr("data-model");
      jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {action: "my_author_box", year: year}, function(response){
       console.log("Response: " + response);
       var static_option1 = '<option value="">Make</option>';
       var full_result1 = static_option1 + response;
       $('#mymakes').html(full_result1);
       //NOTE that 'action' MUST be the same as PHP function name you want to fire
       //you can do whatever you want here with your response
      });
     })

     // get dynamic models ajax if you not advanced developer do not edit this
     jQuery(".make-value").on("change", function(){
       $('#mymodels').html('<option value="">Model</option>');
       $("#tire_width").css("display","none");
       var year = $('#myyear').val();
       var make = jQuery(this).val();
      //var year = jQuery(this).attr("data-year");
      //var make = jQuery(this).attr("data-make");
      //var model = jQuery(this).attr("data-model");
      jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {action: "my_dynamic_models", year:year, make: make}, function(response){
       console.log("Response: " + response);
       var static_option2 = '<option value="">Model</option>';
       var full_result2 = static_option2 + response;
       $('#mymodels').html(full_result2);
       //NOTE that 'action' MUST be the same as PHP function name you want to fire
       //you can do whatever you want here with your response
      });
     })


     // get result size with advanced way and better UX no need to click any thing
     jQuery(".model-value").on("change", function(){
       $("#tire_width").css("display","block");
       var year = $('#myyear').val();
       var make = $('#mymakes').val();
       var model = jQuery(this).val();
      //var year = jQuery(this).attr("data-year");
      //var make = jQuery(this).attr("data-make");
      //var model = jQuery(this).attr("data-model");
      jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {action: "my_tire_width", year:year, make: make, model:model}, function(response){
       console.log("Response: " + response);
       $('#tire_width').text(response);
       //NOTE that 'action' MUST be the same as PHP function name you want to fire
       //you can do whatever you want here with your response
      });
     })
    </script>

<?php echo tyre_search(); ?>
<br /><br />

<?php
//include 'excel_reader.php';     // include the class

// creates an object instance of the class, and read the excel file data



?>



<?php





// function to get makes list

function get_makes_for_year($year) {
function get_makes_by_year($year) {
global $wpdb;
$tablename=$wpdb->base_prefix . "cli_cars";
$makes = $wpdb->get_results("SELECT id, make FROM $tablename WHERE year = '$year'");
return $makes;
}

$all_makes = get_makes_by_year($year);
$html_message = '';
foreach ($all_makes as $value) {
  $html_message .= '<option>' . $value->make . '</option>';
}
return $html_message;
}

echo get_makes_for_year('2002');


////////////////////////////
// function get models for make and year

function get_models_for_make_year($year, $make) {
function get_models_by_makeyear($year, $make) {
global $wpdb;
$tablename=$wpdb->base_prefix . "cli_cars";
$models = $wpdb->get_results("SELECT id, model FROM $tablename WHERE year = '$year' AND make='$make'");
return $models;
}

$all_models = get_models_by_makeyear($year, $make);
$html_message = '';
foreach ($all_models as $value) {
  $html_message .= '<option>' . $value->model . '</option>';
}
return $html_message;
}

echo '<br /><br /> Models: <br /><br />';
echo get_models_for_make_year('2002','Subaru');



//////////////////////////// finall result
// function get tire_size for make and year and model


function get_size_for_make_year_mdoel($year, $make, $model) {
global $wpdb;
$tablename=$wpdb->base_prefix . "cli_cars";
$x = $wpdb->get_results("SELECT id, tire_width FROM $tablename WHERE year = '$year' AND make='$make' AND model='$model'");
return $x[0]->tire_width;
}

echo '<br /><br /> Size: <br /><br />';
echo get_size_for_make_year_mdoel('1989','Mazda','626');


   // query part
   // get makes by year
/*
   function get_makes_by_year($year){
   $makes = $wpdb->get_results("SELECT id, make FROM $wpdb->cli_cars WHERE year = '$year'
   ORDER BY make DESC LIMIT 0,4");


foreach ($makes as $key => $value) {
  echo $key[$value];
}

 }
 get_makes_by_year('2002');

 */
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

    // check start

    global $wpdb;

    $make = $row[1];
    $model = $row[2];
    $year = $row[3];
    $width = $row[4];
    $profile = $row[5];
    $rim_size = $row[6];
    $tire_width = $row[7];

    $checkIfExists = $wpdb->get_var("SELECT id, tire_width FROM $tablename WHERE year = '$year' AND make='$make' AND model='$model'");
    // check end
    if (isset($row) && $checkIfExists == NULL) {

      // the add to database here


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

//my_plugin_delete_database();
//upgrade_200($wpdb);
//my_plugin_insert_data($returned_sheet);









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
