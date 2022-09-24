# CodeIgniter3-MY_Model

It provides a full CRUD base for database interactions, intelligent table name guessing and soft delete.
## Installation/Usage

Download and drag the **MY_Model.php** file into your **application/core** directory. CodeIgniter will load and initialise this class automatically.

Extend your model classes from MY_Model and all the functionality will be baked in automatically.
```php
class User_model extends MY_Model
{
protected $table = 'users'; //you MUST mention the table name
protected $primaryKey = 'id'; //you MUST mention the primary key
protected $createdField  = ''; //created_at field name
protected $updatedField  = '';  //updated_at field name
protected $deletedField  = '';  //deleted field name
protected $useSoftDeletes = false; //if you need to apply  soft delete 

	public function __construct()
	{
		parent::__construct();
	}
}
```

## example
```php
class User_model extends MY_Model { }

$this->load->model('user_model');
// params $limit = null, $offset = 0
$this->user_model->findAll()

$this->user_model->find(1);
    // get first row
$this->user_model->getFirst();
   // get last row
$this->user_model->getLast();
// get multi rows
$this->user_model->getWhere(['username !='=>'arafat']);
// get  one row by where array
$this->user_model->findWhere(['username'=>'arafat']);

$this->user_model->insert(array('username' => 'arafat','email' => 'arafat.733011506@gmail.com'));
$id=1;
$update_data=array('username' => 'arafat','email' => 'arafat.733011506@gmail.com');
// param2  $id or where array
$this->user_model->update($update_data,$id);
// insert or update
$_data=array('username' => 'arafat','email' => 'arafat.733011506@gmail.com');

$this->user_model->save($_data);
// update
$_id=1;
$this->user_model->save($_data,$_id);

$this->user_model->delete(1);

$this->user_model->softDelete(1);
```





Enjoy using my MY_Model and please report any issues or try some pull requests. Thank you