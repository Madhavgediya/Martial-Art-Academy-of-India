<?php

include("includes/application_top.php");

//set Page Title
$caption = $page_title = "Report Stock";
$errormsg = get_rdata('errormsg', '');
$id = get_rdata("id", 0);
$act = get_rdata("act");
$inv_pro_id = get_rdata("inv_pro_id","");
$qty_status = get_rdata("qty_status","Current Stock");

// Set success message based on msg ID
$msg = get_rdata('msg', '');

$total_rows = 0;
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);

if (isset($_GET['per_page'])) {
    $per_page = $_GET['per_page'];
}
if (isset($_GET['per_page']) && $per_page <= 0) {
    $per_page = PER_PAGE;
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

$order_by = get_rdata('order_by', 'p.pro_name, po.po_name , po1.po_name ');

$order = get_rdata('order', 'asc');
$link_order = 'desc';
$pro_name_arrow =  $pro_status_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'desc') {
    $link_order = 'asc';
    if ($order_by == 'pro_status') {
        $pro_status_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $pro_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
}

$pro_name = get_rdata('pro_name', '');

//searching and pagination

$condition = ' i.inv_status = "G" AND p.pro_admin_id ='.$tmp_admin_id . ' AND po.po_br_id  ='.$tmp_admin_id . ' AND po1.po_br_id  ='.$tmp_admin_id;

if ($inv_pro_id != '') {
    $condition.=" AND ip.invpro_pro_id = " . $inv_pro_id ;
}
/*
      <option value="All" <?php if ($qty_status == 'All'); echo ' selected="selected" '; ?>>All</option>
    <option value="Out of Stock" <?php if ($qty_status == 'Out of Stock'); echo ' selected="selected" '; ?>>Out of Stock</option>
    <option value="Office Item" <?php if ($qty_status == 'Office Item'); echo ' selected="selected" '; ?>>Office Item</option>
    <option value="Current Stock" <?php if ($qty_status == 'Current Stock'); echo ' selected="selected" '; ?>>Current Stock</option>
    <option value="Dead Stock" <?php if
*/
/*
<td class="text-right"><?php echo $productsrow['invpro_final_pro_price']; ?></td>
<td class="text-right"><?php echo $productsrow['invpro_pro_qty']; ?></td>
<td class="text-right"><?php echo $productsrow['invpro_pro_qty_sold']; ?></td>
<td class="text-right"><?php echo $productsrow['invpro_pro_qty_dead']; ?></td>
*/
if ($qty_status == 'Out of Stock')
{
    $condition.=" AND ip.invpro_pro_qty = (ip.invpro_pro_qty_sold+ip.invpro_pro_qty_dead) ";
}
else if ($qty_status == 'Current Stock')
{
    $condition.=" AND ip.invpro_pro_qty > (ip.invpro_pro_qty_sold+ip.invpro_pro_qty_dead) ";
}
else if ($qty_status == 'Dead Stock')
{
    $condition.=" AND ip.invpro_pro_qty_dead !=0 ";
}

if ($qty_status == 'Office Item')
{
    $condition.=" AND ip.invpro_used = 'In office use Yes' ";
}
else 
{
    $condition.=" AND ip.invpro_used = 'NA' ";
}


$condition.=" GROUP BY ip.invpro_pro_id, ip.invpro_final_pro_price , ip.invpro_po_id, ip.invpro_po_id_2";

$select = " cat_name, p.pro_name, po.po_name option1 , ip.invpro_final_pro_price, po1.po_name option2 , SUM(ip.invpro_pro_qty) invpro_pro_qty , SUM(ip.invpro_pro_qty_sold) invpro_pro_qty_sold , SUM(ip.invpro_pro_qty_dead) invpro_pro_qty_dead , ip.invpro_pro_id , ip.invpro_po_id, ip.invpro_po_id_2  ";
$condition.=" order by " . $order_by . ' ' . $order;
$table = " sm_invoice i INNER JOIN sm_invoice_products ip ON (i.inv_id = ip.invpro_inv_id) INNER JOIN sm_product_option po ON (ip.invpro_po_id= po.po_id ) INNER JOIN sm_product_option po1 ON (ip.invpro_po_id_2 = po1.po_id ) INNER JOIN sm_products p ON (p.pro_id = ip.invpro_pro_id) LEFT JOIN sm_category ON (p.pro_cat_id = cat_id)  ";

// echo "SELECT ".$select . " FROM ".$table . " WHERE ". $condition;

$searchField = "&pro_name=" . $pro_name ;  
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 5, "qty_status=" . $qty_status  . "per_page=" . $per_page  . $searchField . "&order by=" . $order_by . "&order=" . $order);
$productsData = $pageObj->paginate();

$total_rows = $pageObj->totRows();

