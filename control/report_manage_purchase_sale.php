<?php
add_log_txt($c_file . '--' . json_encode($_REQUEST));

$msg = get_rdata('msg', '');
$inv_del_id = explode('-', get_rdata('inv_purchase_del_id'));
if(count($inv_del_id) > 0 && in_array($inv_del_id[0],['S','C','O'])) {
    $inv_purchase_del_id = $inv_del_id[1];
} else {
    $inv_purchase_del_id = get_rdata('inv_purchase_del_id', '');
}

if (isset($msg) && $msg == 1) {
    $successmsg = $page_type." has been deleted successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = $page_type." has been added successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = $page_type." has been updated successfully";
}

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

$order_by = get_rdata('order_by', 'inv_id');
$order = get_rdata('order', 'DESC');
$link_order = 'desc';
$inv_generate_date_arrow = $inv_parent_arrow = $inv_status_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'desc') {
    $link_order = 'asc';
    if ($order_by == 'inv_status') {
        $inv_status_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $inv_generate_date_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if ($act == 'delete' && $id != 0) {
    if ($errormsg == '') {
        // check for allow to deactive order process.
        /*$cquery = "UPDATE $table_invoice SET inv_status = 'N' WHERE inv_id = " . $id;
        $inv_result = m_process('update', $cquery);*/

        $cquery = "DELETE FROM $table_invoice WHERE inv_id = ".$id ;
        $inv_result = m_process("delete", $cquery);

        if ($inv_result['status'] == 'failure') {
            $errormsg = $inv_result['errormsg'];
        } else {
            delete_product_order($id,$page_type);
            $successmsg = 'Purchase has been deleted successfully.';
        }
        if ($page_type == "Sale") {
            mark_sales_as_inactive($id);
            $invpro_id_purchase_string = update_invoice_product_stock("0", $id);
            if ($invpro_id_purchase_string !='')
            {
                    $res_update_product_stock = update_purchase_order($invpro_id_purchase_string.",");
            }
            remove_invoice_product_details_all_clean();
        }
        
        // update_invoice_product_stock($none_removal_ids, $invpro_inv_id)
        // make as delette as yes
    }
}


$inv_ref_no = get_rdata('inv_ref_no', '');
$inv_status = get_rdata('inv_status', 'All');
$export_data = get_rdata('export_data','');
$query_2 =  $select_2 = $condition_2 = $table_2 = $condition_3 = $table_3 = "";
//searching and pagination
$condition = 'I.inv_admin_id='.$tmp_admin_id. ' AND ';
if ($page_type == "Sale") {
    $condition_2 = 'I.inv_admin_id='.$tmp_admin_id. ' AND ';
}
if ($page_type == "Purchase") {
    $condition .= ' I.inv_purchase_del_id = D.del_id AND I.inv_status = "G" ';
}
else {
    $condition .= ' I.inv_purchase_del_id = D.stu_id AND I.inv_status = "G" ';
    $condition_2 .= ' I.inv_purchase_del_id = D.del_id AND I.inv_status = "G" ';
    $condition_3 .= ' I.inv_purchase_del_id = D.stu_id AND I.inv_status = "G" ';
}
if ($inv_ref_no != '') {
    $condition.=" AND I.inv_purchase_invoice_no LIKE '%" . $inv_ref_no . "%'";
    $condition_2 .=" AND I.inv_purchase_invoice_no LIKE '%" . $inv_ref_no . "%'";
    $condition_3 .=" AND I.inv_purchase_invoice_no LIKE '%" . $inv_ref_no . "%'";
}
if ($page_type == "Purchase")
{
    $select = " I.inv_invd_id, D.del_igst igst , D.del_company_name cname , D.del_first_name first_name , D.del_last_name last_name, D.del_phone phone  , I.* ";
    if ($inv_purchase_del_id != '') {
        add_log_txt($c_file . '-- THIS LINE RUN');
        $condition.=" AND I.inv_purchase_del_id = '" . $inv_purchase_del_id . "'";
        $condition_2 .=" AND I.inv_purchase_del_id = '" . $inv_purchase_del_id . "'";
        $condition_3 .=" AND I.inv_purchase_del_id = '" . $inv_purchase_del_id . "'";
    }
}
else
{
    $select = " '' igst ,I.inv_invd_id, D.stu_gr_no cname , D.stu_first_name first_name, D.stu_last_name last_name , D.stu_phone phone  , I.* ";
    $condition .=' AND I.inv_sale_type = "S" ';
    if ($inv_purchase_del_id != '') {
        $sctype = explode('-', get_rdata('inv_purchase_del_id'));
        if($sctype[0] == 'S') {
            $condition.=" AND I.inv_purchase_del_id = '" . $sctype[1] . "'";
        } else {
            $condition.=" AND I.inv_purchase_del_id = ''";
        }
    } 

        $select_2 = " '' igst ,I.inv_invd_id, D.del_company_name cname , D.del_first_name first_name, D.del_last_name last_name , D.del_phone phone  , I.* ";
        $condition_2 .=' AND I.inv_sale_type = "C" ';
        if ($inv_purchase_del_id != '') {
            $sctype = explode('-', get_rdata('inv_purchase_del_id'));
            if($sctype[0] == 'C') {
                $condition_2 .=" AND I.inv_purchase_del_id = '" . $sctype[1] . "'";
            } else {
                $condition_2.=" AND I.inv_purchase_del_id = ''";
            }
        }

        $select_3 = " '' igst ,I.inv_invd_id, D.stu_gr_no cname , D.stu_first_name first_name, D.stu_last_name last_name , D.stu_phone phone  , I.* ";
        $condition_3 .=' AND I.inv_sale_type = "O" ';
        if ($inv_purchase_del_id != '') {
            $sctype = explode('-', get_rdata('inv_purchase_del_id'));
            if($sctype[0] == 'S') {
                $condition_3 .=" AND I.inv_purchase_del_id = '" . $sctype[1] . "'";
            } else {
                $condition_3 .=" AND I.inv_purchase_del_id = ''";
            }
        }
    }



$order_by_q =  " order by " . $order_by . ' ' . $order;
$table = " $table_invoice I, $table_dealer_student D ";

$searchField = "&inv_ref_no=" . $inv_ref_no . "&inv_status=" . $inv_status . "&inv_purchase_del_id=" . $inv_purchase_del_id;


if ($page_type == "Purchase") 
{
    $full_query = "SELECT ".$select . " FROM ".$table. " WHERE ".$condition. $order_by_q ;    
}
else
{
    
    $table_2 = " $table_invoice I, sm_customer D ";
    $table_3 = " $table_invoice I, sm_student_other D ";
    $full_query_2 = $full_query = "SELECT ".$select . " FROM ".$table. " WHERE ".$condition. " UNION SELECT ".$select_2. " FROM ".$table_2. " WHERE ".$condition_2. " UNION SELECT ".$select_3 . " FROM ".$table_3. " WHERE ".$condition_3." ". $order_by_q;
}

$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 5, "per_page=" . $per_page . $searchField . "&order by=" . $order_by . "&order=" . $order,$full_query);
$invoiceData = $pageObj->paginate();
$total_rows = $pageObj->totRows();
add_log_txt($c_file . '--' . $pageObj->sql);
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
               <section class="content-header">
                    <h1>
                        <?php echo $caption; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $caption; ?></li>
                    </ol>
                </section>
                <!-- Main content -->
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
                                                <label class="col-sm-3 control-label">Invoice No</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="inv_ref_no" id="inv_ref_no"  placeholder="Enter Invoice No" title="Purchase Ref. No." value="<?php echo $inv_ref_no; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            if ($page_type == "Purchase") {
                                            ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Dealer</label>
                                                    <div class="col-sm-9">
                                                        <select required id="inv_purchase_del_id" name="inv_purchase_del_id" class="form-control">
                                                            <option value="0">--Please select--</option>
                                                            <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'CONCAT(del_company_name, " (",del_first_name," ",del_last_name, ")") as del_name,del_id';
                                                            $data_arr_input['table'] = 'sm_dealer';
                                                            $data_arr_input['where'] = " del_status  = 'A' ";
                                                            $data_arr_input['key_id'] = 'del_id';
                                                            $data_arr_input['key_name'] = 'del_name';
                                                            $data_arr_input['current_selection_value'] = $inv_purchase_del_id;
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        ?>

                                        <?php 
                                            if ($page_type == "Sale") {
                                            ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Student/Customer</label>
                                                    <div class="col-sm-9">
                                                        <select required id="inv_purchase_del_id" name="inv_purchase_del_id" class="form-control">
                                                            <option value="0">--Please select--</option>

                                                            <?php
                                                            // student
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = " CONCAT(stu_gr_no,'-',stu_first_name,' ', stu_last_name) as stu_name, CONCAT('S-',stu_id) as stu_id ";
                                                            $data_arr_input['table'] = 'sm_student';
                                                            $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id;
                                                            $data_arr_input['key_id'] = 'stu_id';
                                                            $data_arr_input['key_name'] = 'stu_name';
                                                            if($inv_del_id[0] == 'S') {
                                                                $data_arr_input['current_selection_value'] = 'S-'.$inv_purchase_del_id;
                                                            }
                                                            $data_arr_input['order_by'] = ' stu_gr_no, stu_first_name, stu_last_name';
                                                            display_dd_options($data_arr_input);

                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = " CONCAT(stu_gr_no,'-',stu_first_name,' ', stu_last_name) as stu_name, CONCAT('O-',stu_id) as stu_id ";
                                                            $data_arr_input['table'] = 'sm_student_other';
                                                            $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id;
                                                            $data_arr_input['key_id'] = 'stu_id';
                                                            $data_arr_input['key_name'] = 'stu_name';
                                                            if ($inv_sale_type == 'O') {
                                                                $data_arr_input['current_selection_value'] = 'O-'.$inv_purchase_del_id;
                                                            } 
                                                            $data_arr_input['order_by'] = ' stu_gr_no, stu_first_name, stu_last_name';
                                                            display_dd_options($data_arr_input);

                                                            // customer
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'del_company_name, CONCAT("C-",del_id) as del_id ';
                                                            $data_arr_input['table'] = 'sm_customer';
                                                            $data_arr_input['where'] = " del_br_id = " . $tmp_admin_id . " AND del_status  = 'A' ";
                                                            $data_arr_input['key_id'] = 'del_id';
                                                            $data_arr_input['key_name'] = 'del_company_name';
                                                            if ($inv_del_id[0] == 'C') {
                                                                $data_arr_input['current_selection_value'] = 'C-' . $inv_purchase_del_id;
                                                            }
                                                            $data_arr_input['order_by'] = 'del_company_name';
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        ?>
                                        
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = '<?php echo $manage_url; ?>'">Cancel</button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Export <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="print_attendance('example2', '<?php echo $arr_branch_details["name"]; ?>', '<?php echo $page_type.'Report';?>', '');">Export to PDF</a></li>
                                                <li><a href="#" onclick="exportToExcel()">Export to Excel</a></li>
                                            </ul>
                                        </div>
                                        <!-- <button type="button" style="float:right;" class="btn btn-success" onclick="window.location.href='<?php echo $manage_add;?>'">ADD</button> -->
                                        <?php
                                        if ($page_type == 'Purchase') 
                                        {
                                            ?>
                                                    
                                            <?php }      ?>
                                            
                                    </div><!-- /.box-footer -->
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                        <ul class="nav nav-tabs">
                                <li><a href="report_purchase.php?page_type=Purchase" class="btn btn-default">Purchase</a></li>
                                <li><a href="report_sale.php?page_type=Sale" class="btn btn-default">Sale</a></li>
                               
                            </ul>
                            <div class="box">
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                <th width="5%"><center>Sr No</center></th>
                                                    <!-- <th><center><a href="<?php echo $manage_url; ?>?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&<?php echo $searchField; ?>&order_by=inv_generate_date&order=<?php echo $link_order; ?>">Purchase Ref No <span class="<?php echo $inv_generate_date_arrow; ?>"></span></a></center></th> -->
                                                    <th><center>Invoice No</center></th>
                                                    <th width="15%"><center>Name</center></th>
                                                    <th width="15%"><center>Company</center></th>
                                                    <th width="10%"><center>Invoice Date</center></th>
                                                    <th width="10%"><center>Amount</center></th>
                                                    <!-- <th width="10%"><center>Action</center></th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $class = '';
                                                if ($invoiceData) {
                                                    for ($i = 1; $invoicerow = $invoiceData->fetch(); $i++) {
                                                        $srNo++;
                                                        if ($i % 2 == 0) {
                                                            $class = 'even';
                                                        } else {
                                                            $class = 'odd';
                                                        }
                                                        $inv_id = $invoicerow['inv_id'];
                                                        $inv_invd_id = $invoicerow['inv_invd_id'];
                                                        $inv_generate_date = $invoicerow['inv_generate_date'];
                                                        $inv_inv_ref_no = $invoicerow['inv_ref_no'];
                                                        // $inv_status_d = ;
                                                        $phone = $invoicerow['phone'];
                                                        $last_name = $invoicerow['last_name'];
                                                        $first_name = $invoicerow['first_name'];
                                                        $cname = $invoicerow['cname'];
                                                        $inv_net_amount = $invoicerow['inv_net_amount'];
                                                        $inv_sgst_amount = $invoicerow['inv_sgst_amount'];
                                                        $inv_cgst_amount = $invoicerow['inv_cgst_amount'];
                                                        $inv_igst_amount = $invoicerow['inv_igst_amount'];
                                                        $inv_purchase_invoice_no = $invoicerow['inv_purchase_invoice_no'];
                                                        $igst = $invoicerow['igst'];
                                                        ?>
                                                        <tr class="<?php echo $class; ?>">
                                                            <td><center><?php echo $srNo; ?></center></td>
                                                            <!-- <td><?php echo $inv_invd_id; ?></td> -->
                                                            <td><?php echo $inv_purchase_invoice_no; ?></td>
                                                            <td><?php echo $first_name." ".$last_name; ?></td>
                                                            <td><?php echo $cname; ?></td>
                                                            <td><center><?php echo convert_db_to_disp_date($inv_generate_date); ?></center></td>
                                                            <?php /* ?>
                                                            <td>
                                                            <?php if ($igst == 'Y') { ?>
                                                                ICGST <?php echo ($inv_igst_amount); ?> INR
                                                            <?php }  else { ?>
                                                                CGST <?php echo $inv_cgst_amount; ?> INR </br>SGST <?php echo $inv_sgst_amount; ?> INR 
                                                            <?php } ?>
                                                            </td>
                                                            <?php */ ?>
                                                            <td><?php echo $inv_net_amount; ?> INR</td>
                                        <!-- <td><center>
                                        <a target="_blank" href="purchase_sale_print.php?type=<?php echo $page_type;?>&id=<?php echo $inv_id; ?>" class="text-info fa fa-fw fa-print"></a>&nbsp;
                                                                    <a href="<?php echo $edit_page_url; ?>?id=<?php echo $inv_id; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" title="Edit"></a>
                                                                    <?php
                                                                    $bln_delete =  order_allow_to_edit($invoicerow['inv_id']);
                                                                    if ($bln_delete == true)
                                                                    {
                                                                    ?>
                                                                    &nbsp;
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $inv_id; ?>, '<?php echo $page_type;?>');" title="Delete"></a>
                                                                    &nbsp;
                                                                    <?php } ?>
                                                                    
                                                                </center></td> -->


                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="5">No records found or you have not permission to access these records.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <?php if ($invoiceData) { ?>
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
            <?php include("includes/product_qty_manager.php"); ?>
            <?php include("includes/footer.php"); ?>
            <script type="text/javascript">
                $("#inv_purchase_del_id").select2();
                
            function exportToExcel()
            {
                var excelData = '<table border="1">';
                var tableHeaders = document.querySelectorAll('#example2 thead tr th');
                excelData += '<tr>';
                tableHeaders.forEach(function (header) {
                excelData += '<th>' + header.textContent + '</th>';
                });
                excelData += '</tr>';
                var tableRows = document.querySelectorAll('#example2 tbody tr');
                tableRows.forEach(function (row) {
                excelData += '<tr>';
                 var rowCells = row.querySelectorAll('td');
                rowCells.forEach(function (cell) {
                    excelData += '<td>' + cell.textContent + '</td>';
                });
                excelData += '</tr>';
                 });
                excelData += '</table>';
                var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                var pagetype=<?php echo json_encode($page_type);?>;
                a.download = pagetype+'.xls';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
        </script>
        </div>
    </body>
</html>