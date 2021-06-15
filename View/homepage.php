<?php require 'includes/header.php' ?>


<body>
  <div class="container">
    <div class="row">
      <div class="col-sm products">
        <!-- select customer checkout -->
        <div class="row">
          <div class="col-6">
            <form method="post">
              <label for="customer-select">Choose a customer:</label>
              <select name="customer-select" id="customer-select">
                <?php foreach ($customers as $customer):
                    $customerObj = new Customer(
                        $customer['firstname'],
                        $customer['lastname'],
                        (int)$customer['fixed_discount'],
                        (int)$customer['variable_discount'],
                        (int)$customer['id'],
                        (int)$customer['group_id']
                    );
                    ?>
                <option value=" <?php echo htmlspecialchars($customerObj->getId()) ?>">
                  <?php echo htmlspecialchars($customerObj->getFullName())?>
                </option>
                <?php endforeach;?>
                <input type="submit" value="Submit">
              </select>
            </form>
          </div>
        </div>

        <!-- products -->
        <table class="products table table-striped table-wide ">
          <thead>
            <tr>
              <th width="40%">Product</th>
              <th width="40%">Price</th>
              <td colspan="2" width="20%"></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
              <td><?php echo htmlspecialchars($product->getName()) ?></td>
              <td><?php echo '€' . htmlspecialchars($product->getPrice()) ?></td>
              <td>
                <a href="" class="btn btn-success">Add</a>
              </td>
              <td>
                <form method="post">
                  <input type="hidden" name="id" value="" />
                  <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- total checkout -->
        <table class="total table table-striped table-wide">
          <thead>
            <tr>
              <th width="40%">Added products</th>
              <th width="40%">Total Price</th>
              <td colspan="2" width="20%"></td>
            </tr>
          </thead>
          <tbody>

            <tr>
              <td>Mouse pad</td>
              <td>54.18€</td>
              <td>
                <form class="checkout" method="post">
                  <input type="hidden" name="id" value="" />
                  <input type="submit" name="delete" value="Checkout" class="btn btn-primary">
                </form>
              </td>
            </tr>

          </tbody>
        </table>

      </div>
    </div>
</body>
<?php
require 'includes/footer.php' ?>