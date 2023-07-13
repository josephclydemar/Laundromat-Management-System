## CPE340-Laundry-System _WashMatic_
---

#### Download and Install [XAMPP](https://www.apachefriends.org/ "Apache Friends"):

<br>

---

### Follow the ff. code naming standards:

1. Variables: **Snake Case**
    * ex: `laundry_user_first_name`

1. Functions/Methods: **Camel Case**
    * ex: `getLaundryUserFirstName`

1. Classes: **Pascal Case**
    * ex: `LaundryUser`

<br/>

---
### MySQL Database and Table Creation Commands:

**Create the database in _phpMyAdmin_ and name it `laundry_system`.**

**Create the `users` table:**
```sql
CREATE TABLE users (user_id INT NOT NULL AUTO_INCREMENT, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(25) NOT NULL, user_address VARCHAR(200), total_orders INT NOT NULL, email VARCHAR(100) NOT NULL, user_password VARCHAR(100) NOT NULL, user_type INT NOT NULL, PRIMARY KEY (user_id));
```

**Create the `services` table:**
```sql
CREATE TABLE services (service_id INT NOT NULL AUTO_INCREMENT, service_name VARCHAR(50), description TEXT, PRIMARY KEY (service_id));
```

**Create the `orders` table:**
```sql
CREATE TABLE orders (order_id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, service_id INT NOT NULL, remaining_time INT NOT NULL, status INT NOT NULL, weight DOUBLE NOT NULL, description TEXT, PRIMARY KEY (order_id));
```

<br>

---


### PHP to MySQL Connection code:  
**Selecting All Rows From a table:**
```php
$sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
$stmt = $pdo->query($sql_select);
$all_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

<br>

**Selecting First or Last Row From a table:**
```php
$sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
$stmt = $pdo->query($sql_select);
$one_row = $stmt->fetch(PDO::FETCH_ASSOC);
```

<br>

**Selecting A Specific Row From a table:**
```php
$sql_select = "SELECT * FROM users WHERE surname=:surname";
$stmt = $pdo->prepare($sql_select);
$stmt->execute(array(':surname'=>$_POST['surname']););
$specific_user = $stmt->fetch(PDO::FETCH_ASSOC);
```

<br>
<br>
<br>

**Inserting Into a table:**
```php
$sql_insert = "INSERT INTO users (surname, firstname, age) VALUES (:surname, :firstname, :age)";
$stmt = $pdo->prepare($sql_insert);
$user_data = array(':surname'=>$_POST['surname'], ':firstname'=>$_POST['firstname'], ':age'=>$_POST['age']);
$stmt->execute($user_data);
```
