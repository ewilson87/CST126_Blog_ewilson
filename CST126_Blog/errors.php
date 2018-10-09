<?php
/**
 * CST-126 Blog project
 * errors.php version 1.0
 * Program Author: Evan Wilson
 * Date: 10/9/2018
 * HTML and PHP use in display of errors on registration and login pages
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */

 //if there are any errors, loop through array and echo each one
  if (count($errors) > 0) : ?>
    <div class="error">
        <?php foreach ($errors as $error) : ?>
            <p><?php echo $error ?></p>
        <?php endforeach ?>
    </div>
<?php  endif ?>