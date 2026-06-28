<?php
    session_start();
    include("config.php");

    if(isset($_SESSION["role"]) && $_SESSION["role"] == "STAFF"){
        header('Location: staffPage.php');
        exit();
    }

    $sql = "SELECT videoID, videoName, videoRentalPrice, videoImage
            FROM VideoTape
            WHERE videoStatus='AVAILABLE' AND videoReleaseDate BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
            ORDER BY videoReleaseDate DESC";

    $query = mysqli_query($conn, $sql);        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Reels - Home Page</title>

    <link rel='stylesheet' type='text/css' href='website.css'/>
    <link rel='stylesheet' type='text/css' href='index.css'/>
    <script src ='website.js' defer></script>

</head>

<body>

    <header>
        <div id="titleImg">
            <img src = "./img/WebsiteLogo.png" class = 'logo' alt="Midnight Reels Logo">
        </div>
        <!--Same line as header, navigation to Rent and Status page-->
        <nav class ="navigation">
                <button class ="navButton" onclick="location.href = 'rental.php'" alt="Rental Button" title="Go to Rental Page">
                    Rental
                </button>
                
                <button class = 'navButton' onclick="location.href = 'rentalStatus.php'" alt="Rental Status Button" title="Go to Rental Status Page">
                    Rental Status
                </button>
            
                <a href="profile.php" class="profile_button">
                    <img src = './img/HomeProfile.png' class = 'logo' alt="Profile Button" title="Go to Profile">
                </a>
        </nav>
    </header>



<main>
    <section class = 'introduction'>
        <div class = 'text'>
            <h1 style="color:rgb(228, 119, 89)">Midnight Reels: Rent and Chill</h1>
            <h2>
                Welcome to the Midnight Reels videotape rental page! You can now rent tapes from the website and pick them up at a later date from our physical store! We have a variety of tapes of different genre from Thriller, Documentary to Comedy.
            </h2>
        </div>
        
        <div class = 'intrologo'>
            <img src = './img/IntroLogo.png' class = 'introductionlogo' alt="Introductio Logo">
        </div>

    </section>

    <section id="newRelease">
        <div class="sectionTitle">
        <h2 style="color:white; margin: 10px;">
            NEW RELEASES!
        </h2>
        </div>
        <div class="newVideoList">
            <?php
                if($query->num_rows > 0){
                    while($row = mysqli_fetch_array($query)){
                        echo "<div class=video>";
                        echo "<a class='imageLink' href='videoTape.php?id=$row[videoID]'><img class='videoTape' src='$row[videoImage]' alt='$row[videoName]' title='$row[videoName]'></a>";
                        echo "<div class='videoName'><h3>".$row['videoName']."</h3></div>";
                        echo "<div class='price'><h4>RM ".$row['videoRentalPrice']."/day</h4></div>";
                        echo "<a class='viewButtonLink' href='videoTape.php?id=$row[videoID]'><div class='viewButton'>View Details</div></a>";
                        echo "</div>";
                    }
                }
                else{
                    echo "<div id='noVideoError'><p>No tapes found.</p></div>";
                }
            ?>
        </div>
    </section>
</main>

<footer class = 'footer'>
        <div class = 'footer-item footer-one'>
            Contact Us!
            <br>067-6767676
            <br>midnightreels@gmail.com
        </div>

        <div class = 'footer-item footer-two'>

        </div>

        <div class = 'footer-item footer-three'>
            7th Street, 9/5
            <br>Manus Quarter,
            <br>New Eridu.
        </div>
</footer>

    <div id="overlay">
        <div id="errorBox" class="errorBox">
            <div id="errorText"></div>
            <div class="errorBoxButton">
                <button id="closeButton" onclick="closeError()">Close</button>
                <button id="confirmButton" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div> 

</body>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        reloadOnBack()});
    </script> 

</html>

<?php
if(isset($_SESSION["success"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["success"]); ?>");
            });
        </script>
    <?php
        unset($_SESSION["success"]);    
    }
if(isset($_SESSION["error"])){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showError("<?php echo addslashes($_SESSION["error"]); ?>");
            });
        </script>
    <?php
        unset($_SESSION["error"]);    
    }
?>