<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<?php
$cart = unserialize($_SESSION['cart']);
$cart_item_count = 0;
if(isset($cart)){
    $cart_item_count = $cart->item_count;
} ?>

<h2>Cart Details - <?php echo $cart_item_count; ?></h2>

<table>
  <tr>
    <th>Stand No</th>
    <th>Product Name</th>
    <th>Rate</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Action</th>
  </tr>
<?php
if(isset($cart)){
    for ($i = 0; $i < sizeof($cart->cart_items); $i++) { ?>
    <tr>
    <td><b><?php echo $cart->cart_items[$i]->additional_info->stand_no; ?></b></td>
    <td><?php echo $cart->cart_items[$i]->product_name; ?></td>
    <td><?php echo $cart->cart_items[$i]->rate; ?></td>
    <td><?php echo $cart->cart_items[$i]->quantity; ?></td>
    <td><?php echo $cart->cart_items[$i]->total; ?></td>
    <td>
       <form action="/cart/removeitem" method="post">
            <input type='hidden' id='cart_item_id' name='cart_item_id' value='<?php echo $cart->cart_items[$i]->cart_item_id; ?>' />
            <input type='hidden' id='cart_id' name='cart_id' value='<?php echo $cart->cart_id; ?>' />
            <input type='hidden' id='customer_id' name='customer_id' value='<?php echo $cart->customer_id; ?>' />
            <input type='submit' value='Remove' />
       </form>
    </td>
  </tr>
<?php  }
} ?>
<tr>
    <td colspan="3"></td>
    <td><b>Sub-Total</b></td>
    <td><?php echo $cart->total; ?></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="5"></td>
    <td><a href="/stand/reserve">Proceed to reserve</a></td>
  </tr>


</table>