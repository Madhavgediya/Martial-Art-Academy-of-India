<?php
$con = mysqli_connect("localhost", "root", "", "dbkkw4rfsaxdu5");
//$con = mysqli_connect("localhost","martialart",'MAaoi%SumiT#7878',"dbkkw4rfsaxdu5");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['skipReason']) && isset($_POST['grno']) && isset($_POST['ex_id'])) {
    $skipReason = mysqli_real_escape_string($con, $_POST['skipReason']);
    $grno = $_POST['grno'];
    $exid = $_POST['ex_id'];

    $getquery = "SELECT stu_id FROM sm_student WHERE stu_gr_no=?";
    $stmt = mysqli_prepare($con, $getquery);
    mysqli_stmt_bind_param($stmt, "s", $grno);
    mysqli_stmt_execute($stmt);
    $getresult = mysqli_stmt_get_result($stmt);

    if ($getresult) {
        $data = mysqli_fetch_assoc($getresult);

        if ($data) {
            $stu_id = $data['stu_id'];

            $getfee = "SELECT 
                        sm_student_course.sc_stu_id,
                        sm_branch_type.brt_id,
                        sm_course.co_id,
                        sm_student_course.sc_total_fee,
                        sm_branch_type.brt_amount_month 
                    FROM 
                        sm_student_course
                        INNER JOIN sm_branch_type ON sm_branch_type.brt_id = sm_student_course.sc_brt_id
                        INNER JOIN sm_course ON sm_course.co_id = sm_student_course.sc_co_id
                        INNER JOIN sm_branch ON sm_branch.br_id = sm_student_course.sc_br_id  
                    WHERE 
                        sm_student_course.sc_stu_id = ? 
                        AND sm_student_course.sc_is_current = 1
                    GROUP BY 
                        sm_student_course.sc_stu_id, sm_branch_type.brt_id, sm_course.co_id, sm_branch_type.brt_amount_month
                    ORDER BY 
                        sm_student_course.sc_id ASC";
            $stmt_getfee = mysqli_prepare($con, $getfee);
            mysqli_stmt_bind_param($stmt_getfee, "i", $stu_id);
            mysqli_stmt_execute($stmt_getfee);
            $getfeeresult = mysqli_stmt_get_result($stmt_getfee);

            if ($getfeeresult) {
                $row = mysqli_fetch_assoc($getfeeresult);
                $month_amount = $row['brt_amount_month'];

                $query = "UPDATE sm_exam_student_entrolled SET exam_skip_reason=? WHERE exs_ex_id=? AND exs_stu_id=?";
                $stmt_update = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt_update, "sii", $skipReason, $exid, $stu_id);
                $getupdate = mysqli_stmt_execute($stmt_update);

                if ($getupdate) {
                    $deletequery = "UPDATE sm_student SET is_skiped=1 WHERE stu_id=?";
                    $stmt_delete = mysqli_prepare($con, $deletequery);
                    mysqli_stmt_bind_param($stmt_delete, "i", $stu_id);
                    $getdelete = mysqli_stmt_execute($stmt_delete);

                    if ($getdelete) {
                        $updatedata = "UPDATE sm_student_course SET sc_total_fee=sc_total_fee+? WHERE sc_stu_id=?";
                        $stmt_updatefee = mysqli_prepare($con, $updatedata);
                        mysqli_stmt_bind_param($stmt_updatefee, "ii", $month_amount, $stu_id);
                        $updatefee = mysqli_stmt_execute($stmt_updatefee);

                        if ($updatefee) {
                            $response = [
                                'status' => 'success',
                                'message' => 'ok',
                                'getQueryValue' => $query,
                                'deleteMessage' => 'okdelete',
                                'getdeletequery' => $deletequery,
                                'updateQuery' => $updatedata,
                                'studentid'=>$stu_id
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Error updating fee: ' . mysqli_error($con)
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error deleting query: ' . mysqli_error($con)
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Error updating query: ' . mysqli_error($con)
                    ];
                }

                echo json_encode($response);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No data found for the specified condition']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

mysqli_close($con);
?>
