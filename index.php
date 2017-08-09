<?php 
require __DIR__.'/vendor/autoload.php'; // adding slim framework
require 'db.php'; // Connection to database 

// instatiating slim 
$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// index page
$app->get('/','get_employee' ); // calling function get_employee on index page


$app->get('/emp/{id}',function($request,$response,$args)
{
	get_employee_id($args['id']);
});

// add an employee
$app->post('/empadd',function($request,$response,$args){
	add_employee($request -> getParsedBody()); // request object's method to parse through http request
});

//update employee details
$app->post('/empupdate',function($request,$response,$args)
{
    update_employee($request->getParsedBody());
});

//delete a record
$app->delete('/emp_delete',function($request,$response,$args){
    delete_employee($request->getParsedBody());
});

function get_employee()
{
	$db=connect_db();
	$sql=" Select * from employee order by `emp_name` ";
    $exe=$db->query($sql);
    $data = $exe->fetch_all(MYSQLI_ASSOC);
    $db=null;
    echo json_encode($data);
}

function get_employee_id($emp_id){
	$db=connect_db();
	$sql="select * from employee where `employee_id`='$emp_id'";
	$exe=$db->query($sql);
	$data= $exe->fetch_all(MYSQLI_ASSOC);
	$db=null;
	echo json_encode($data);
}

function add_employee($data) {
    $db = connect_db();
    $sql = "insert into employee (emp_name,emp_contact,emp_role) "
            . " VALUES(?,?,?)";
    if($stmt=$db->prepare($sql))
    {
        $stmt->bind_param("sss",$data['emp_name'],$data['emp_contact'],$data['emp_role']);  
        if(!$stmt->execute())
            echo $stmt->error;
        $last_id = $db->insert_id;
    }
    else
    {
        echo "no";
    }
    $db = null;
    if (!empty($last_id))
        echo $last_id;
    else
        echo 'false';
}

function update_employee($new_data)
{
    $db=connect_db();
    $sql="UPDATE employee SET emp_name=? , emp_contact=? , emp_role=? where employee_id=?";
    $stmt=$db->prepare($sql);
    $stmt->bind_param("sssi",$new_data['emp_name'],$new_data['emp_contact'],$new_data['emp_role'],$new_data['employee_id']);
    if($stmt->execute())
        echo "Updated successfully";
    else
        echo $stmt->error;
    $db=null;
}

function delete_employee($emp)
{
    $db=connect_db();
    $sql="DELETE from employee where employee_id=? ";
    $stmt=$db->prepare($sql);
    $stmt->bind_param('i',$emp['employee_id']);
    if($stmt->execute())
        echo "Deleted";
    else
        echo $stmt->error;
    $db=null;
}

$app->get('/articleImage', function ($request, $response) {
    $image = @file_get_contents('test.jpg');
    if ($image === false) {
        $response->write('Could not find test.jpg.');
        return $response->withStatus(404);
    }

    $response->write($image);
    return $response->withHeader('Content-Type', 'image/jpeg');
});

// running application
$app->run();

?>

