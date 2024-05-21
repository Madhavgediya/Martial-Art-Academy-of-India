<?php
define('URL_PREFIX','http://eklavya.aksharecommerce.com/webservices/');
// define('URL_PREFIX','');
if (isset($_REQUEST["method"]) && $_REQUEST["method"] == 'de')
{
    echo "<pre>";
    print_r(unserialize($_REQUEST["school"])); 
    echo "</pre>";
}
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
                <td><form id="" method="post" action=""  >
                        <table>
                            <tr>
                                <td>Authentication</td>
                            </tr>
                            <tr>
                                <td>

                                    <input type="hidden" id="method" name="method" value="de" >
                                    <table>
                                        <tr>
                                            <td>
                                                school
                                            </td>

                                            <td>
                                                <textarea id="school" name="school"  ></textarea>

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
    </body>

</html>