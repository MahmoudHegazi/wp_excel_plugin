# wp_excel_plugin

#### advanced Plugin That handle excel/Database/Adavnved search/AJAX 

1. with this plugin no need to use CRUD or add or delete items just upload excel sheet and let it handle 
2. work with WordPress without any errors secured
3. it Use excelreader to make it more powerfull 


### like JSON was invated I invited way to connect PHP excution and JS excution and return complete message

1. some says php exuciton not effect javascript and could not connected I find new way to make JavaScript knows when PHP will run without any ajax or requests

```javascript
<script>

// this is new invition like JSON  it seem cute function but it is not
// it will excuted after the php run thats Magic (python king )

function connect_php() {
jQuery(document).ready(function($){


    setTimeout(function() {
       $("#final_result").css('display', 'block');
    }, 4000); //here 1000 means 1 second

});

}

jQuery(document).ready(function($){
  /*
    $( "#aform" ).submit(

      function($,event){
        event.preventDefault();
      //  connect_php();
      //  $( "#aform" ).submit();

      }
    );
    */
    (function a(x) {
        // The following condition
        // is the base case.
        if ( ! x) {
            return;
        }
        a(--x);
        event.preventDefault();
        $( "#aform" ).submit();
        connect_php();
    })(10);
    
});


// do not neeed this way

</script>

```



```php
// this php connected to javascript Invinted By Python king no ajax no connect Just Adavnaced brain

function my_plugin_insert_data($excel_data_arrays) {

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
   $message_success = 'every thing completed I will apear after end';
   echo '<p style="background:lightblue;font-size:1.2em;padding:30px;width:300px;">'. $message_success .'</p>';
}
```



```javascript 
// unlike what should happend due to the php function not make document ready this will fired after the php function and for loop complete
jQuery(document).ready(function($){


    setTimeout(function() {
       // $("myclicker").css('display', 'block');
       alert('hi');
    }, 10000); //here 1000 means 1 second

});
```
