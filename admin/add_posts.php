<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['publish'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'active';

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);

    if (isset($image)) {
        if ($select_image->rowCount() > 0 and $image != '') {
            $message[] = 'Hình đã có!';
        } elseif ($image_size > 2000000) {
            $message[] = 'Hình có kích thước quá lớn!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = '';
    }

    if ($select_image->rowCount() > 0 and $image != '') {
        $message[] = 'Hãy đổi tên hình!';
    } else {
        $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
        $message[] = 'Đăng công khai thành công!';
    }

}

if (isset($_POST['draft'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'deactive';

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);

    if (isset($image)) {
        if ($select_image->rowCount() > 0 and $image != '') {
            $message[] = 'image name repeated!';
        } elseif ($image_size > 2000000) {
            $message[] = 'Hình có kích thước quá lớn!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = '';
    }

    if ($select_image->rowCount() > 0 and $image != '') {
        $message[] = 'Hãy đổi tên hình!';
    } else {
        $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
        $message[] = 'Đã lưu bản nháp!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Viết</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>


<?php include '../components/admin_header.php' ?>

<section class="post-editor">

    <h1 class="heading">Thêm bài viết mới</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
        <p>Tiêu đề <span>*</span></p>
        <input type="text" name="title" maxlength="100" required class="box">
        <p>Nội dung <span>*</span></p>
        <textarea name="content" class="box" required maxlength="10000" cols="30" rows="10"></textarea>
        <p>Thuộc danh mục <span>*</span></p>
        <select name="category" class="box" required>
            <option value="" selected disabled>-- select category*</option>
            <option value="nature">nature</option>
            <option value="education">education</option>
            <option value="pets and animals">pets and animals</option>
            <option value="technology">technology</option>
            <option value="fashion">fashion</option>
            <option value="entertainment">entertainment</option>
            <option value="movies and animations">movies</option>
            <option value="gaming">gaming</option>
            <option value="music">music</option>
            <option value="sports">sports</option>
            <option value="news">news</option>
            <option value="travel">travel</option>
            <option value="comedy">comedy</option>
            <option value="design and development">design and development</option>
            <option value="food and drinks">food and drinks</option>
            <option value="lifestyle">lifestyle</option>
            <option value="personal">personal</option>
            <option value="health and fitness">health and fitness</option>
            <option value="business">business</option>
            <option value="shopping">shopping</option>
            <option value="animations">animations</option>
        </select>
        <p>Hình ảnh</p>
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
        <div class="flex-btn">
            <input type="submit" value="Đăng bài viết" name="publish" class="btn">
            <input type="submit" value="Lưu bản nháp" name="draft" class="option-btn">
        </div>
    </form>

</section>


<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>