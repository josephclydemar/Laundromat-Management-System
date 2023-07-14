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

**Create the `payments` table:**
```sql
CREATE TABLE payments (payment_id INT NOT NULL AUTO_INCREMENT, order_id INT NOT NULL, payment_date DATETIME NOT NULL, payment_amount DOUBLE NOT NULL, PRIMARY KEY (payment_id));
```

<br>

**Insert an admin to `users` table:**
```sql
INSERT INTO users (firstname, lastname, user_address, total_orders, email, user_password, user_type) VALUES ('Miguelito', 'Legazpi', 'Consolacion, Cebu', 0, 'miguelito24@gmail.com', 'gwapokohaha', 1);
```

<br>

---


### PHP to MySQL Connection code:  
**Selecting all Rows from a Table:**
```php
$sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
$stmt = $pdo->query($sql_select);
$all_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

<br>

**Selecting first or last Row from a Table:**
```php
$sql_select = "SELECT * FROM users";   or   $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
$stmt = $pdo->query($sql_select);
$one_row = $stmt->fetch(PDO::FETCH_ASSOC);
```

<br>

**Selecting a specific Row from a Table:**
```php
$sql_select = "SELECT * FROM users WHERE surname=:surname";
$stmt = $pdo->prepare($sql_select);
$stmt->execute(array(':surname'=>$_POST['surname']););
$specific_user = $stmt->fetch(PDO::FETCH_ASSOC);
```

<br>
<br>
<br>

**Inserting into a Table:**
```php
$sql_insert = "INSERT INTO users (surname, firstname, age) VALUES (:surname, :firstname, :age)";
$stmt = $pdo->prepare($sql_insert);
$user_data = array(':surname'=>$_POST['surname'], ':firstname'=>$_POST['firstname'], ':age'=>$_POST['age']);
$stmt->execute($user_data);
```
