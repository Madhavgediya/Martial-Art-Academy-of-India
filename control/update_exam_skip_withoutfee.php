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
                    $response = [
                        'status' => 'success',
                        'message' => 'ok',
                        'getQueryValue' => $query,
                        'deleteMessage' => 'okdelete',
                        'getdeletequery' => $deletequery,
                        'studentid'=>$stu_id
                    ];
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
