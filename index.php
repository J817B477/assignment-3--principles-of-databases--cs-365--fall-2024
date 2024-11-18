<!DOCTYPE html>
<?php
$title = "Student Password Locker";
?>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1><?php echo $title ?></h1>
    </header>
    <form id="clear-results" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input id="clear-results__submit-button" type="submit" value="Clear Results">
    </form>

    <?php
    require_once "includes\config.php";
    require_once "includes\helpers.php";

    define("NOTHING_FOUND",  0);
    define("SEARCH",         1);
    define("UPDATE",         2);
    define("INSERT",         3);
    define("DELETE",         4);

    $option = (isset($_POST['submitted']) ? $_POST['submitted'] : null);

    if ($option != null) {
        switch ($option) {
            case SEARCH:
                if ("" == $_POST['search']) {
                    echo '<div id="error">Search query empty. Please try again.</div>' .
                        "\n";
                } else {
                    if (NOTHING_FOUND === (search($_POST['search']))) {
                        echo '<div id="error">Nothing found.</div>' . "\n";
                    }
                }

                break;

            case UPDATE:
                if ((0 == $_POST['new-attribute']) && ("" == $_POST['pattern'])) {
                    echo '<div id="error">One or both fields were empty, ' .
                        'but both must be filled out. Please try again.</div>' . "\n";
                } else {
                    update($_POST['current-attribute'], $_POST['new-attribute'],
                        $_POST['query-attribute'], $_POST['pattern']);
                }

                break;

            case INSERT:
                if (("" == $_POST['artist-id']) || ("" == $_POST['artist-name'])) {
                    echo '<div id="error">At least one field in your insert request ' .
                        'is empty. Please try again.</div>' . "\n";
                } else {
                    insert($_POST['artist-id'],$_POST['artist-name']);
                }

                break;

            case DELETE:
                if (("" == $_POST['current-attribute']) || ("" == $_POST['pattern'])) {
                echo '<div id="error">At least one field in your delete procedure ' .
                    'is empty. Please try again.</div>' . "\n";
            } else {
                delete($_POST['current-attribute'], $_POST['pattern']);
            }

            break;

        }
    }

    ?>
  </body>
</html>