if (isset($_GET['page']) && $_GET['page'] > 0 && ($_GET['page'] > ceil($total_rows / $per_page))) {
    $srNo = 0;
}
$arr_branch_details =  get_branch_details(session_get("id"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
    </head>
    <body class="skin-green sidebar-mini">
        <div class="wrapper">
            <?php include("includes/header.php"); ?>
            <?php include("includes/left_menu.php"); ?>
            <div class="content-wrapper">                
                <!-- Main content -->
                <section class="content-header">
                    <h1>
                        <?php echo $caption; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $caption; ?></li>
                    </ol>
                </section>
                <section class="content">
<?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">                                
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form class="form-horizontal" name="form1" id="form1" method="post">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Product</label>
                                                <div class="col-sm-9">
                                                        <select id="inv_pro_id"  name="inv_pro_id" class="form-control select2">
                                                            <option value="">-Please select-</option>
                                                            <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'pro_name , pro_id';
                                                            $data_arr_input['table'] = ' sm_products ';
                                                            $data_arr_input['where'] = " pro_admin_id = " . $tmp_admin_id ;
                                                            $data_arr_input['key_id'] = 'pro_id';
                                                            $data_arr_input['key_name'] = 'pro_name';
                                                            $data_arr_input['current_selection_value'] = $inv_pro_id;
                                                            $data_arr_input['order_by'] = 'pro_name';
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                        </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                        <select id="qty_status"  name="qty_status" class="form-control select2">
                                                            <option value="All" <?php if ($qty_status == 'All') echo ' selected="selected" '; ?>>All</option>
                                                            <option value="Out of Stock" <?php if ($qty_status == 'Out of Stock') echo ' selected="selected" '; ?>>Out of Stock</option>
                                                            <option value="Office Item" <?php if ($qty_status == 'Office Item') echo ' selected="selected" '; ?>>Office Item</option>
                                                            <option value="Current Stock" <?php if ($qty_status == 'Current Stock') echo ' selected="selected" '; ?>>Current Stock</option>
                                                            <option value="Dead Stock" <?php if ($qty_status == 'Dead Stock') echo ' selected="selected" '; ?>>Dead Stock</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div> 
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                                                                
                                    <button type="button" class="btn btn-warning pull-right" onclick="product_qty_manager_toggle('Dead Stock');" style="margin-left: 5px;">Dead Stock</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'report_stock.php'">Cancel</button>
                                        <button type="submit" class="btn btn-info pull-right">Search</button>
                                        <button type="button" onclick="print_attendance('print_me','<?php echo $arr_branch_details["name"]; ?>','report_stock','');" class="btn btn-warning">Print</button>
                                        
                                    </div><!-- /.box-footer -->
                                </form>                                
                            </div>

                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-xs-12">
                            <div class="box">                                
                                <div class="box-body">
                                        <table  class="table table-bordered table-hover table-condensed"  id="print_me">
                                            <thead>
                                                <tr>
                                                    <th width="5%"><center>Sr.No.</center></th>
                                                    <th class="text-left">Category</th>
                                                    <th class="text-left">Product</th>
                                                    <th width="20%" class="text-left">Option1</th>
                                                    <th class="text-left" width="20%">Option2</th>
                                                    <th class="text-right" width="7%">Price</th>
                                                    <th class="text-right" width="7%">Qty</th>
                                                    <th class="text-right" width="7%">Sold</th>
                                                    <th class="text-right" width="7%">Dead</th>
                                                    <th class="text-right" width="7%">Net Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$class = '';
if ($productsData) {
   
    for ($i = 1; $productsrow = $productsData->fetch(); $i++) {
        $srNo++;
        if ($i % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }
        ?>
            <tr class="<?php echo $class; ?>">
                <td><center><?php echo $srNo; ?></center></td>
                <td style="padding-left:10px;"><?php echo ucfirst($productsrow['cat_name']); ?></td>   
                <td style="padding-left:10px;"><?php echo ucfirst($productsrow['pro_name']); ?></td>                                                        
                <td style="padding-left:10px;"><?php echo ucfirst($productsrow['option1']); ?></td>                                                        
                <td style="padding-left:10px;"><?php echo ucfirst($productsrow['option2']); ?></td>                                                        
                <td class="text-right"><?php echo $productsrow['invpro_final_pro_price']; ?></td>
                <td class="text-right"><?php echo $productsrow['invpro_pro_qty']; ?></td>
                <td class="text-right"><?php echo $productsrow['invpro_pro_qty_sold']; ?></td>
                <td class="text-right"><?php echo $productsrow['invpro_pro_qty_dead']; ?></td>
                <td class="text-right"><?php echo ($productsrow['invpro_pro_qty']-$productsrow['invpro_pro_qty_sold']-$productsrow['invpro_pro_qty_dead']); ?></td>
            </tr>
        <?php
    }
} else {
    echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="5">No records found or you have not permission to access these records.</td></tr>';
}
?>
                                            </tbody>
                                        </table>
                                    
<?php if ($pageObj) { ?>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mt20 mb20">
                                                <?php
                                                $page = 1;
                                                if (isset($_GET['page'])) {
                                                    $page = $_GET['page'];
                                                }
                                                if ($page > 0 && ($page > ceil($total_rows / $per_page))) {
                                                    $f_line = 1;
                                                    $page = 1;
                                                } else {
                                                    $f_line = ($page - 1) * $per_page + 1;
                                                }
                                                ?>
                                                <?php $l_line = $page * $per_page; ?>
                                                Showing
                                                <?php
                                                if ($f_line < $total_rows) {
                                                    echo ($page - 1) * $per_page + 1;
                                                } else {
                                                    echo $total_rows;
                                                }
                                                ?>
                                                to
                                                <?php
                                                if ($l_line < $total_rows) {
                                                    echo $page * $per_page;
                                                } else {
                                                    echo $total_rows;
                                                }
                                                ?>
                                                of <?php echo $total_rows ?> entries
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <div class="pull-right">
                                                    <ul class="pagination">
                                                        <?php echo $pageObj->renderFullNav(); ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>               
            </div>
            <script type="text/javascript">
            $('#inv_pro_id').select2();
            </script>
    <?php include("includes/product_qty_manager.php"); ?>        
    <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>