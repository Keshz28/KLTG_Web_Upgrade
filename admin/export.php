<?php
// Include database connection file
include('functions.php');

// Check if the export button is clicked
if(isset($_POST['export'])) {
    // Define the filename for the exported file
    $filename = "exported_data.csv";

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open a file handle for writing
    $output = fopen("php://output", "w");

    // Write the header row for the CSV file
    fputcsv($output, array('ID', 'Email', 'Phone', 'Country', 'State', 'City', 'Submission Date'));

    // Fetch data from database
    // Adjust the SQL query to include records within the selected date range
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $sql = "SELECT id, email, phone, country, state, city, submission_date FROM contact_forms WHERE submission_date BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
    $result = mysqli_query($db, $sql);

    // Check if records exist
    if(mysqli_num_rows($result) > 0) {
        // Loop through each row and write data to CSV file
        while($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
    }

    // Close the database connection
    mysqli_close($db);

    // Close the file handle
    fclose($output);

    // Exit to prevent further output
    exit;
}

// Check if the export button is clicked
// if(isset($_POST['export_subs'])) {

//     // Define the filename for the exported file
//     $start_dt = $_POST['start_date'];
//     $end_dt = $_POST['end_date'];
//     $start_date = explode(' ', $start_dt)[0];
//     $end_date = explode(' ', $end_dt)[0];
    
//     $filename = "emailsubs_{$start_date}_{$end_date}.csv";

//     // Set headers for CSV download
//     header('Content-Type: text/csv');
//     header('Content-Disposition: attachment; filename="' . $filename . '"');

//     // Open a file handle for writing
//     $output = fopen("php://output", "w");

//     // Write the header row for the CSV file
//     fputcsv($output, array('ID', 'Email', 'Country', 'Monthly Subs', 'Date'));

//     // Fetch data from database
//     // Adjust the SQL query to include records within the selected date range
    
//     $sql = "SELECT emailsub_id, emailsub_email, emailsub_country, emailsub_consent, emailsub_date FROM emailsub WHERE emailsub_date BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
//     $result = mysqli_query($db, $sql);

//     // Check if records exist
//     if(mysqli_num_rows($result) > 0) {
//         // Loop through each row and write data to CSV file
//         while($row = mysqli_fetch_assoc($result)) {
//             fputcsv($output, $row);
//         }
//     }

//     // Close the database connection
//     mysqli_close($db);

//     // Close the file handle
//     fclose($output);

//     // Exit to prevent further output
//     exit;
// }
?>
