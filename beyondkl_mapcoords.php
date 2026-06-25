<?php
/**
 * Beyond KL — exact map coordinates lookup (title => "lat,lng").
 *
 * GENERATED FILE. The Beyond KL location links are goo.gl/maps short links
 * that resolve to exact coordinates; resolving them per page load would mean
 * dozens of network calls, so they are baked here once. "View on Map" buttons
 * (see viewOnMapButton in beyondkl.php) pin by these coords for an exact marker.
 *
 * To regenerate after adding/editing Beyond KL places, re-run the resolver
 * (see git history / the resolve loop over beyondkl_i/hs/w/h/es locationurl).
 * Keyed by the trimmed, urldecoded title. Unknown titles fall back to a
 * title search in viewOnMapButton.
 */
return [
  'Broga Hill, Semenyih' => '2.9482279,101.9016484',
  'Bukit Batu Pahat' => '3.5594444,101.6783333',
  'Bukit Batu Putih' => '2.4112771,101.8490798',
  'Bukit Kutu' => '3.5430556,101.7175',
  'Bukit Tinggi, Pahang' => '3.400997,101.846822',
  'Cameron Highland, Pahang' => '4.4721201,101.3801441',
  'Fraser Hill, Pahang' => '3.711868,101.7365558',
  'Genting Highlands, Pahang' => '3.423978,101.7932011',
  'Jeram Toi, Negeri Sembilan' => '2.8644138,102.0141184',
  'Jugra Hill' => '2.8333333,101.4166667',
  'KKB Paragliding Park' => '3.5442849,101.6752429',
  'Lang Tengah Island' => '5.796016,102.8960712',
  'Lata Medang' => '3.540237,101.7559781',
  'Mantanani Island' => '6.7166667,116.3333333',
  'Manukan Island' => '5.9758774,116.0007493',
  'Maxwell Hill, Perak' => '4.8623,100.793',
  'Mossy Forest, Cameron Highlands' => '4.5243623,101.3818728',
  'Mount Pulai, Johor' => '1.6016667,103.5461111',
  'Pangkor Island' => '4.2274912,100.5577407',
  'Panorama Hill, Sungai Lembing' => '3.9176995,103.0418105',
  'Penang Hill, Penang' => '5.4084613,100.2773317',
  'Penang National Park, Penang' => '5.4471423,100.1939923',
  'Pulau Besar' => '2.43803,103.9811184',
  'Pulau Langkawi' => '6.35,99.8',
  'Pulau Perhentian' => '5.909231,102.7479949',
  'Pulau Redang' => '5.7844414,103.0068926',
  'Puncak Rajawali' => '2.8333333,101.4166667',
  'Rawa Island' => '2.5204381,103.9760408',
  'Serendah Waterfall' => '3.3664894,101.63777',
  'Seven Wells Waterfall, Kedah' => '6.3830867,99.6741164',
  'Sipadan Island' => '4.1149742,118.6286669',
  'Sungai Chiling' => '3.5926247,101.7347412',
  'Sungai Lembing, Pahang' => '3.914618,103.0327402',
  'Sungai Pisang Waterfall, Batu Caves' => '3.3062369,101.7351596',
  'Tengah Island' => '2.476944,103.960278',
  'Ulu Chepor, Perak' => '4.7004357,101.07731',
  'Whitewater Rafting Kuala Kubu Bharu' => '3.5719401,101.6950891',
];
