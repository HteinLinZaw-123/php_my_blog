<?php 

    session_start();
    if(isset($_SESSION['user_id'])){

    require "../dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // var_dump($_FILES);
        $id = $_POST['postID'];
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $user_id = $_SESSION['user_id'];
        $description = $_POST['description'];
        $old_photo = $_POST['old_photo'];

        echo "$id and $title and $category_id and $description and $old_photo";

        // File Upload
        $image_array = $_FILES['image'];
        var_dump($image_array);
        if(isset($image_array) && $image_array['size'] > 0){
            $folder_name = 'images/';
            $image_path = $folder_name.$image_array['name'];

            $tmp_name = $image_array['tmp_name'];
            move_uploaded_file($tmp_name,$image_path);
        }else{
            $image_path = $old_photo;
        }
        $sql = "UPDATE posts SET title = :title, image = :image_path, description = :description, category_id = :category_id, user_id = :user_id WHERE id = :id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':id',$id);
        $stmt -> bindParam(':title',$title);
        $stmt -> bindParam(':image_path',$image_path);
        $stmt -> bindParam(':description',$description);
        $stmt -> bindParam(':category_id',$category_id);
        $stmt -> bindParam(':user_id',$user_id);
        $stmt -> execute();

        header("location: posts.php");
    }else{
        include "layouts/nav_sidebar.php";

        $post_id = $_GET['id'];
        // echo $post_id;

        $sql = "SELECT * FROM posts WHERE id = :post_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':post_id',$post_id);
        $stmt -> execute();
        $post = $stmt -> fetch(PDO::FETCH_ASSOC);
        // var_dump($post);

        $sql = "SELECT * FROM categories";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
        $categories = $stmt -> fetchAll();
    }
?>
                <main>
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Create Posts</h1>
                            <a href="create_post.php" class=" btn btn-danger float-end">Cancel</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="posts.php">Posts</a></li>
                            <li class="breadcrumb-item active">Posts</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Create Posts
                            </div>
                            <div class="card-body">
                                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="postID" value="<?= $post['id'] ?>">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?= $post['title'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Categories</label>
                                    <select class="form-select" aria-label="Default select example" name="category_id">
                                        <option selected>Choose...</option>
                                        <?php
                                            foreach($categories as $category){
                                                
                                        ?>
                                        <option value="<?= $category['id'] ?>"<?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
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
                                        <img src="<?= $post['image'] ?>" alt="" width="300" height="200" class="py-3">
                                        <input type="hidden" name="old_photo" id="" value="<?= $post['image'] ?>">
                                    </div>
                                    <div class="tab-pane fade" id="new_image-tab-pane" role="tabpanel" aria-labelledby="new_image-tab" tabindex="0">
                                        <input class="form-control my-3" type="file" id="formFile" name="image">
                                    </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" rows="3" name="description"><?= $post['description'] ?></textarea>
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
