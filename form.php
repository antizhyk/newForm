<?php
$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$patronymic = $_POST['patronymic'];
$image = $_POST['image'];
$data = $_POST['data'];
$document = $_POST['document'];

print_r($_POST);
$servername = "test-db-service";
$database = "myDB";
$username = "root";
$password = "root";
$sql = "mysql:host=$servername;dbname=$database;";
$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
    echo "Connected successfully";
    $sql = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(225),
            surname VARCHAR(225),
            patronymic VARCHAR(225),
            email VARCHAR(225),
            data DATE,
            image BLOB,
            document BLOB);
    CREATE TABLE  IF NOT EXISTS  numbers 
    (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_users INT,
        number_telephon INT(11),
        FOREIGN KEY (id_users) REFERENCES users(id)
    );";
    $my_Db_Connection ->exec($sql);


// Здесь мы создаём переменную, которая вызывает метод prepare() объекта базы данных
// SQL-запрос, который вы хотите запустить, вводится как параметр, а плейсхолдеры записываются следующим образом - :placeholder_name
    $my_Insert_Statement = $my_Db_Connection->prepare("INSERT INTO users (name, surname, patronymic, email, data, image, document) 
VALUES (:first_name, :last_name, :patronymic, :email, :data, :image, :document)");

// Теперь мы сообщаем скрипту, на какую переменную фактически ссылается каждый плейсхолдер, используя метод bindParam()
// Первый параметр - это плейсхолдер в приведённом выше выражении, второй параметр - это переменная, на которую он должен ссылаться
    $my_Insert_Statement->bindParam(':first_name', $name);
    $my_Insert_Statement->bindParam(':last_name', $surname);
    $my_Insert_Statement->bindParam(':patronymic', $surname);
    $my_Insert_Statement->bindParam(':email', $email);
    $my_Insert_Statement->bindParam(':data', $data);
    $my_Insert_Statement->bindParam(':image', $image);
    $my_Insert_Statement->bindParam(':document', $document);


// Выполните запрос, используя данные, которые мы только-что определили
// Метод execute() возвращает TRUE, если запрос был выполнен успешно и FALSE в противном случае, позволяя вам создавать собственные сообщения
    if ($my_Insert_Statement->execute()) {
        echo "New record created successfully";
    } else {
        echo "Unable to create record";
    }

} catch (PDOException $error) {
    echo 'Connection error: ' . $error->getMessage();
}



