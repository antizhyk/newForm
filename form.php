<?php
//==============Переменные для побключения к БД=====================
$servername = "test-db-service";
$database = "myDB";
$username = "root";
$password = "root";
$sql = "mysql:host=$servername;dbname=$database;";
$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
//=================================================================
//=======Переменные с переданной через POST инфой=====================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(preg_match("/^\p{L}+$/u", $_POST['name'])){
        $name = $_POST['name'];
    }
    if(preg_match("/^\p{L}+$/u", $_POST['surname'])){
    $surname = $_POST['surname'];
    }
    if(preg_match("/^\p{L}+$/u", $_POST['patronymic'])){
        $patronymic = $_POST['patronymic'];
    }
    if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/", $_POST['email'])){
        $email = $_POST['email'];
    }
    $image = '/pictures/' . $_FILES['image']['name'];
    $document = '/documents/' . $_FILES['summary']['name'];
    if(preg_match("/^\d{1,2}\.\d{1,2}\.\d{1,4}$/", $_POST['data'])){
        $data = $_POST['data'];
    }

}
//=================================================================
//==========================Загрузка файлов на сервер==============
$uploaddirDoc = '/var/www/site2/documents/';
$uploaddirPic = '/var/www/site2/pictures/';
$uploadfileDoc = $uploaddirDoc . basename($_FILES['summary']['name']);
$uploadfilePic = $uploaddirPic . basename($_FILES['image']['name']);
move_uploaded_file($_FILES['summary']['tmp_name'], $uploadfileDoc);
move_uploaded_file($_FILES['image']['tmp_name'], $uploadfilePic);
//=================================================================
//==============Подключаюсь к БД и создаю нужные мне таблицы=====================
try {
    $my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
    $sql = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(225),
            surname VARCHAR(225),
            patronymic VARCHAR(225),
            email VARCHAR(225),
            data DATE,
            image VARCHAR(225),
            document VARCHAR(225));
    CREATE TABLE  IF NOT EXISTS  numbers 
    (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_users INT,
        number_telephon VARCHAR(13),
        FOREIGN KEY (id_users) REFERENCES users(id)
    );";
    $my_Db_Connection ->exec($sql);
//=================================================================
//==============Закидываю данные в основную таблицу=====================
// Здесь мы создаём переменную, которая вызывает метод prepare() объекта базы данных
// SQL-запрос, который вы хотите запустить, вводится как параметр, а плейсхолдеры записываются следующим образом - :placeholder_name
    $my_Insert_Statement = $my_Db_Connection->prepare("INSERT INTO users (name, surname, patronymic, email, data, image, document) 
VALUES (:first_name, :last_name, :patronymic, :email, :data, :image, :document)");

// Теперь мы сообщаем скрипту, на какую переменную фактически ссылается каждый плейсхолдер, используя метод bindParam()
// Первый параметр - это плейсхолдер в приведённом выше выражении, второй параметр - это переменная, на которую он должен ссылаться
    $my_Insert_Statement->bindParam(':first_name', $name);
    $my_Insert_Statement->bindParam(':last_name', $surname);
    $my_Insert_Statement->bindParam(':patronymic', $patronymic);
    $my_Insert_Statement->bindParam(':email', $email);
    $my_Insert_Statement->bindParam(':data', $data);
    $my_Insert_Statement->bindParam(':image', $image);
    $my_Insert_Statement->bindParam(':document', $document);


// Выполните запрос, используя данные, которые мы только-что определили
// Метод execute() возвращает TRUE, если запрос был выполнен успешно и FALSE в противном случае, позволяя вам создавать собственные сообщения
    if ($my_Insert_Statement->execute()) {

    } else {
        echo "Unable to create record";
    }
//=================================================================
//==Закидываю данные в таблицу с номерами телефонов=====================
//$sqlSearch = "SELECT `surname` FROM `users` WHERE `name` LIKE '$name'";
//    $result = $my_Db_Connection -> exec($sqlSearch);
//    echo $result;
    $id = $my_Db_Connection->lastInsertId();
    foreach ($_POST['tel'] as $key => $value){
        $my_Insert_Tel = $my_Db_Connection->prepare("INSERT INTO numbers (id_users, number_telephon) VALUES (:id_users, :telephon)");
        $my_Insert_Tel->bindParam(':id_users', $id);
        $my_Insert_Tel->bindParam(':telephon', $value);
        if ($my_Insert_Tel->execute()) {

        } else {
            echo "Unable to create record";
        }
    }

    echo $id;
//=================================================================

} catch (PDOException $error) {
    echo 'Connection error: ' . $error->getMessage();
}



