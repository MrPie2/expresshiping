<?php
include('../Connect.php'); 
include('bootstrap.php');
include('Header.php');
?>


<section>
    <div class="container-fluid">
        
        <div class="row">
                    <div class="col-md-12">
                        
                        <?php
                        $Page_Name=$_GET['Page'];
                        $sql="select * from pages where Page_Name='$Page_Name'";
                        $query=mysqli_query($Conn, $sql);
                        while($row=mysqli_fetch_array($query, MYSQLI_ASSOC)){
                            echo $row['Page_Contents'];
                        }
                        ?>
                    </div>
        </div>
    </div>
</section>


<?php 
include('Footer.php');

include('Scripts.php');
?>