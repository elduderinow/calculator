<?php require 'includes/header.php' ?>

    <body>
    <div class="container">
        <div class="display-1 text-center">€<?php echo $finalPrice ?></div>
        <div class="row">
            <div class="col-sm products">
                <!-- select customer checkout -->
                <div class="row">
                    <div class="col-12">
                        <form method="post">
                            <label for="customer-id">Choose a customer:</label>
                            <select name="customer-id" id="customer-id">
                                <?php foreach ($customers as $customer): ?>
                                    <option <?php
                                    if (isset($_SESSION['customer-id'])) {
                                        echo($_SESSION['customer-id'] == $customer->getId() ? 'selected' : '');
                                    }
                                    ?>
                                            value=<?php echo '{"id":' . $customer->getId() . ',"groupId":' . $customer->getGroupId() . '}' ?>>
                                        <?php echo htmlspecialchars($customer->getFullName()) ?>
                                    </option>
                                <?php endforeach; ?>
                                <input type="submit" value="Update">
                            </select>
                        </form>
                        <!-- total checkout -->
                        <table class="total table table-striped table-wide">
                            <thead>
                            <tr>
                                <th width="25%">Added products</th>
                                <th width="25%">Amount</th>
                                <th width="25%">Price</th>
                                <th width="25%">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($checkoutProducts as $key => $checkProd): ?>
                                <tr>
                                    <td><?php echo $checkProd->getName(); ?></td>
                                    <td>1x</td>
                                    <td><?php echo '€' . $checkProd->getPrice(); ?></td>
                                    <td>1x</td>
                                    <td>
                                        <form method="get">
                                            <input type="hidden" name="id" value="<?php echo $key ?>"/>
                                            <input type="submit" name="button" value="Delete" class="btn btn-danger">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
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
                                <form method="get">
                                    <input type="hidden" name="id" value="<?php echo $product->getId() ?>"/>
                                    <input type="submit" name="button" value="Add" class="btn btn-success">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <td colspan="3" width="20%"">
                        <form method="get">
                            <div class="leftbut"style="float:left">
                                <input type="hidden" name="pagval" value=""/>
                                <input type="submit" name="leftbut" value="&#8592;" class="btn btn-primary">
                            </div>
                            <div class="rightbut" style="float:right">
                                <input type="hidden" name="pagval" value=""/>
                                <input type="submit" name="rightbut" value="&#8594;" class="btn btn-primary">
                            </div>
                        </form>
                    </td>
                    </tbody>
                </table>

            </div>
        </div>
    </body>
<?php
require 'includes/footer.php'
?>