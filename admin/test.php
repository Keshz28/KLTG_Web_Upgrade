<?php
include('functions.php');
// debug_to_console("test");
echo $_ENV['DB_USER2'];
echo $_ENV['DB_PASSWORD2'];
echo $_ENV['DB_DATABASE2'];

// $result = file_get_contents("https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts?key=AIzaSyDgtoltxiLF12Tcz4v6wZT48eJW2qt7y5E&fetchImages=true&maxResults=500");
// // Will dump a beauty json :3
// $data = (json_decode($result, true));
// // var_dump ($data);

// foreach ($data['items'] as $x) {
//     //     var_dump($x['title']);
// //     var_dump($x['id']);
//     // echo ($x['content']);
//     // echo strip_tags($text, '<p><strong><a>');
//     $pattern = '/<span[^>]*style\s*=\s*["\'][^"\']*font-size\s*:\s*medium[^"\']*["\'][^>]*>(.*?)<\/span>/is';

//     $matches = [];
//     preg_match_all($pattern,($x['content']), $matches);
    
//     if (isset($matches[1][1])) {
//         echo htmlspecialchars("<item>");

//         echo htmlspecialchars('<guid  isPermaLink="false">');
//         echo htmlspecialchars($x['id']);
//         echo htmlspecialchars("</guid>");

//         echo htmlspecialchars("<title>");
//         echo htmlspecialchars($x['title']);
//         echo htmlspecialchars("</title>");

      

//         echo htmlspecialchars("<link>");
//         echo htmlspecialchars("https://www.kltheguide.com.my/blog-details.php?postid=" . $x['id']);
//         echo htmlspecialchars("</link>");


//         echo htmlspecialchars("<description>");
//         echo $matches[1][1]; // Output the content inside the second matching span
//         echo htmlspecialchars("</description>");

//         echo htmlspecialchars("</item>");

//         echo "</br>";
//     } else {
//         echo htmlspecialchars("<item>");

//         echo htmlspecialchars('<guid  isPermaLink="false">');
//         echo htmlspecialchars($x['id']);
//         echo htmlspecialchars("</guid>");

//         echo htmlspecialchars("<title>");
//         echo htmlspecialchars($x['title']);
//         echo htmlspecialchars("</title>");

      

//         echo htmlspecialchars("<link>");
//         echo htmlspecialchars("https://www.kltheguide.com.my/blog-details.php?postid=" . $x['id']);
//         echo htmlspecialchars("</link>");



//         echo htmlspecialchars("</item>");

//         echo "</br>";

//    }

?>