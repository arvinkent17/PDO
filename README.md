PDO
===

Dynamic PDO Database Class

It is easy, secured and reusable to any website projects development that uses PHP programming language.

How to use it?

Just extend your class with database class

Example:

  Class User extends Database {
  
    public function addUser($username, $password) {
    
      $this->executeMySQL("INSERT into User (username, password) VALUES (?, ?)", array($username, $password));
      
      if ($this->isInserted()) {
      
        return true;
        
      }  
      
    }
    
  }
  
  $user = new User();
  
  $insertData = $user->addUser("TEST", "TEST");
  
  if ($insertData) {
  
    echo "successfully added user";
    
  }
  
  Note: You can use any query either is it Create, Update, Delete, Search, Read and pass values on the method executeMySQL
