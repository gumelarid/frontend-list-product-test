<?php
session_start();
$totalData = 0;
if (isset($_GET['keyword'])) {
  $keyword = $_GET['keyword'];
  $url = 'https://nutech.creativibe.site/cari?keyword='.$keyword;
}else{
  $page = 1;
  $url = "https://nutech.creativibe.site/all";
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $url = 'https://nutech.creativibe.site/all?page='.$page;
  }
}
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);
if ($result['status'] == 200) {
  $totalData = isset($result['pagination']) ? $result['pagination']['totalData'] : count($result['data']);
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body class="text-bg-info my-4">
    <div class="container p-4" style="background-color: white;">
      <div class="text-center my-4">
        <h1>List Product</h1>
      </div>

      <!-- Button trigger modal -->
      <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add
          </button>
        <div class="d-flex">
          <form action="index.php" method="GET">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="keyword" placeholder="Search name" aria-label="Search name" aria-describedby="button-addon2">
                <button class="btn btn-sm btn-outline-secondary" type="submit" id="button-addon2">Search</button>
              </div>
            </form>
            <form action="index.php">
              <div class="input-group mb-3">
                <button class="btn btn-md btn-outline-secondary" type="submit" id="button-addon2">Reset</button>
              </div>
            </form>
        </div>
      </div>
      

      <!-- Modal add-->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Form Add Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="create.php" method="POST" id="link" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Product Name<span>*</span></label>
                <input type="string" name="product_name" class="form-control" >
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Image<span>*</span></label>
                <div id="Help" class="form-text">Please upload an image with a png or jpg, file size max 100kb</div>
                <input type="file" name="product_picture" class="form-control" aria-describedby="Help">
               
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Price<span>*</span></label>
                <input type="number" name="product_price" class="form-control">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product sale price<span>*</span></label>
                <input type="number" name="product_sale" class="form-control">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Stock<span>*</span></label>
                <input type="number" name="product_stock" class="form-control">
              </div>
              <input type="submit" class="btn btn-primary" value="Save">
            </form>
            </div>
          </div>
        </div>
      </div>

      <?php if(isset($_SESSION['status'])) { ?>
        <?php if($_SESSION['status'] === 300) { ?> 
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul>
                <?php if(is_array($_SESSION['success_mg'])) { ?>
                  <?php foreach ($_SESSION['success_mg'] as $value) { ?>
                      <?php if (count($value) == 1) { ?>
                            <li> <?php echo $value[0] ?></li>
                      <?php  }else{ ?>
                          <?php foreach ($value as $alert) { ?>
                            <li><?php echo $alert ?></li>
                          <?php } ?>
                      <?php } ?>
                  <?php  } ?>
                <?php  }else{ ?>
                    <li> <?php echo $_SESSION['success_mg'] ?></li>
                <?php  } ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }else{ ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?php echo $_SESSION['success_mg'] ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
      <?php } ?>

      <table class="table table-responsive">
        <thead class="text-bg-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Picture</th>
            <th scope="col">Price</th>
            <th scope="col">Sale Price</th>
            <th scope="col">Stock</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <?php if (isset($result['data'])) { ?>
          <tbody>
            <?php $no = 1; foreach($result['data'] as $list) { ?>
            <tr>
              <th scope="row"><?php echo $no++ ?></th>
              <td><?php echo $list['product_name'] ?></td>
              <td>
                <img src="https://nutech.creativibe.site/uploads/<?php echo $list['product_picture'] ?>" alt="picture" class="img-thumbnail" style="max-width: 200px;">
              </td>
              <td><?php echo "Rp " . number_format($list['product_price'],2,',','.') ?></td>
              <td><?php echo "Rp " . number_format($list['product_sale'],2,',','.') ?></td>
              <td><?php echo $list['product_stock'] ?></td>
              <td>
                  <button type="button" class="btn btn-sm btn-info" id="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal"
                      data-id="<?php echo $list['product_id']; ?>"
                      data-name="<?php echo $list['product_name']; ?>"
                      data-picture="<?php echo $list['product_picture']; ?>"
                      data-sold="<?php echo $list['product_sale']; ?>"
                      data-stock="<?php echo $list['product_stock']; ?>"
                      data-price="<?php echo $list['product_price']; ?>"
                  >Edit</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $list['product_id']; ?>">Delete</button>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        <?php } ?>
      </table>
      <p>Total Data : <?php echo $totalData ?></p>
      <?php if (isset($result['pagination'])) { ?>
        <nav>
          <ul class="pagination justify-content-center">
            <li class="page-item">
              <a class="page-link" <?php if($result['pagination']['page'] > 1){ echo "href='?page=1'"; } ?>>First Page</a>
            </li>
            <?php 
            for($x=1;$x<=$result['pagination']['totalPage'];$x++){
              ?> 
              <li class="page-item"><a class="page-link" href="?page=<?php echo $x ?>"><?php echo $x; ?></a></li>
              <?php
            }
            ?>				
            <li class="page-item">
              <a  class="page-link" <?php if($result['pagination']['page'] < $result['pagination']['totalPage']) { $last = $result['pagination']['totalPage']; echo "href='?page=$last'"; } ?>>Last Page</a>
            </li>
          </ul>
        </nav>
      <?php } ?>
      
    </div>

    <!-- Modal edit-->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Form Update Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="update.php" method="POST" id="link" enctype="multipart/form-data">
              <input type="hidden" name="product_id" id="id">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Product Name<span>*</span></label>
                <input type="string" name="product_name" class="form-control" id="product" >
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Image<span>*</span></label>
                <div id="Help" class="form-text">Please upload an image with a png or jpg, file size max 100kb</div>
                <input type="file" name="product_picture" class="form-control" id="file" aria-describedby="Help">
               
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Price<span>*</span></label>
                <input type="number" name="product_price" class="form-control" id="price">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product sale price<span>*</span></label>
                <input type="number" name="product_sale" class="form-control" id="sold">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Product Stock<span>*</span></label>
                <input type="number" name="product_stock" class="form-control" id="stock">
              </div>
              <input type="submit" class="btn btn-primary" id="btn-product" value="Update">
            </form>
            </div>
          </div>
        </div>
      </div>


    <!-- delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <p class="my-4">Are your sure delete this product !</p>
          <form action="delete.php" method="POST" id="link" enctype="multipart/form-data">
              <input type="hidden" name="product_id" id="id_pd">
              <input type="submit" class="btn btn-primary" id="btn-product" value="Delete">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <script>
        $('#editModal').on('show.bs.modal', function (e) {
          let btn  = $(e.relatedTarget)
          let id = btn.data('id')
          let product = btn.data('name')
          let stock = btn.data('stock')
          let price = btn.data('price')
          let sold = btn.data('sold')
          
          // set data to form
          var modal = $(this)

          //variabel di atas dimasukkan ke dalam element yang sesuai dengan idnya masing-masing
          modal.find('#id').val(id)
          modal.find('#product').val(product)
          modal.find('#stock').val(stock)
          modal.find('#sold').val(sold)
          modal.find('#price').val(price)
        });

        $('#deleteModal').on('show.bs.modal', function (e) {
          let btn  = $(e.relatedTarget)
          let id = btn.data('id')
          // set data to form
          var modal = $(this)

          //variabel di atas dimasukkan ke dalam element yang sesuai dengan idnya masing-masing
          modal.find('#id_pd').val(id)
        });
    </script>
   
  </body>
</html>


<?php
session_destroy();
?>
