<?php
/*
 *
 * The first part handles application logic.
 *
 */

global $db_hostname, $db_port, $db_database, $db_username, $db_password, $latency, $storage_option, $enable_cache, $hd_folder, $cache_server;
include("./config.php");

session_start();
$server   = $_SERVER['SERVER_ADDR'];
$db = open_db_connection($db_hostname, $db_port, $db_database, $db_username, $db_password);

// Simulate latency 
sleep($latency);

if (isset($_POST['username'])) 
{
	// This is a login request
	process_login($db, $_POST['username'], $_POST['password']);
}

if (isset($_GET['logout']))
{
	// This is a logout request
	process_logout();
}

if (isset($_FILES["fileToUpload"]) && isset($_SESSION['username']))
{
	// Check file type before processing
	$file_temp = "/tmp/".basename($_FILES["fileToUpload"]["name"]);
	$p = explode('/', $_FILES["fileToUpload"]['type']);
	$file_type = strtolower($p[1]);
	if(($file_type != "jpg") && ($file_type != "png") && ($file_type != "jpeg") && ($file_type != "gif") )
	{
		// Not an image file, ignore the upload request
	}
	else
	{
		$username = $_SESSION['username'];
		// This is an image upload request, save the file first
		// In config.php, we specify the storage option as "hd"
		$key = save_upload_to_hd($_FILES["fileToUpload"], $hd_folder);

		add_upload_info($db, $username, $key, $_POST['name'], $_POST['writer'], $_POST['age']);

	}
}

function getAuthUser($db, $username, $password)
{
	$sql = "SELECT * FROM `users` WHERE `username`=? LIMIT 1";
	$statement = $db->prepare($sql);
	try {
		$statement->execute(array($username));
	} catch (PDOException $e) {
		var_dump("Error: " . $e->getMessage());
	}
	if(password_verify($password, $statement->fetchAll()[0]['password']))
	{
			return True;
	}
	return false;
}

function process_login($db, $username, $password)
{
//	var_dump(getAuthUser($db, $username, $password));
	if(getAuthUser($db, $username, $password)){
		// Simply write username to session data
		$_SESSION['username'] = $username;
	}
	else {
		var_dump('login falided!');
	}

}

function process_logout()
{
	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) 
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}

	// Finally, destroy the session.
	session_destroy();
}

function save_upload_to_hd($uploadedFile, $folder)
{
    // Rename the target file with a UUID
    $ext = pathinfo($uploadedFile["name"], PATHINFO_EXTENSION);
    $uuid = uniqid();
    $key = $uuid.".".$ext;

    // Copy the upload file to the target file
    $tgtFile  = $folder."/".$key;
    copy($_FILES["fileToUpload"]["tmp_name"], $tgtFile);
    return $key;
}

function open_db_connection($hostname, $port, $database, $username, $password)
{
	// Open a connection to the database
	return new PDO("mysql:host=$hostname;port=$port;dbname=$database;charset=utf8", $username, $password);
}

function add_upload_info($db, $username, $filename, $name, $writer, $age)
{
	// Add a new record to the upload_images table
	$sql = "INSERT INTO upload_images (username, filename, name, writer, age) VALUES (?, ?, ?, ?, ?)";
	$statement = $db->prepare($sql);
	try {
		$statement->execute(array($username, $filename,  $name, $writer, $age));
	} catch (PDOException $e) {
		var_dump("Error: " . $e->getMessage());
	}

}

function retrieve_recent_uploads($db, $count)
{
	// Print a message so that the user knows these records come from the DB.
	echo "Getting latest $count records from database.<br>";

	// Geting the latest records from the upload_images table
	$sql = "SELECT * FROM upload_images ORDER BY timeline DESC LIMIT $count";
	$statement = $db->prepare($sql);
	$statement->execute();
	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function open_memcache_connection($hostname)
{	
	// Open a connection to the memcache server
	$mem = new Memcached();
	$mem->addServer($hostname, 11211);
	return $mem;
}



?>

<?php
/*
 *
 * The second part handles user interface.
 *
 */
echo "<html>";
echo "<head>";
echo "<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
echo "<title>Scalable Web Application</title>";
echo "<script src='demo.js'></script>";
echo "<link href='s.css' rel='stylesheet'>";
echo "</head>";
echo "<body id='background-container'>";
if (isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	// This section is shown when user is login
	echo "<table width=100% border=0>";
	echo "<tr>";
		echo "<td><H1>$server</H1></td>";
		echo "<td align='right'>";
			echo "$username<br>";
			echo "<a href='index.php?logout=yes'>Logout</a>";
		echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "<HR>";

	echo "we assume that you are uploading images files with file extensions such as JPG, JPEG, GIF, PNG.<br>&nbsp;<br>";

    echo "<button onclick='location.href=`insert.php`'>ثبت کتاب</button>";
    echo '<br>';
    echo '<br>';
    echo '<br>';

}
else
{
	// This section is shown when user is not login
	echo "<table width=100% border=0>";
	echo "<tr>";
		echo "<td><H1>$server</H1></td>";
		echo "<td align='right'>";
            echo "<button onclick='location.href=`login.php`'>Login</button>";
			echo "<button onclick='location.href=`register.php`'>SignUp</button>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "<HR>";
}

// Get the most recent N images
if ($enable_cache)
{
	// Attemp to get the cached records for the front page
	$mem = open_memcache_connection($cache_server);
	$images = $mem->get("front_page");
	if (!$images)
	{
		// If there is no such cached record, get it from the database
		$images = retrieve_recent_uploads($db, 10);
		// Then put the record into cache
		$mem->set("front_page", $images, time()+86400);
	}
}
else
{
	// This statement get the last 10 records from the database
	$images = retrieve_recent_uploads($db, 10);
}

$session_id = session_id();

// Display the images
echo "<br>&nbsp;<br>";
echo "<div style='
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    /* gap: 10px; */
    justify-items: center;
'>";
if ($storage_option == "hd")
{
	// Images are on hard disk
	foreach ($images as $image)
	{
        echo "<div>";
		$filename = $image["filename"];
		$url = "uploads/".$filename;
		echo "<img src='$url' width=200px height=150px>&nbsp;&nbsp;";
        echo "<br>";
        echo 'نام کتاب: '.$image["name"];
        echo "<br>";
        echo 'نام نویسنده کتاب: '.$image["writer"];
        echo "<br>";
        echo 'سال چاپ کتاب: '.$image["age"];
        echo "<br>";
        echo 'زمان ثبت کتاب: '.$image["timeline"];
        echo "<br>";
        if (isset($_SESSION['username'])){
		?>
		<button onclick='location.href="delete.php?filename=<?php echo $filename ?>"'>delete</button>
		<?php
        }
        echo "</div>";
	}
}
echo "</div>";


echo "<hr>";
echo "Session ID: ".$session_id;
echo "</body>";
echo "</html>";
?>
