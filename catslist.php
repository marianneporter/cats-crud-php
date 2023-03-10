<?php 
    require_once('database/dbConnect.php');
    $dbConnection = new DB_Connect();
    $db = $dbConnection->CreateConnection();
    if ($db == null) {     
        header("Location: error.php");
    }

    require_once('database/dbQueries.php');
    $dbQueries = new DB_Queries();
    $cats = $dbQueries->getCats($db);

    if ($cats==null) {
        header("Location: error.php");
    }  

    require_once('includes/header.php');

    require_once('utility/dates.php');
   
?>  
    <div class="all-cats" >
        <h2 class="all-cats-title" >All Cats</h2>
        <div class="add-area">
            <a href="add-update.php" class="btn btn-primary add-btn mt-3 mb-2 me-2" >Add Cat</a>
        </div>

        <div class="d-none d-lg-block px-3">
            <table class="table ">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Colour</th>
                        <th>Favourite Food</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<count($cats); $i++) {  ?>
                        <tr>
                            <td><?php echo $cats[$i]["name"] ?></td>  
                            <td><?php echo DateFunctions::dbToDisplayFormat($cats[$i]["dob"]) ?></td>
                            <td><?php echo $cats[$i]["colour"] ?></td> 
                            <td><?php echo $cats[$i]["fav_food"] ?></td>
                            <td><a href="add-update.php?id=<?php echo $cats[$i]['id']?>" class="btn btn-primary">edit</a></td>
                            <td><a class="btn btn-danger" href="delete.php?id=<?php echo $cats[$i]['id']  ?>">delete</a></td>                     
                        </tr>  
                    <?php } ?>                
                </tbody>
            </table>
        </div>
    </div>


    <div class="d-lg-none">       
        <div class="cat-cards" >
            <?php for($i=0; $i<count($cats); $i++) {  ?>
                <div class="card-content">
                    <h4><?php echo $cats[$i]["name"] ?></h4>  
                    <div><h6> DOB:&nbsp; </h6><?php echo DateFunctions::dbToDisplayFormat($cats[$i]["dob"]) ?></div>
                    <div><h6> Colour:&nbsp; </h6></h6><?php echo $cats[$i]["colour"] ?></div> 
                    <div><h6> Favourite Food:  </h6> <?php echo $cats[$i]["fav_food"] ?></div>
                    <div class="buttons-col">
                        <a href="add-update.php?id=<?php echo $cats[$i]['id']?>" class="btn btn-primary btn-sm">edit</a>
                        <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $cats[$i]['id']  ?>">delete</a>
                    </div> 
                </div>  
            <?php } ?>      
        </div>                    
    </div>
        
    <?php require_once('includes/footer.php') ?>   
