<?php 

session_start();

    if(isset($_SESSION['user_id'])){
    include "../dbconnect.php";


    if($_SERVER['REQUEST_METHOD']=='POST'){
        $id = $_POST['userID'];
        // echo $id;
        $sql = "DELETE FROM users WHERE id = :user_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':user_id',$id);
        $stmt -> execute();

        header('Location: users.php');
    }else{
        include "layouts/nav_sidebar.php";
        $sql = "SELECT * FROM users";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
        $users_data = $stmt -> fetchAll();
    }

?>
                <main>  
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Users</h1>
                            <a href="create_user.php" class=" btn btn-primary float-end">Create Users</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
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
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>

                                    </tfoot>
                                    <tbody>
                                        <?php
                                            foreach($users_data as $user){
                                        ?>    
                                            <tr>
                                                <th><?= $user['id'] ?></th>
                                                <th><?= $user['name'] ?></th>
                                                <th>
                                                    <a href="../detail.php" class="btn btn-sm btn-outline-primary">Detail</a>
                                                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete" data-user_id="<?= $user['id'] ?>">Delete</a>
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
            <input type="hidden" name="userID" id="userID" value="">
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
