<?php
require "header.php";
?>

<main>
    <div class="wrapper-main">
        <section class="section-default">
            <h1>Signup</h1>
            <?php
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case "emptyfields":
                        echo '<p class="signuperror">Fill in all fields!</p>';
                        break;
                    case "invaliduidmail":
                        echo '<p class="signuperror">Invalid username and e-mail!</p>';
                        break;
                    case "invaliduid":
                        echo '<p class="signuperror">Invalid username!</p>';
                        break;
                    case "invalidmail":
                        echo '<p class="signuperror">Invalid e-mail!</p>';
                        break;
                    case "passwordcheck":
                        echo '<p class="signuperror">Your passwords do not match!</p>';
                        break;
                    case "usertaken":
                        echo '<p class="signuperror">Username is already taken!</p>';
                        break;
                }
            } elseif (isset($_GET['signup']) && $_GET['signup'] == "success") {
                echo '<p class="signuperror">Signup successful!</p>';
            }
            ?>
            <form class="form-signup" action="includes/signup.inc.php" method="post">
                <input type="text" name="uid" placeholder="Username" required>
                <input type="text" name="mail" placeholder="E-mail" required>
                <input type="password" name="pwd" placeholder="Password" required>
                <input type="password" name="pwd-repeat" placeholder="Repeat password" required>
                <button type="submit" name="signup-submit">Signup</button>
            </form>
        </section>
    </div>
</main>

<?php
require "footer.php";
?>
