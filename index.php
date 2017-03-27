<?php
if (isset($_POST['codigo']))
{

    $codigo = trim($_POST['codigo']);

    echo "*************";
    echo substr_count($codigo, "\n");
    echo "<br />";
    echo "*************";
    
    $temp = explode("\n", $codigo);

    echo "Explode";
    echo "<pre>";
    print_r($temp);
    echo "</pre>";
    echo "*************";

    for ($i = 0; $i < count($temp); $i++)
    {
       $temp[$i] = str_replace("<br />", "", $temp[$i]);
        echo "<pre>";
        print_r(str_split(trim($temp[$i])));
        echo "</pre>";
        echo "*************";
    }

    //var_dump(str_split($codigo));
}
?>

<html>
    <form action="" method="POST">
        <textarea name="codigo"></textarea>
        <input type="submit" value="enviar"/> 
    </form>
</html>
