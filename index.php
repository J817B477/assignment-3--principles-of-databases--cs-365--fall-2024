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

    <!-- edit these to reflect the 'student_passwords' database -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Search</legend>
            Search for Series of Characters in Database:
            </br>
            </br>
            <input type="text" name="search" autofocus required>
            <input type="hidden" name="submitted" value="1">
            <p><input type="submit" value="search"></p>
        </fieldset>
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- sends to the server itself-->
        <fieldset>
            <legend>Update</legend>
            Update Existing Entry:
            </br>
            </br>

            SET
            <select name="current-attribute" id="current-attribute">
                <option>Site Name</option>
                <option>URL</option>
                <option>User Name</option>
                <option>User First Name</option>
                <option>User Last Name</option>
                <option>User Email Address</option>
            </select>
            = <input type="text" name="new-attribute" required>
            WHERE
            <select name="query-attribute" id="query-attribute">
                <option>Site Name</option>
                <option>URL</option>
                <option>User Name</option>
                <option>First Name</option>
                <option>Last Name</option>
                <option>Email Address</option>
                <option>Registration Comment</option>
            </select>
            = <input type="text" name="pattern" required>
            <input type="hidden" name="submitted" value="2">
            <p><input type="submit" value="update"></p>
        </fieldset>
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Insert</legend>
            Insert New Registration
                </br>
                </br>
                VALUES:
                </br>
                </br>
                <input type="text" name="artist-id" placeholder="Site Name" required>

                <p>
                    <input type="text" class="entry-field" placeholder="URL" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="User Name" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="First Name" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="Last Name" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="Email Address" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="Email Address" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="Website Password" required>
                </p>
                <p>
                    <input type="text" class="entry-field" placeholder="Registration Comment" required>
                </p>
            <input type="hidden" name="submitted" value="3">
            <p><input type="submit" value="insert"></p>
        </fieldset>
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Delete</legend>
            Delete Registration Entry:
            <select name="current-attribute" id="current-attribute">
                <option>Site Name</option>
                <option>URL</option>
                <option>User Name</option>
                <option>User First Name</option>
                <option>User Last Name</option>
                <option>User Email Address</option>
            </select>
            = <input type="text" name="pattern" required>
            <input type="hidden" name="submitted" value="4">
            <p><input type="submit" value="delete"></p>
        </fieldset>
    </form>
  </body>
</html>
