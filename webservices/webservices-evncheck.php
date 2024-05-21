<?php
define('URL_PREFIX','http://eklavya.aksharecommerce.com/webservices/');
// define('URL_PREFIX','');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Check Webservices</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <table>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>authentication.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Authentication</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="studentlogin" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                login id    
                                            </td>

                                            <td>
                                                <input type="text" id="login_id" name="login_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                login password


                                            </td>

                                            <td>
                                                <input type="text" id="login_password" name="login_password" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Faculty</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="faculty" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Faculty</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="complain" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                complain_title
                                            </td>

                                            <td>
                                                <input type="text" id="complain_title" name="complain_title" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                complain_description    
                                            </td>

                                            <td>
                                                <input type="text" id="complain_description" name="complain_description" />
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><!-- student -->
                    <form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Student</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="student" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>




                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><!-- school contact us  -->
                    <form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>School Contact Us</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="method" name="method" value="school-contactus" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>




                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><!-- school news  -->
                    <form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>School News</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="method" name="method" value="school-news" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>-->




                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><!-- school news  -->
                    <form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>School Events</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="method" name="method" value="school-events" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>-->




                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>School Gallery</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="method" name="method" value="school-gallery" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>-->




                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td></td>
            </tr>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>School Gallery</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="method" name="method" value="school-gallery-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>-->

                                        <tr>
                                            <td>
                                                Gallery ID
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="gallery_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Complain Reply</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="complain-reply" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                complain no
                                            </td>

                                            <td>
                                                <input type="text" id="cm_identy_id" name="cm_identy_id" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                complain_description    
                                            </td>

                                            <td>
                                                <input type="text" id="complain_description_reply" name="complain_description_reply" />
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Complain Details</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-complain" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Complain Details in Details</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-complain-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                cm_id
                                            </td>

                                            <td>
                                                <input type="text" id="cm_identy_id" name="cm_identy_id" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Event Details in Details</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-event-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>-->

                                        <tr>
                                            <td>
                                                event id
                                            </td>

                                            <td>
                                                <input type="text" id="event_id" name="event_id" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Student Attendance Details</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-attendance-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Month
                                            </td>

                                            <td>
                                                <input type="text" id="att_month" name="att_month" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Year
                                            </td>
                                            <td>
                                                <input type="text" id="att_year" name="att_year" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Student Result</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-result-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Faculty with Login</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="faculty-login" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Time Table</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-time-table" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Article</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="school-article" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>circular</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="school-circular" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>circular details</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="get-circular-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                Cirular ID
                                            </td>

                                            <td>
                                                <input type="text" id="ci_id" name="ci_id" />

                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
            </tr>
            <tr>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>GCM</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="register-gcm" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                session id    
                                            </td>

                                            <td>
                                                <input type="text" id="session_id" name="session_id" />
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                GCM ID
                                            </td>

                                            <td>
                                                <input type="text" id="ci_id" name="ci_id" />

                                            </td>
                                        </tr>
                                        
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Daily Darshan</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="dailydarshan" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                         
                                        
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                <td><form id="" method="post" action="<?php echo URL_PREFIX; ?>general.php" target="_blank" >
                        <table>
                            <tr>
                                <td>Daily Darshan</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="dailydarshan-details" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <input type="text" id="school" name="school" />

                                            </td>
                                        </tr>
                                         
                                         <tr>
                                            <td>
                                                dailydarshan ID
                                            </td>

                                            <td>
                                                <input type="text" id="dailydarshan_id" name="dailydarshan_id" />

                                            </td>
                                        </tr>
                                        
                                       
                                        <tr>
                                            <td colspan="2"> 
                                                <input type="submit" value="submit" />
                                            </td>

                                        </tr>


                                    </table>
                                </td>

                            </tr>
                        </table>
                    </form></td>
                
            </tr>
        

        </table>




        <!-- complain -->









    </body>

</html>