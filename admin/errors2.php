<?php
/* ============================================================================
 *                               errors2.php
 *
 *   Partial include — alternate error-alert renderer (variant of errors.php).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ if (count($errors2) > 0): ?>
  <?php foreach ($errors2 as $error2): ?>

    <script>toastupdate("<?php echo $error2?>");</script>
    <script>console.log("<?php echo $error2?>");</script>

  <?php endforeach ?>

<?php endif ?>