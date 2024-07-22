<?php 
session_start();

    if(isset($_SESSION['user_id'])){
    include "../dbconnect.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $id = $_POST['postID'];
        // echo $id;
        $sql = "DELETE FROM posts WHERE id = :post_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':post_id',$id);
        $stmt -> execute();

        header('Location: posts.php');
    }else{
        include "layouts/nav_sidebar.php";
        $sql = "SELECT posts.*, users.name as user_name, categories.name as category_name  FROM posts INNER JOIN users ON posts.user_id = users.id INNER JOIN categories ON posts.category_id = categories.id";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
        $posts = $stmt -> fetchAll();
    }


?>
                <main>
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Posts</h1>
                            <a href="create_post.php" class=" btn btn-primary float-end">Create Post</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Posts</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Posts
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>User</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                            <tr>
                                                <th>Title</th>
                                                <th>User</th>
                                                <th>Category</th>
                                                <th>Action</th>
                                            </tr>

                                    </tfoot>
                                    <tbody>
                                        <?php
                                            foreach($posts as $post){
                                        ?>    
                                            <tr>
                                                <th><?= $post['title'] ?></th>
                                                <th><?= $post['user_name'] ?></th>
                                                <th><?= $post['category_name'] ?></th>
                                                <th>
                                                    <a href="../detail.php" class="btn btn-sm btn-outline-primary">Detail</a>
                                                    <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete" data-post_id="<?= $post['id'] ?>">Delete</a>
                                                </th>
                                            </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-danger">Are u sure to delete?</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <form action="" method="POST">
            <input type="hidden" name="postID" id="postID" value="">
            <button type="submit" class="btn btn-primary">YES</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
    include "layouts/footer.php";
    }else{
        header('location: ../index.php');
    }
?>
