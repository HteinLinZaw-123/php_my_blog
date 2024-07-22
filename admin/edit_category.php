<?php 

    session_start();
    if(isset($_SESSION['user_id'])){

    require "../dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['categoryID'];
        $name = $_POST['name'];

        echo "$id and $name";

        $sql = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':id',$id);
        $stmt -> bindParam(':name',$name);
        $stmt -> execute();

        header("location: categories.php");
    }else{
        include "layouts/nav_sidebar.php";

        $category_id = $_GET['id'];
        // echo $category_id;

        $sql = "SELECT * FROM categories WHERE id = :category_id";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':category_id',$category_id);
        $stmt -> execute();
        $category = $stmt -> fetch(PDO::FETCH_ASSOC);
        // var_dump($category);

        // $sql = "SELECT * FROM categories";
        // $stmt = $conn -> prepare($sql);
        // $stmt -> execute();
        // $categories = $stmt -> fetchAll();
    }
?>
                <main>
                    <div class="container-fluid px-4">
                        <div class="mt-3">
                            <h1 class="mt-4 d-inline">Create Categories</h1>
                            <a href="create_category.php" class=" btn btn-danger float-end">Cancel</a>
                        </div>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="categories.php">Categories</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Update Categories
                            </div>
                            <div class="card-body">
                                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="categoryID" value="<?= $category['id'] ?>">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Category Name</label>
                                            <input type="text" name="name" class="form-control" id="exampleFormControlInput1" value="<?= $category['name'] ?>">
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
