<?php 
require_once 'includes/header.php'; 

require_once 'utility/dates.php';

// initial handling for first time this page is routed to
function addCatForForm() {   
    $cat = new stdClass;
    $cat->id=0;
    $cat->name = "";
    $cat->dob = "";
    $cat->colour ="";
    $cat->favFood ="";
   
    return (object) $cat;
}

//initial handling for update the first time this page is routed to
//db is accessed to get the current field values for the stored cat
function updateCatForForm($id) {

    require_once('includes/getCatObject.php');

    
    $catObj = getCatObject($id);

    return $catObj; 

}


function handleSubmittedForm($id=0) {    
    $cat = new stdClass();
    $cat->id      = isset($_POST['id'])   ?  $_POST['id'] : 0;  
    $cat->name    = isset($_POST['name']) ? $_POST['name'] : '';
    $cat->dob     = isset($_POST['dob']) && $_POST['dob'] != '' ? DateFunctions::displayToDBFormat($_POST['dob']) : '';
    $cat->colour  = isset($_POST['colour']) ? $_POST['colour'] : '' ;
    $cat->favFood = isset($_POST['favFood']) ? $_POST['favFood'] : '';    
    $errors = array('name' => '', 'dob' => '', 'colour' => '', 'favFood' => '');
 
    if($cat->name == '') {
        $errors['name'] = "Cat's name is required";            
    } else {
      
        if(!preg_match('/^[a-zA-Z\s]+$/', $cat->name)) {
            $errors['name'] = "Name must contain only letters and spaces";
        }
    }

    if($cat->dob=='') {
        $errors['dob'] = "Cat's date of birth is required";
    }  

    if($cat->colour=='') {        
        $errors['colour'] = "Cat's colour is required";
    } 

    if($cat->favFood=='') {        
        $errors['favFood'] = "Cat's favourite food is required";
    }  

    return [
        "cat" => $cat,
        "errors" => $errors
    ];       
} 


if (isset($_POST['submit'])) {
    //handle processing for submitted form

    $submissionResult=handleSubmittedForm();
    $errors = $submissionResult['errors'];  
    $cat    = $submissionResult['cat'];    
    if (!array_filter($errors)) {
        // do add or update for valid cat
        require_once('includes/updateDB.php');
    } 

} else {

    // handle processing for unsubmitted (freshly displayed form)

    $errors = array('name' => '', 'dob' => '', 'colour' => '', 'favFood' => ''); 
    if (isset($_GET['id'])) {
        $id=$_GET['id'];
        $cat=updateCatForForm($id);  
    } else {
        $cat=addCatForForm();
    }}

?>

<div class="add-update">
    <h3><?php echo $cat->id=='0' ? 'Add' : 'Update'  ?> Cat</h3>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"
          onsubmit="return formSubmitted(event);" > 
        <input value="<?php echo $cat->id ?>" type="hidden" name="id" > 
        <div class="mt-2 mb-3">
          
                <label class="form-label">Cat's Name</label>
                <input type="text"
                       class="form-control"
                       id="name" name="name"
                       value="<?php echo htmlspecialchars($cat->name) ?>">  
           
            <div class="text-danger"><?php echo $errors['name']?></div>    
        </div>

        <div class="mb-3">           
            <label class="form-label">Date of Birth</label>
            <input type="text" class="form-control"
                id="dob" name="dob" readonly="readonly"
                style="background-color: white;"
                value="<?php echo htmlspecialchars(DateFunctions::dbToDisplayFormat($cat->dob)) ?>">                 
           
            <div class="text-danger"><?php echo $errors['dob']?></div>    

        </div>     

        <div class="mb-3">
            <label class="form-label">Colour</label>
            <select id="colourSelect" class="form-select colour-select" name="colour"
                    data-colour="<?php echo $cat->colour ?>" >
                <option hidden value="">Select Colour</option>       
                <option>Black</option>
                <option>Black and White</option>
                <option>Tabby</option=>
                <option>Tabby and White</option=>
                <option>Ginger</option>
                <option>Ginger and White</option=>
                <option>White</option>
                <option>Grey</option>
                <option>Grey and White</option>
                <option>Tortoiseshell</option>
            </select>  
            <div class="text-danger"><?php echo $errors['colour']?></div>     
        </div>
        <div class="mb-3">
            <label class="form-label">Cat's Favourite Food</label>
            <select id="favFood" class="form-select fav-food-select" name="favFood"
                data-food="<?php echo $cat->favFood ?>"> 
                <option hidden value="">Select Favourite Food</option>                          
                <option>Fish</option>
                <option>Chicken</option>
                <option>Wet cat food</option>
                <option>Dry cat food</option> 
            </select>  
            <div class="text-danger"><?php echo $errors['favFood']?></div>     
        </div>
        <div class="mb-5 d-sm-flex justify-content-around button-set">
            <a href="catslist.php" class="btn btn-secondary">Cancel</a> 
            <button type="submit"
                    class="btn btn-success"
                    name="submit"><?php echo $cat->id=='0' ? 'Add' : 'Update'  ?> Cat</button>
        </div>
    </form>

    <?php require_once('includes/footer.php') ?>

</div>