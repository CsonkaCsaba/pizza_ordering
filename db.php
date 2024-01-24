<?php

define("DBHOST","localhost");
define("DBNAME","pizza");
define("DBUSER","root");
define("DBPASSWORD","");
$conn = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME,DBUSER,DBPASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


try {
    $conn->beginTransaction();

    $conn->commit();   
    } catch (PDOException $e){
        echo "Adatbázis hiba: " .$e->getMessage();
        $conn->rollBack();
    } catch (Exception $e){
        echo "Egyéb hiba: " .$e->getMessage();
        die();
}

if(isset($_POST['nev'])){
    $nev = $_POST["nev"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $pizza = $_POST["pizza"];
    $pizzasize = $_POST["pizzasize"];
    $extracheese = $_POST["extracheese"];
    $timestamp = date("Y-m-d H:i:s");

    try{
        $sql="INSERT INTO pizza.pizza (nev, email, telefon, pizza, meret, extrasajt, ido) VALUES (:nev,:email,:phone,:pizza, :pizzasize, :extracheese, :ido)";
        $query = $conn->prepare($sql);
        $query->bindParam(':nev', $nev, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':phone', $phone, PDO::PARAM_INT);
        $query->bindParam(':pizza', $pizza, PDO::PARAM_STR);
        $query->bindParam(':pizzasize', $pizzasize, PDO::PARAM_STR);
        $query->bindParam(':extracheese', $extracheese, PDO::PARAM_STR);
        $query->bindParam(':ido', $timestamp, PDO::PARAM_STR);
        $query->execute();

        $lastId = $conn->lastInsertId();
        $sql2 ="SELECT * FROM pizza.pizza WHERE pizza.azon = ? AND pizza.nev = ?";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(1, $$lastId, PDO::PARAM_INT);
        $query2->bindParam(2, $pizza, PDO::PARAM_STR);
        $query2->execute();
        $array = array('azon' => $lastId, 'lastEmail' => $email);
        echo json_encode($array);

        } catch (PDOException $e){
            echo "Adatbázis hiba: " .$e->getMessage();    
        } catch (Exception $e){
            echo "Egyéb hiba: " .$e->getMessage();
        die();
        }
} 
?>