<?php
include('../../Connect.php'); 
require_once('../twig/vendor/autoload.php');
$loader = new \Twig\Loader\ArrayLoader();
$twig = new \Twig\Environment($loader);
?>
<style>

@media screen and (max-width: 600px){
 img{
        width: 100%;
    }
}
   
@media screen and (min-width: 600px){
    img{
        width: 50%;
    }
}
</style>

<section>
    <div class="container-fluid">
        <div class="row" style="position: relative;">
                    <div class="col-md-12" style="padding: 0px;">
                        
                        <?php
                        $invoice_id=$_GET['invoice_id'] ?? null;
                        $sqlinvoice="select * from invoice where invoice_id='$invoice_id'";
				    $queryinvoice=mysqli_query($Conn, $sqlinvoice);
				    $invoice_data=[];
	

while($rowinvoice = mysqli_fetch_assoc($queryinvoice)){

    $product_id = $rowinvoice['product_id'];

    $queryprod = mysqli_query(
        $Conn,
        "SELECT * FROM Product WHERE Product_id='$product_id'"
    );

    $rowinvoice['product'] = mysqli_fetch_assoc($queryprod);

    $invoice_data[] = $rowinvoice;
}
                        /*
                        //sele users
                        $Investor_id=$_SESSION['User_id'];
                        $sql="select * from investors where Investor_id='$Investor_id'";
                        $query=mysqli_query($Conn, $sql);
                        $query2=mysqli_query($Conn, $sql);
                        $rowd=mysqli_fetch_assoc($query2);
                        $Fin_Asset= number_format($rowd['Fin_Asset'], 2,'.',',');
                        $Total_Deposit= number_format($rowd['Total_Deposit'], 2,'.',',');
    
                        $First_Name= $rowd['First_Name'];
                        
                        
                        //sql select contracts
                        $sqlcontracts="select * from contracts where Investor_id='$Investor_id'";
                        $querycontract=mysqli_query($Conn, $sqlcontracts);
                        $contract=[];
                        while($rowcontract = mysqli_fetch_assoc($querycontract)){
                                $contracts[] = $rowcontract;
                            }
                        
                           
                        //sql select investmentplans
                        $sqlinv="select * from investmentplans";
                        $queryinv=mysqli_query($Conn, $sqlinv);
   
                        $invt=[];
                       
                        while($rowinv = mysqli_fetch_assoc($queryinv)){
                                $investmentplans[] = $rowinv;
                            }
                        
                        
                         //sql select assets
                        $sqlasset="select * from assets where Investor_id='$Investor_id'";
                        $queryasset=mysqli_query($Conn, $sqlasset);
                        $rowasset=mysqli_fetch_assoc($queryasset);
                        $countasset=mysqli_num_rows($queryasset);
                       $asset_name=$rowasset['asset_name'];
                        $asset_amount=number_format($rowasset['amount'], 2, '.',',');

                        
*/
                        
                        
                        //select page
                        $sql="select Page_Contents, styles_css, script_js, Page_Name from pages where link='$lin'";
                        $query=mysqli_query($Conn, $sql);
                        $row=mysqli_fetch_assoc($query);
                        
                         //select footer 
                        $sqlbnav="select Page_Contents, styles_css, script_js, Page_Name from pages where link='footer'";
                        $querybnav=mysqli_query($Conn, $sqlbnav);
                        $rowfooter=mysqli_fetch_assoc($querybnav);
                        $footer=$rowfooter['Page_Contents'];
                        $footer_css=$rowfooter['styles_css'];
                        
                         //select header nav
                        $sqlhead="select Page_Contents, styles_css, script_js, Page_Name from pages where link='header'";
                        $queryhead=mysqli_query($Conn, $sqlhead);
                        $rowhead=mysqli_fetch_assoc($queryhead);
                        $header=$rowhead['Page_Contents'];
                        $header_css=$rowhead['styles_css'];
                        
                         //select services 
                        $sqlserv="select * from pages where type='services'";
                        $queryserv=mysqli_query($Conn, $sqlserv);
                        $services=[];
                       
                        while( $rowserv=mysqli_fetch_assoc($queryserv)){
                                $services[] = $rowserv;
                            }

                        $template=$row['Page_Contents'];
                        $js=$row['script_js'];
                        $css=$row['styles_css'];
                        $page_name=$row['Page_Name'];
                        echo "<style>".$row['styles_css']."</style>";
                        
                        echo "<style>".$rowfooter['styles_css']."</style>";
                        echo "<style>".$rowhead['styles_css']."</style>";
                     echo   $twig->createTemplate($template)->render([
                            'template' =>$template,
                            'Page_Name' => $page_name,
                            'footer' => $footer,
                            'header' => $header,
                            'services' => $services,
                            'invoice_data'=>$invoice_data
                            ]);
                             echo "<script>".$row['script_js']."</script>";
?>
                    </div>
        </div>
    </div>
</section>

<?php include('Footer.php'); ?>
