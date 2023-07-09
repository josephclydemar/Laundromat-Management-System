## CPE340-Laundry-System C# Bois
### WashMatic Laundry System
#### WashMatic is a laundromat app system that streamlines operations for owners and enhances convenience for customers. It enables owners to manage customer orders, sales, and inventory efficiently. Customers can easily access service, track their orders, and provide feedback through the user-friendly app. WashMatic revolutionizes the laundromat experience, making it more efficient and customer-centric.
<br/><br/>

### Use The Following Code Naming Standards:

<br/>

#### Variables: Snake Case
#### Ex:  laundry_user_first_name

<br/>

#### Functions/Methods: Camel Case
#### Ex:  getLaundryUserFirstName

<br/>

#### Classes: Pascal Case
#### Ex: LaundryUser

<br/>
<br/>
<br/>

### MySQL Database and Table Creation Commands:
#### Creating The 'customers' Table:
##### CREATE TABLE customers (customer_id INT NOT NULL AUTO_INCREMENT, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(25) NOT NULL, customer_address VARCHAR(200), total_orders INT NOT NULL, email VARCHAR(100) NOT NULL, customer_password VARCHAR(100) NOT NULL, PRIMARY KEY (customer_id));

<br/>

### PHP to MySQL Connection Instruction:  
#### Selecting First or Last Row From a Table:  
##### $sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";<br/>$stmt = $pdo->query($sql_select);<br/>$one_row = $stmt->fetch(PDO::FETCH_ASSOC);  

<br/>

#### Selecting All Rows From a Table:  
##### $sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";<br/>$stmt = $pdo->query($sql_select);<br/>$all_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

<br/>

#### Inserting Into a Table:
##### $sql_insert = "INSERT INTO users (surname, firstname, age) VALUES (:surname, :firstname, :age)";<br/>$stmt = $pdo->prepare($sql_insert);<br/>$user_data = array(':surname'=>$_POST['surname'], ':firstname'=>$_POST['firstname'], ':age'=>$_POST['age'],);<br/>$stmt->execute($user_data);


