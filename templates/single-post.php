<?php
    //security
    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo esc_html($title); ?></title>
    </head>

    <body>
        <h1>Single Pet</h1>
        <p>This is a paragraph</p>
        <p>This is another paragraph</p>

        <?php
            // This is the data that was passed to the template
            echo var_dump($data);
        ?>
    </body>

</html>