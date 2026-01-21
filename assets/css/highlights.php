
<?php 

$query = "SELECT * FROM indexpage ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {


  $hero_title = $row['hero_title'];
  $hero_title2 = $row['hero_title2'];
  $hero_subtitle = $row['hero_subtitle'];

  $tile1_title = $row['tile1_title'];
  $tile1_subtitle = $row['tile1_subtitle'];
  $tile1_photo1 = $row['tile1_photo1'];
  $tile1_photo2 = $row['tile1_photo2'];
  $tile1_photo3 = $row['tile1_photo3'];

  $tile2_title = $row['tile2_title'];
  $tile2_subtitle = $row['tile2_subtitle'];
  $tile2_photo1 = $row['tile2_photo1'];
  $tile2_photo2 = $row['tile2_photo2'];
  $tile2_photo3 = $row['tile2_photo3'];
  $tile2_photo4 = $row['tile2_photo4'];
  $tile2_photo5 = $row['tile2_photo5'];
  $tile2_photo6 = $row['tile2_photo6'];

  $tile3_title = $row['tile3_title'];
  $tile3_subtitle = $row['tile3_subtitle'];

  $tile4_title = $row['tile4_title'];
  $tile4_subtitle = $row['tile4_subtitle'];

}

?>
<style>

.features .hightlight1 {
  background-image: url("../img/highlights/".$tile1_photo1);
  background-size: cover;
  max-width: 250px;
  max-height: 250px;

  aspect-ratio: 1/1;
}

.features .hightlight2 {
    background-image: url("../img/highlights/".$tile1_photo2);
  background-size: cover;
  max-width: 250px;
  max-height: 250px;
  aspect-ratio: 1/1;
}

.features .hightlight3 {
    background-image: url("../img/highlights/".$tile1_photo3);
  background-size: cover;
  max-width: 250px;
  max-height: 250px;
  aspect-ratio: 1/1;
}
</style>