<?php if (count($errors2) > 0): ?>
  <?php foreach ($errors2 as $error2): ?>

    <script>toastupdate("<?php echo $error2?>");</script>
    <script>console.log("<?php echo $error2?>");</script>

  <?php endforeach ?>

<?php endif ?>