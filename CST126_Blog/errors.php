<?php
/**
 * Created by PhpStorm.
 * User: ewwil
 * Date: 10/9/2018
 * Time: 4:30 PM
 */
  if (count($errors) > 0) : ?>
    <div class="error">
        <?php foreach ($errors as $error) : ?>
            <p><?php echo $error ?></p>
        <?php endforeach ?>
    </div>
<?php  endif ?>