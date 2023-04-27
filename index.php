<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$name = htmlspecialchars($_POST['todo']);
$name = trim($name);
$json = [];
$name2 = htmlspecialchars($_POST['todo_pod']);
$name2 = trim($name2);
$json2 = [];
$time = htmlspecialchars($_POST['todo_num']);
$time = trim($time);
$time_array = [];

if (file_exists('data.json')) {
  $data_json = file_get_contents('data.json');
  $json = json_decode($data_json, true);
}
if (file_exists('data_pod.json')) {
  $data_pod_json = file_get_contents('data_pod.json');
  $json2 = json_decode($data_pod_json, true);
}
if (file_exists('time.json')) {
  $time_json = file_get_contents('time.json');
  $time_array = json_decode($time_json, true);
}

if ($name) {
  $json[] = $name;
  file_put_contents('data.json', json_encode($json, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
if ($name2) {
  $json2[] = $name2;
  file_put_contents('data_pod.json', json_encode($json2, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
if ($time) {
  $time_array[] = $time;
  file_put_contents('time.json', json_encode($time_array, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}

$key = @$_POST['todo_name'];
if (isset($_POST['del'])) {
  unset($json[$key]);
  file_put_contents('data.json', json_encode($json, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}

$key2 = @$_POST['todo_pod_name'];
if (isset($_POST['pod_del'])) {
  unset($json2[$key2]);
  file_put_contents('data_pod.json', json_encode($json2, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_ REFERER']);
}

$key3 = @$_POST['todo_num'];
if (isset($_POST['num_del'])) {
  unset($time_array[$key3]);
  file_put_contents('time.json', json_encode($time_array, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['save'])) {
  $json[$key] = @$_POST['title'];
  file_put_contents('data.json', json_encode($json, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
if (isset($_POST['pod_save'])) {
  $json2[$key2] = @$_POST['title_pod'];
  file_put_contents('data_pod.json', json_encode($json2, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
if (isset($_POST['num_save'])) {
  $time_array[$key3] = @$_POST['title_num'];
  file_put_contents('time.json', json_encode($time_array, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  header('Location: '. $_SERVER['HTTP_REFERER']);
}
if (isset($_POST['export_excel'])) {
  $t = time();
  header("Content-Type: text/csv; charset=utf-8");
  header("Content-Disposition: attachment; filename=download".$t .".csv");
  $output = fopen("php://output", "w");
  fputcsv($output, ['ID', 'Tasks']);
 
foreach($json as $id => $arr) {
     fputcsv($output, [$id, $arr]);
 } 
   fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="styles.css">
  <title>Document</title>
</head>
<body>
  <div class="cmn">
    <button class="btn_create" data-toggle="modal" data-target="#exampleModal">Создать новую задачу</button>
    <form action="" method="post" class="form_exp">
      <button class="btn_save" name="export_excel">Сохранить в Localstorage</button>
    </form>
    <?php
    
    ?>
    <br>
    <div class="raz">
      <span>Номер</span>
      <span>Текст</span>
      <span>Действия</span>
    </div>
    <div class="cont">
    <?php
    foreach ($json as $key => $todo): ?>
      <span><?php echo $key + 1; ?></span>
      <div class="contik">
        <input type="text" readonly value="<?php echo $todo; ?>" class="out_inp">
        <div class="cont3">
          <div class="cont4">
          <?php foreach ($json2 as $key2 => $todo2): ?>
              
              <div><input type="text" readonly value="<?php echo $todo2; ?>" class="out_inp_2"></div>
          <?php endforeach; ?>
          </div>
          <div class="cont4">
          <?php foreach ($time_array as $key3 => $todo3): ?>
              <div><input type="text" readonly value="<?php echo $todo3; ?>" class="out_inp_2">
              <button class="bnt_edit" data-toggle="modal" data-target="#edit<?php echo $key; ?>"><i class="fas fa-edit"></i></button>
              <button class="btn_dlt" data-toggle="modal" data-target="#delete<?php echo $key; ?>"><i class="fas fa-trash-alt"></i></button>
              </div>
          <?php endforeach; ?>
          </div>
        </div>
        <div>
        </div>
      </div>
      <span>
        <button class="bnt_edit" data-toggle="modal" data-target="#edit<?php echo $key; ?>"><i class="fas fa-edit"></i></button>
        <button class="btn_dlt" data-toggle="modal" data-target="#delete<?php echo $key; ?>"><i class="fas fa-trash-alt"></i></button>
        <button class="add_pod" data-toggle="modal" data-target="#create<?php echo $key; ?>"><i class="fas fa-plus"></i></button>
        <div class="modal fade" id="create<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Добавить подзадачу</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">
                          <p class="tit_inp">Название</p>
                          <p class="tit_inp ntn_2">Кол-во часов</p>
                          <div class="input-group">
                              <input type="text" class="form-control" name="todo_pod" id="name_pod"><br>
                              <input type="number" name="todo_num" class="form-control inp_num">
                          </div>
                  </div>
                  <div class="modal-footer">
                      <button class="btn btn-primary send" data-send="1">Создать</button>
                  </div> </form>
              </div>
          </div>
        </div>
        <div class="modal fade" id="delete<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Вы хотите удалить запись №<?php echo $key + 1 ;?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body ml-auto">
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">
                          <div class="input-group">
                              <input type="hidden" name="todo_name" value="<?php echo $key; ?>">
                          </div>
                          <button class="btn btn-danger del" name="del">Удалить</button>
                  </div>
                  </form>
              </div>
          </div>
        </div>
        <div class="modal fade" id="edit<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Изменить запись</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    <form action="" method="post" class="mt-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="title" value="<?php echo $todo; ?>">
                        </div>
                        <input type="hidden" name="todo_name" value="<?php echo $key;?>">
                        <div class="modal-footer">
                            <button type="submit" name="save" class="btn btn-sm btn-success p-1 pt-0" data-target="#edit<?php echo $key;?>">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </span>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить запись</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" name="todo" >
                    </div>
                    <div class="add_podd">
                      <div class="first_cont">
                        <p style="text-align:left;">Подзадача</p>
                        <input type="text" class="form-control inp_podza" name="todo_pod">
                      </div>
                      <div class="first_cont">
                        <p style="text-align:left; padding-left:20px;">Кол-во часов</p>
                        <input type="number" name="todo_num" class="form-control inp_num inp_podza">
                      </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary send" data-send="1">Создать</button>
            </div> </form>
        </div>
    </div>
  </div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>