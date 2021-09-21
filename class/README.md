# Как испольщовать

### 1. Прописываем в Connect.php данные от базы

### 2. Запускаем роутинг командой:
```Route::start();```

### 3. Создаем объект с подклбчением к pdo:
```$db = new QueryBuilder(Connect::make());```

### 4. Далее объект можно использовать, в нем имееются такие команды:

```get_user(string $email)```

```user_register($email, $pass)```

```user_authorization(string $email, string $pass)```

```check_for_admin()```

```access_check()```

```all_users_withdrawal()```

```edit_general_information($user_id, $first_name, $position, $phone, $address)```

```edit_work_status($user_id, $work_status)```

```edit_social($user_id, $vk, $telegram, $instagram)```

```edit_avatar($user_id)```

```get_user_id_by_email($email)```

```get_user_by_id($id)```

```edit_credential($user_id, $email, $password, $old_password)```

```has_image($user_id)```

```delete($user_id)```


