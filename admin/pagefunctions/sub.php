<?php 
if (isset($_POST['editmail'])) {
    $querycontent = "";
    $params = [];
    $types = "";
    
    // Build SET clause with proper validation
    if (isset($_POST['emailtitle']) && $_POST['emailtitle'] !== '') {
        if ($querycontent) {
            $querycontent .= ", ";
        }
        $querycontent .= "title = ?";
        $params[] = $_POST['emailtitle'];
        $types .= "s";
    }
    
    if (isset($_POST['emailcontent']) && $_POST['emailcontent'] !== '') {
        if ($querycontent) {
            $querycontent .= ", ";
        }
        $querycontent .= "content = ?";
        $params[] = $_POST['emailcontent'];
        $types .= "s";
    }
    
    // Only proceed if we have fields to update
    if ($querycontent) {
        $query = "UPDATE welcomeemail SET " . $querycontent . " WHERE id = ?";
        $params[] = 1; // The ID
        $types .= "i";
        
        $stmt = mysqli_prepare($db, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            
            if (mysqli_stmt_execute($stmt)) {
                array_push($errors2, "Edit Saved");
                // Replace debug_to_console with actual logging:
                error_log("Welcome email updated successfully");
            } else {
                echo "Error updating record: " . mysqli_error($db);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($db);
        }
    }
}
?>