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

<br>

**Create the `services` table:**
```sql
CREATE TABLE services (service_id INT NOT NULL AUTO_INCREMENT, service_name VARCHAR(50), service_description TEXT, PRIMARY KEY (service_id));
```

<br>

**Create the `orders` table:**
```sql
CREATE TABLE orders (order_id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, service_id INT NOT NULL, date_ordered DATETIME NOT NULL, remaining_time INT NOT NULL, order_status INT NOT NULL, order_weight DOUBLE NOT NULL, order_description TEXT, PRIMARY KEY (order_id));
```

<br>

**Create the `payments` table:**
```sql
CREATE TABLE payments (payment_id INT NOT NULL AUTO_INCREMENT, order_id INT NOT NULL, payment_date DATETIME NOT NULL, payment_amount DOUBLE NOT NULL, PRIMARY KEY (payment_id));
```

<br>

**Create the `messages` table:**
```sql
CREATE TABLE messages (message_id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, order_id INT NOT NULL, message_date DATETIME NOT NULL, message TEXT, PRIMARY KEY (message_id));
```

<br>
<br>
<br>

**Insert an admin into `users` table:**
```sql
INSERT INTO users (firstname, lastname, user_address, total_orders, email, user_password, user_type) VALUES ('Miguelito', 'Legazpi', 'Consolacion, Cebu', 0, 'miguelito24@gmail.com', 'gwapokohaha', 1);
```

<br>

**Insert a service into `services` table:**
```sql
INSERT INTO services (service_name, service_description) VALUES ("Wash, Dry", "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.");
```

<br>

**Insert an order into `orders` table:**
```sql
INSERT INTO orders (user_id, service_id, date_ordered, remaining_time, order_status, order_weight, order_description) VALUES (13, 2, '2023-07-11 19:52:02', 234, 3, 4.56, 'Ahahahahha');
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
