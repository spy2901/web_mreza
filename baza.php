<?php

    function konekcija(){
        global $konekcija;

        $konekcija = mysqli_connect("localhost", "root", "", "social_network");
        if($konekcija == false){
            die("Greska pri konektovanju na bazu");
        }
    }
    function disconnect(){
        global $konekcija;

        return mysqli_close($konekcija);
    }


    function register($username,$email,$password,$image,$tmp_name){
        global $konekcija;

        /* sprecavam database injection kod username,email  */
        $sredjenUser = mysqli_real_escape_string($konekcija,$username);
        $sredjenEmail = mysqli_real_escape_string($konekcija,$email);
        $sredjenPass = mysqli_real_escape_string($konekcija,$password);

        $hashedpassword = password_hash($sredjenPass,PASSWORD_ARGON2ID);
        //pomeram fajlove u images/ folder
        move_uploaded_file($tmp_name,"images/$image");

        $sqlUpit = "SELECT *FROM users WHERE username = '$sredjenUser'";
        $rezultat = mysqli_query($konekcija, $sqlUpit);
        if ($rezultat == false) {
            die('Doslo je do greske pri registraciji korisnika');
        }
    
        if (mysqli_num_rows($rezultat) != 0) {
            return false;
        }
        $sqlUpit = "INSERT INTO users VALUES(NULL,'$sredjenUser', '$hashedpassword','$sredjenEmail','$image')";
        $rezultat = mysqli_query($konekcija, $sqlUpit);
        if ($rezultat == false) {
            die('Doslo je do greske pri registraciji korisnika');
        }
        header("location:index.php");
        return true;
    }

    /*login funkcija */ 
    function login($username,$password){
        global $konekcija;

        /* sprecavam database injectiom */
        $sredjenUser = mysqli_real_escape_string($konekcija,$username);
        $sredjenPass = mysqli_real_escape_string($konekcija,$password);
        
        // hashujem password sa ARGON21 ALGORITMOM
        $hashedpassword = password_hash($sredjenPass,PASSWORD_ARGON2ID);
        
        // kreiram sql upit gde dovatam sve iz users tabele gde je uslov da je 
        // username = $srednjenUser i password=$hashedpasswor;
        $sql_upit = "select * from users WHERE username = '".$sredjenUser."' and password = '".$hashedpassword."' ";
        
        // izvrsavam sql upis uz pomoc mysqli_query funkcije i cuvam ga u promenljivoj $rezultat
        $rezultat = mysqli_query($konekcija,$sql_upit);
            
        // cekiram da li postoji korisnik sa datim username-om
        if (mysqli_num_rows($rezultat) == 0) {
            // ako ne postoji, izbacujem gresku i prekidam dalji rad

            echo "<script>alert('user not found in database');</script>";
        }
        // proveravam da li je password tacan za korisnika
       
       
            $sql_upit = "select image from users WHERE username = '".$sredjenUser."' and password = '".$hashedpassword."' ";
            $image1 = mysqli_query($konekcija,$sql_upit);
            // saljem preko sesije username kao 'username' i sliku iz baze 
            $_SESSION['img'] = $image1;
            $_SESSION["username"] = $username;
            // prebacujem se na home.php stranicu
            header("location:home.php");
        
            return true;    
        } 
    /* profile picture function added 22.3.2023 */
    // uzima username od sesije kao parametre
     function display_pfp($username){
        global $konekcija;
        $sqlimage = "SELECT * FROM users WHERE username='$username'";
        $imageresult1 = mysqli_query($konekcija,$sqlimage);

        while($row = mysqli_fetch_assoc($imageresult1))
        {       
            echo "<img src='images/".$row['image']."' class='pfp'  style='' alt='slika nije ucitana'>"; 
        }
        return true;
    }

    // Define the create_post() function
    function create_post($username, $param1, $param2) {
        global $konekcija;
        // Sanitize the input data to prevent SQL injection attacks
        $naslov = mysqli_real_escape_string($konekcija, $param1);
        $postdesc = mysqli_real_escape_string($konekcija, $param2);
        $date = date('Y-m-d');
        
        // Insert the post data into the database
        $sql = "INSERT INTO posts VALUES (NULL,'$username', '$postdesc','$date','$naslov')";
        $rezultat = mysqli_query($konekcija,$sql);
        if ($rezultat) {
            return true;
        } else {
            return false;
        }
  }
?>