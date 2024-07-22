<?php 

session_start();

    if(isset($_SESSION['user_id'])){
    include "../dbconnect.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $id = $_POST['categoryID'];
        echo $id;
        $sql = "DELETE FROM categories WHERE id = :category_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':category_id',$id);
        $stmt -> execute();

        header('Location: categories.php');
    }else{
        include "layouts/nav_sidebar.php";
        $sql = "SELECT * FROM categories";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute();
        $categories_data = $stmt -> fetchAll();
    }

?>
                <main>  
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Categories</h1>
                            <a href="create_category.php" class=" btn btn-primary float-end">Create Categories</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Categories</li>
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
                                            foreach($categories_data as $category){
                                        ?>    
                                            <tr>
                                                <th><?= $category['id'] ?></th>
                                                <th><?= $category['name'] ?></th>
                                                <th>
                                                    <a href="../detail.php" class="btn btn-sm btn-outline-primary">Detail</a>
                                                    <a href="edit_category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete" data-category_id="<?= $category['id'] ?>">Delete</a>
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
            <input type="hidden" name="categoryID" id="categoryID" value="">
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
