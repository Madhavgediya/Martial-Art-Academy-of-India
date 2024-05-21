<?php

include_once("mail/class.phpmailer.php");

function send_mail($temp_name, $param, $define_param, $dir_path = '', $htmlBody = '') {
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8';
    $mail->IsSMTP();
    $mail->Host = CON_MAIL_HOST;
    $mail->SMTPDebug = false;
    $mail->SMTPSecure = MAIL_SMTP_SECURE;
    $mail->SMTPAuth = true;
    $mail->Port = MAIL_PORT;
    $mail->Username = CON_MAIL_USER;
    $mail->Password = CON_MAIL_PASS;
    $mail->addCustomHeader('X-MC-Subaccount: ', MANDRILL_SUBACCOUNT);

    $subject = '';

    $param["SITE_URL"] = SITE_URL;

    $no_send_templates_arr = array();

    if (!in_array($temp_name, $no_send_templates_arr)) {
        $mail_template = new mail_template();
        $mail_template->data["*"] = "";
        $mail_template->where = "m_temp_name = '" . $temp_name . "'";
        $mail_template->action = 'get';
        $result = $mail_template->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($result['count'] > 0) {
                foreach ($result['res'] as $db_row) {
                    if ($temp_name == 'custom-template') {
                        $subject = $define_param['mail_subject'];
                    } else {
                        $subject = $db_row['m_temp_subject'];
                    }
                    if ($htmlBody == '') {
                        $htmlBody = $db_row['m_temp_body'];
                    }
                    foreach ($param as $key => $value)
                        $htmlBody = str_replace("[" . $key . "]", $value, $htmlBody);
                }
            }
        }
        //return $htmlBody;
    } else {
        return true;
    }

    if (isset($define_param['reply_to_email']) && isset($define_param['reply_to_name'])) {
        if ($define_param['reply_to_email'] != '' && $define_param['reply_to_name'] != '') {
            $mail->AddReplyTo($define_param['reply_to_email'], $define_param['reply_to_name']);
        }
    }

    $mail->AddAddress($define_param['to_email'], $define_param['to_name']);
    //$mail->AddBCC('emailsat0412@gmail.com', 'MMS');			  
    $mail->SetFrom($define_param['from_email'], $define_param['from_name']);
    $mail->Subject = $subject;
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    // optional - MsgHTML will create an alternate automatically

    if (MAIL_SWITCH == TRUE) {
        $mail->MsgHTML($htmlBody);
        if (!$mail->Send()) {
            echo $mail->ErrorInfo;
            //echo $htmlBody;
            //exit(0);
        }
    }

    //echo $htmlBody;
    //exit(0);

    $m_temp_id = 0;
    $mail_template = new mail_template();
    $mail_template->data["*"] = "";
    $mail_template->where = "m_temp_name = '" . $temp_name . "'";
    $mail_template->action = 'get';
    $result = $mail_template->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $m_temp_id = $db_row['m_temp_id'];
            }
        }
    }
    $log_email = new log_email();
    if (isset($define_param['user_id'])) {
        $log_email->data["lem_user_id"] = $define_param['user_id'];
    } else if (isset($define_param['admin_id'])) {
        $log_email->data["lem_user_id"] = $define_param['admin_id'];
    } else {
        $log_email->data["lem_user_id"] = 0;
    }
    $log_email->data["lem_from_name"] = $define_param['from_name'];
    $log_email->data["lem_from_email"] = $define_param['from_email'];
    $log_email->data["lem_to_name"] = $define_param['to_name'];
    $log_email->data["lem_to_email"] = $define_param['to_email'];
    $log_email->data["lem_subject"] = $subject;
    $log_email->data["lem_emailtext"] = $htmlBody;
    $log_email->data["lem_date"] = date('Y-m-d H:m:s');
    $log_email->data["lem_m_temp_id"] = $m_temp_id;
    $log_email->action = 'insert';
    $result = $log_email->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
        return $errormsg;
    } else {
        return 'success';
    }
}

function read_file($file) {
    $contents = '';
    if (file_exists($file)) {
        $handle = fopen($file, "r");
        $contents .= fread($handle, filesize($file));
        fclose($handle);
    }
    return $contents;
}

?>