<?php 

session_start();
$product_ids = array();

$connect = mysqli_connect('localhost', 'root', '', 'cart');
mysqli_set_charset($connect, "utf8mb4");

if(filter_input(INPUT_POST, 'add_to_cart'))
{

    if(isset($_SESSION['shopping_cart']))
    
    {

        $count = count($_SESSION['shopping_cart']);

        $product_ids = array_column($_SESSION['shopping_cart'],'id');

        if(!in_array(filter_input(INPUT_GET,'id'), $product_ids)){

            $_SESSION['shopping_cart'][$count] = array (

                'id'   =>  filter_input(INPUT_GET,'id'),
                'name'   =>  filter_input(INPUT_POST,'name'),
                'price'   =>  filter_input(INPUT_POST,'price'),
                'quantity'   =>  filter_input(INPUT_POST,'quantity')
            );
        }
        else
        {
            for ($i = 0; $i < count($product_ids); $i++)
            {
                if ($product_ids[$i] == filter_input(INPUT_GET,'id'))
                {
                    $_SESSION['shopping_cart'][$i]['quantity'] += filter_input(INPUT_POST,'quantity');
                }
            }
        }
    }

    else
    {
        $_SESSION['shopping_cart'][0] = array
        (
            'id'   =>  filter_input(INPUT_GET,'id'),
                'name'   =>  filter_input(INPUT_POST,'name'),
                'price'   =>  filter_input(INPUT_POST,'price'),
                'quantity'   =>  filter_input(INPUT_POST,'quantity')
        );
    }
    
}

//removing products

if(filter_input(INPUT_GET,'action'))
{
    if(filter_input(INPUT_GET,'action') == 'delete')
    {
        foreach($_SESSION['shopping_cart'] as $key => $product)
        {
            if($product['id'] == filter_input(INPUT_GET,'id'))
            {
                unset($_SESSION['shopping_cart'][$key]);
            }
        }

        $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
    }
}



function pre_r($array)
{
    echo "<pre>";
    print_r($array);
    echo "<pre>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="css">
</head>

<body>
    <br />
    <div class="container">
        <div class="row">

            <?php

$query = 'SELECT * FROM products ORDER BY id ASC';
$result = mysqli_query($connect, $query);
if ($result):
    if(mysqli_num_rows($result)>0):
        while($product = mysqli_fetch_array($result)):

?>



            <?php
endwhile;
endif;
endif;
?>

        </div>

        <div style="clear:both"></div>
        <br />
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th colspan="5">
                        <h3>Order Details</h3>
                    </th>
                </tr>
                <tr>
                    <th width="40%">Product Name</th>
                    <th width="10%">Quantity</th>
                    <th width="20%">Price</th>
                    <th width="15%">Total</th>
                    <th width="5%">Action</th>
                </tr>

                <?php 
    if(!empty($_SESSION['shopping_cart']));

    $total = 0;

    foreach($_SESSION['shopping_cart'] as $key => $product):
        ?>
                <tr>
                    <td>
                        <?php echo $product['name']; ?>
                    </td>
                    <td>
                        <?php echo $product['quantity']; ?>
                    </td>
                    <td>$
                        <?php echo $product['price']; ?>
                    </td>
                    <td>$
                        <?php echo number_format($product['quantity'] * $product['price'], 2); ?>
                    </td>
                    <td>
                        <a href="index.php?action=delete&id=<?php echo $product['id']; ?>">
                            <div class="btn-danger">Remove</div>
                        </a>
                    </td>
                </tr>

                <?php 
        
        $total = $total + ($product['quantity'] * $product['price']);
        
    endforeach;
        ?>

                <tr>
                    <td colspan="3" align="right">Total</td>
                    <td align="right">$
                        <?php echo number_format($total,2); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <?php 
        if(isset($_SESSION['shopping_cart']));
        if(count($_SESSION['shopping_cart']) > 0);
        
        ?>

                        <a href="" class="button">Checkout</a>

                        <?php 
        endif; endif;

        ?>
                    </td>
                </tr>

                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>