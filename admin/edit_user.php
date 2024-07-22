<?php 

    session_start();
    if(isset($_SESSION['user_id'])){

    require "../dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // var_dump($_FILES);
        $id = $_POST['userID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_SESSION['password'];
        $old_photo = $_POST['old_photo'];

        // echo "$id and $title and $category_id and $description and $old_photo";

        // File Upload
        $image_array = $_FILES['profile'];
        var_dump($image_array);
        if(isset($image_array) && $image_array['size'] > 0){
            $folder_name = 'images/';
            $image_path = $folder_name.$image_array['name'];

            $tmp_name = $image_array['tmp_name'];
            move_uploaded_file($tmp_name,$image_path);
        }else{
            $image_path = $old_photo;
        }
        $sql = "UPDATE users SET name = :name, email = :email, password = :password, profile = :image_path WHERE id = :id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':id',$id);
        $stmt -> bindParam(':name',$name);
        $stmt -> bindParam(':email',$email);
        $stmt -> bindParam(':password',$password);
        $stmt -> bindParam(':image_path',$image_path);
        $stmt -> execute();

        header("location: users.php");
    }else{
        include "layouts/nav_sidebar.php";

        $user_id = $_GET['id'];
        // echo $post_id;

        $sql = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':user_id',$user_id);
        $stmt -> execute();
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);
        // var_dump($user);

        $sql = "SELECT * FROM users";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
        $users = $stmt -> fetchAll();
    }
?>
                <main>
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Create Users</h1>
                            <a href="create_user.php" class=" btn btn-danger float-end">Cancel</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Update Users
                            </div>
                            <div class="card-body">
                                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="userID" value="<?= $user['id'] ?>">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Name</label>
                                    <input type="name" name="name" class="form-control" id="exampleFormControlInput1" value="<?= $user['name'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="exampleFormControlInput1" value="<?= $user['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="exampleFormControlInput1" value="<?= $user['password'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Images</label>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-tab-pane" type="button" role="tab" aria-controls="image-tab-pane" aria-selected="true">image</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="new_image-tab" data-bs-toggle="tab" data-bs-target="#new_image-tab-pane" type="button" role="tab" aria-controls="new_image-tab-pane" aria-selected="false">New Image</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">\
                                            <img src="<?= $user['profile'] ?>" alt="" width="300" height="200" class="py-3">
                                            <input type="hidden" name="old_photo" id="" value="<?= $user['profile'] ?>">
                                        </div>
                                        <div class="tab-pane fade" id="new_image-tab-pane" role="tabpanel" aria-labelledby="new_image-tab" tabindex="0">
                                            <input class="form-control my-3" type="file" id="formFile" name="profile">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
<?php 
    include "layouts/footer.php";
    }else{
        header('Location: ../index.php');
    }
?>
