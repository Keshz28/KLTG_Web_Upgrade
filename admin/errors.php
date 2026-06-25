<?php
/* ============================================================================
 *                                errors.php
 *
 *   Partial include — renders the $errors array as Bootstrap alerts
 *   (used by login/register/forms). No session guard of its own.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ if (count($errors) > 0): ?>
  <?php foreach ($errors as $error): ?>

    <div class="alert alert-danger" role="alert">
    <?php echo $error ?>
    </div>

   
  <?php endforeach ?>

<?php endif ?>