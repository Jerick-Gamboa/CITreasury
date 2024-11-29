<?php
class HTML {
	public function __construct($title) {
		echo "<!DOCTYPE html>\n<html>\n<head>\n\t<meta charset=\"utf-8\">\n\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n\t<title>$title</title>\n";
	}

	public function addLink($rel, $href) {
		echo "\t<link rel=\"$rel\" href=\"$href\">\n";
	}

	public function addScript($src, $defer=false) {
		echo "\t<script src=\"$src\" " . ($defer ? 'defer' : '') . "></script>\n";
	}

	public function startBody() {
		echo "</head>\n<body>\n";
	}

	public function endBody() {
		echo "</body>\n</html>\n";
	}
}

function verifyAdminLoggedIn($conn) {
	if (isset($_SESSION['cit-student-id'])) {
	    $sql = "SELECT `type` FROM `accounts` WHERE `student_id` = ?";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("s", $_SESSION['cit-student-id']);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    if ($result->num_rows > 0) {
	        $row = $result->fetch_assoc();
	        $type = $row['type'];
	        if ($type === 'user') { # If account type is user, redirect to user page
	            header("location: ../user/");
	        }
	    } else { # If account is not found, return to login page
	        header("location: ../");
	    }
	} else { # If session is not found, return to login page
	    header("location: ../");
	}
}

function verifyUserLoggedIn($conn) {
	if (isset($_SESSION['cit-student-id'])) {
	    $sql = "SELECT `type` FROM `accounts` WHERE `student_id` = ?";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("s", $_SESSION['cit-student-id']);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    if ($result->num_rows > 0) {
	        $row = $result->fetch_assoc();
	        $type = $row['type'];
	        if ($type === 'admin') { # If account type is admin, redirect to admin page
	            header("location: ../admin/");
	        }
	    } else { # If account is not found, return to login page
	        header("location: ../");
	    }
	} else { # If session is not found, return to login page
	    header("location: ../");
	}
}
?>