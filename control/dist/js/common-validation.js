/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var have_error = 'N';					   
var have_error_id = '';	
function validate_from_with_required()
{
    alert('called funcation');
    $(".required").each(
		function () {
                    alert('inside function');
		 }
	);
    return false;
}

