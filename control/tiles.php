<?php

    $con = mysqli_connect("localhost","root","","dbkkw4rfsaxdu5");
   //$con = mysqli_connect("localhost","martialart",'MAaoi%SumiT#7878',"dbkkw4rfsaxdu5");

//Cash
    $CashCredit=0;
            $CashDebit=0;

            $totalCash=0;
        
            $cash1 = mysqli_query($con,"select * from sm_payment_transaction where pt_tran_mode_of_payent='Cash' and pt_type='Credit' ");

            $cash2 = mysqli_query($con,"select * from sm_payment_transaction where pt_tran_mode_of_payent='Cash' and pt_type='Debit' ");

            while($row = mysqli_fetch_array($cash1))
            {
                $CashCredit += $row["pt_tran_amount"];
            }

            while($row = mysqli_fetch_array($cash2))
            {
                $CashDebit += $row["pt_tran_amount"];
            }

            $totalCash = $CashCredit - $CashDebit;


//Bank
    $BankCredit=0;
            $BankDebit=0;

            $totalBank=0;
        
            $bank1 = mysqli_query($con,"select * from sm_payment_transaction where pt_type='Credit' and (pt_tran_mode_of_payent='DD' or pt_tran_mode_of_payent='Cheque' or pt_tran_mode_of_payent='Net Banking') ");

            $bank2 = mysqli_query($con,"select * from sm_payment_transaction where pt_type='Debit' and (pt_tran_mode_of_payent='DD' or pt_tran_mode_of_payent='Cheque' or pt_tran_mode_of_payent='Net Banking') ");
            
            // $total = $bank1 - $bank2;

            while($row = mysqli_fetch_array($bank1))
            {
                $BankCredit += $row["pt_tran_amount"];
            }

            while($row = mysqli_fetch_array($bank2))
            {
                $BankDebit += $row["pt_tran_amount"];
            }

            $totalBank = $BankCredit - $BankDebit;


//Total student & Active Student
    $std1 = mysqli_query($con,"select * from sm_student");
    $total_student = mysqli_num_rows($std1);
    $std2 = mysqli_query($con,"select * from sm_student where stu_status='A'");
    $active_student = mysqli_num_rows($std2);

//Pending Books
    $book = mysqli_query($con,"SELECT bki.* ,bk.book_id from sm_book_issue_history bki inner join sm_book bk on bk.book_id = bki.bi_book_id where bki.bi_status='Pending';
    ");
    $pending_books = mysqli_num_rows($book);

//Total Fee
    $sum = 0;
    $total_amount = mysqli_query($con,"SELECT * FROM sm_payment_transaction where pt_tran_amount");
    while($row = mysqli_fetch_assoc($total_amount))
    {
        $sum += $row["pt_tran_amount"];
    }

//Inquery & Pending Inquiry
    $complete = mysqli_query($con,"select * from sm_contact where con_status='Closed'");
    $complete_inquiry = mysqli_num_rows($complete);
    $pending = mysqli_query($con,"select * from sm_contact where con_status='Discussion' or con_status='Open'");
    $pending_inquiry = mysqli_num_rows($pending);
?>

<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/tiles.css">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card" style="height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Cash</h4>
                    <h2 class="text-right"><i class="fa fa-money f-left"></i><span><?php echo $totalCash . ' &#8377'; ?></span></h2>
                    <!-- <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> -->
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card" style="height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Bank</h4>
                    <h2 class="text-right"><i class="fa fa-bank f-left"></i><span><?php echo $totalBank . ' &#8377'; ?></span></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card" style="height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Total Student</h4>
                    <h2 class="text-right"><i class="fa fa-user f-left"></i><span><?php echo $total_student ?></span></h2>
                    <p class="m-b-0">Active Student<span class="f-right"><?php echo $active_student ?></span></p>

                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card" style="height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Pending Books</h4>
                    <h2 class="text-right"><i class="fa fa-book f-left"></i><span><?php echo $pending_books ?></span></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card order-card" style="background:#ab4aff;height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Total Fee</h4>
                    <h2 class="text-right"><i class="fa fa-rupee f-left"></i><span><?php echo $sum . ' &#8377'; ?></span></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card order-card" style="background:#5985b5; height:135px; width:300px;">
                <div class="card-block">
                    <h4 class="m-b-20">Inquiry</h4>
                    <h2 class="text-right"><i class="fa fa-comments f-left"></i><span><?php echo $complete_inquiry ?></span></h2>
                    <p class="m-b-0">Pending Inquiry<span class="f-right"><?php echo $pending_inquiry ?></span></p>
                   
                </div>
            </div>
        </div>

	</div>
</div>
</body>
</html>