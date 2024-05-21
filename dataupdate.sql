SELECT  DISTINCT exs_certificate, exs_belt, exs_co_id, exs_be_id,exs_already_paid,stu_id, exs_id,exs_ex_id, exs_result_status,exs_result_marks,exs_enroll_next,	 exs_total_marks, exs_fee,exs_paid,  exs_total_marks as eca_total_marks,
co_name , be_id, be_name_for ,be_belt_duration  ,be_belt_exam_fee, sc_joined_date,
stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name  
FROM sm_exam INNER JOIN  sm_exam_student_entrolled ON (ex_id = exs_ex_id)
INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND  exs_be_id = sc_be_id AND exs_co_id = sc_co_id )
INNER JOIN sm_course ON (co_id = sc_co_id)
INNER JOIN sm_belt ON (be_belt_duration != 0  AND be_id = sc_be_id)
INNER JOIN sm_student ON (stu_id = sc_stu_id )
INNER JOIN sm_branch_type ON (sc_brt_id = brt_id )  
WHERE  sc_stu_id= 5
ORDER BY ex_date ,exs_id ASC 

--------- --- 



INSERT INTO emb_delete (emb_delete_id)
SELECT emb_id FROM embroidery e INNER JOIN embroidery e1 ON (e.emb_file_thumb_path = e1.emb_file_thumb_path) 
WHERE e.emb_id > e1.emb_id


DELETE FROM embroidery WHERE emb_id IN (SELECT emb_delete_id FROM emb_delete)

SELECT e.emb_id FROM embroidery e INNER JOIN embroidery e1 ON (e.emb_file_thumb_path = e1.emb_file_thumb_path) 
WHERE e.emb_id > e1.emb_id


SELECT e.* FROM sm_exam_student_entrolled e INNER JOIN sm_exam_student_entrolled e1 ON (e.exs_stu_id = e1.exs_stu_id) 
INNER JOIN sm_student s ON (e.exs_stu_id = s.stu_id)
WHERE s.stu_br_id = 1
AND e.exs_id > e1.exs_id


SELECT GROUP_CONCAT(e.sc_id) FROM sm_student_course e INNER JOIN sm_student_course e1 ON (e.sc_stu_id = e1.sc_stu_id) 
INNER JOIN sm_student s ON (e.sc_stu_id = s.stu_id)
WHERE s.stu_br_id = 1
AND e.sc_id > e1.sc_id


DELETE FROM sm_student_course WHERE sc_id IN (355,356,357,358,359,361,362,363,364,365,366,367)

UPDATE sm_student_course SET sc_total_paid= 0, sc_full_fee_paid = 'N' , sc_is_current = 1, sc_remarks = '', sc_additional_days = 0
WHERE sc_br_id =1


SELECT GROUP_CONCAT(e.exs_id) FROM sm_exam_student_entrolled e INNER JOIN sm_exam_student_entrolled e1 ON (e.exs_stu_id = e1.exs_stu_id) 
INNER JOIN sm_student s ON (e.exs_stu_id = s.stu_id)
WHERE s.stu_br_id = 1
AND e.exs_id > e1.exs_id

UPDATE sm_student_course SET sc_total_paid= 0, sc_full_fee_paid = 'N' , sc_is_current = 1, sc_remarks = '', sc_additional_days = 0
WHERE sc_br_id =1


UPDATE sm_exam_student_entrolled e INNER JOIN sm_student s ON (s.stu_id = e.exs_stu_id) 
SET 
WHERE s.stu_br_id = 1
AND e.exs_id > e1.exs_id


DELETE FROM sm_payment_transaction WHERE pt_br_id =1;
DELETE FROM sm_exam_result WHERE exre_br_id = 1;
DELETE FROM sm_exam WHERE ex_br_id = 1;

DELETE FROM sm_exam_student_entrolled WHERE exs_id IN (
24,25,39,47,48,49,50,51,52,53,54,55,56,57,58,59)

-- DELETE FROM sm_student_attendance WHERE 
-- DELETE FROM sm_student_course WHERE 


SELECT GROUP_CONCAT(e.exs_id) FROM sm_exam_student_entrolled e 
INNER JOIN sm_exam_student_entrolled e1 ON (e.exs_stu_id = e1.exs_stu_id) 
INNER JOIN sm_student s ON (e.exs_stu_id = s.stu_id)
WHERE s.stu_br_id = 1 AND e.exs_id > e1.exs_id 

DELETE FROM sm_exam_student_entrolled WHERE exs_id IN (
SELECT e.exs_id FROM sm_exam_student_entrolled e 
INNER JOIN sm_exam_student_entrolled e1 ON (e.exs_stu_id = e1.exs_stu_id) 
INNER JOIN sm_student s ON (e.exs_stu_id = s.stu_id)
WHERE s.stu_br_id = 1 AND e.exs_id > e1.exs_id )

WHERE 
TRUNCATE `sm_exam`;
TRUNCATE `sm_exam_result`;
TRUNCATE `sm_exam_student_entrolled`;
TRUNCATE `sm_log`;
TRUNCATE `sm_payment_transaction`;
TRUNCATE `sm_result`;
TRUNCATE `sm_results`;
TRUNCATE `sm_student_attendance`;
TRUNCATE `sm_student_course`;


/*
SELECT length('M000000001');
SELECT length('M000000052');
SELECT length('M000000163');
*/

ALTER TABLE `main_student` ADD `Id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Id`);
ALTER TABLE `main_student` CHANGE `EnrollmentNo` `EnrollmentNo` INT(3) NULL DEFAULT NULL;
ALTER TABLE `main_student` ADD `EnrollmentNoCP` VARCHAR(10) NOT NULL DEFAULT '' AFTER `EnrollmentNo`;
UPDATE main_student SET EnrollmentNoCP = CONCAT('M00000000',EnrollmentNo) WHERE EnrollmentNo > 0 ANd EnrollmentNo <10;
UPDATE main_student SET EnrollmentNoCP = CONCAT('M0000000',EnrollmentNo) WHERE EnrollmentNo > 9 ANd EnrollmentNo <100;
UPDATE main_student SET EnrollmentNoCP = CONCAT('M000000',EnrollmentNo) WHERE EnrollmentNo > 99 ;
UPDATE `main_student` SET `BirthDate` = '1985-12-04' WHERE BirthDate = '';
ALTER TABLE `main_student` CHANGE `BirthDate` `BirthDate` DATE NULL DEFAULT NULL;
ALTER TABLE `main_student` CHANGE `AddmitionDate` `AddmitionDate` DATE NULL DEFAULT NULL;
UPDATE `main_student` SET `EnrollmentNo` = '1' WHERE `main_student`.`Id` = 1;

INSERT INTO `sm_student`(`stu_id`, `stu_gr_no`, `stu_profile`, `stu_first_name`, `stu_middle_name`, `stu_last_name`, `stu_birth_date`, `stu_phone`, `stu_email`, `stu_home_address`, `stu_office_address`, `stu_city`, `stu_state_id`, `stu_postal_code`, `stu_mother_name`, `stu_parent_mobile_no`, `stu_aadharno`, `stu_whatsappno`, `stu_batchtime`,  `stu_admission_date`) 
SELECT `EnrollmentNo`, `EnrollmentNoCP`, `Profile`, `FirstNAMe`, `MiddelNAMe`, `LastNAMe`, `BirthDate`,`ApplicantMo`,`Email`, `HomeAddress`, `OfficeAddress`, '',2, '', `MotherNAMe`, `ParanceMo`,  `Aadhar No`, `WhatsappNo`,  `BatchTime`,   `AddmitionDate` FROM `main_student` 
ORDER BY EnrollmentNo ASC

UPDATE `sm_student` SET  stu_br_id = 1;
UPDATE `sm_student` SET  stu_br_id = 2 WHERE stu_gr_no IN ('M000000092','M000000093','M000000094','M000000126','M000000131','M000000158','M000000160','M000000163');

UPDATE `sm_student`  INNER JOIN sm_batch_time ON (stu_batchtime = bt_name ) 
SET stu_batchtime = bt_id
WHERE stu_br_id = bt_br_id



ALTER TABLE `main_student` CHANGE `Belt` `Belt` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

UPDATE main_student SET Belt = CONCAT(Belt, ' Belt');


SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-05-16',1,'2018-05-16',1
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNoCP AND BatchType = brt_name
AND stu_br_id = 1 AND brt_br_id =1
ANd co_br_id = 1
AND be_br_id = 1

SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-05-16',1,'2018-05-16',1
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNoCP AND BatchType = brt_name
AND stu_br_id = 2 AND brt_br_id =2
ANd co_br_id = 2
AND be_br_id = 2

-- next query to entroll in course
-- next query to add fee details
-- next to update course next date


SELECT count(*), EnrollmentNo FROM `main_student` group by EnrollmentNo having count(*) > 1 

SELECT * 
FROM `main_student` 
WHERE EnrollmentNo IN (92,93,94,126,131,158,160,163)


INSERT INTO `sm_student_course`(`sc_id`, `sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, `sc_total_fee`, `sc_total_paid`, `sc_full_fee_paid`, `sc_is_current`, `sc_belt_duration`, `sc_belt_exam_fee`, `sc_belt_onemonth_fee`, `sc_remarks`, `sc_create_date`, `sc_create_by_id`, `sc_update_date`, `sc_update_by_id`) 

SELECT stu_id , 0 , stu_br_id , brt_id 
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN sm_branch_type ON (BatchType = brt_name)

Hi,

How are you?


Martial Art Control Panel.
http://maccess.martialartacademyofindia.com/control/

Login 1:
zanzarda@martialartacademyofindia.com
martialart@123

Login 2:
martialartacademyofindia@gmail.com
martialart@123


DELETE FROM `sm_belt` WHERE be_br_id = 2;

INSERT INTO `sm_belt`(`be_name`, `be_name_for`, `be_image`, `be_sort_order`, `be_status`, `be_belt_duration`, `be_belt_fee`, `be_belt_exam_fee`, `be_belt_onemonth_fee`, `be_br_id`, `be_create_date`, `be_create_by_id`, `be_update_date`, `be_update_by_id`)
SELECT `be_name`, `be_name_for`, `be_image`, `be_sort_order`, `be_status`, `be_belt_duration`, `be_belt_fee`, `be_belt_exam_fee`, `be_belt_onemonth_fee`, 2, `be_create_date`, `be_create_by_id`, `be_update_date`, `be_update_by_id` FROM `sm_belt` WHERE be_br_id = 1;

DELETE FROM `sm_branch_type` WHERE `brt_br_id` = 2;


INSERT INTO `sm_branch_type`(`brt_name`, `brt_amount`, `brt_amount_month`, `brt_days`, `brt_status`, `brt_br_id`, `brt_create_date`, `brt_create_by_id`, `brt_update_date`, `brt_update_by_id`)
SELECT `brt_name`, `brt_amount`, `brt_amount_month`, `brt_days`, `brt_status`, 2, `brt_create_date`, `brt_create_by_id`, `brt_update_date`, `brt_update_by_id` FROM `sm_branch_type`  WHERE `brt_br_id` = 1;


DELETE FROM `sm_exam_categories` WHERE `exc_br_id` = 2;

INSERT INTO `sm_exam_categories`(`exc_parent_id`, `exc_group_name`, `exc_name`, `exc_marks`, `exc_status`, `exc_br_id`, `exc_create_date`, `exc_create_by_id`, `exc_update_date`, `exc_update_by_id`)
SELECT  `exc_parent_id`, `exc_group_name`, `exc_name`, `exc_marks`, `exc_status`, 2, `exc_create_date`, `exc_create_by_id`, `exc_update_date`, `exc_update_by_id` FROM `sm_exam_categories` WHERE `exc_br_id` = 1;

DELETE FROM `sm_exam_categories_allocation` WHERE `eca_br_id` = 2;

INSERT INTO `sm_exam_categories_allocation`(`eca_be_id`, `eca_ex_id`, `eca_exc_id`, `eca_total_marks`, `eca_obtain_marks`, `eca_br_id`, `eca_create_date`, `eca_create_by_id`, `eca_update_date`, `eca_update_by_id`)
SELECT `eca_be_id`, `eca_ex_id`, `eca_exc_id`, `eca_total_marks`, `eca_obtain_marks`,2, `eca_create_date`, `eca_create_by_id`, `eca_update_date`, `eca_update_by_id` FROM `sm_exam_categories_allocation` WHERE `eca_br_id` = 1;



Entrollment query

INSERT INTO `sm_student_course`(`sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, `sc_total_fee`, `sc_total_paid`, `sc_full_fee_paid`, `sc_is_current`, `sc_belt_duration`, `sc_belt_exam_fee`, `sc_belt_onemonth_fee`, `sc_remarks`, `sc_create_date`, `sc_create_by_id`, `sc_update_date`, `sc_update_by_id`) 
SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-06-23',1,'2018-06-23',1
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNoCP AND BatchType = brt_name
AND stu_br_id = 1 AND brt_br_id =1
ANd co_br_id = 1
AND be_br_id = 1


INSERT INTO `sm_student_course`(`sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, `sc_total_fee`, `sc_total_paid`, `sc_full_fee_paid`, `sc_is_current`, `sc_belt_duration`, `sc_belt_exam_fee`, `sc_belt_onemonth_fee`, `sc_remarks`, `sc_create_date`, `sc_create_by_id`, `sc_update_date`, `sc_update_by_id`) 
SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-06-23',1,'2018-06-23',1
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNoCP AND BatchType = brt_name
AND stu_br_id = 2 AND brt_br_id =2
ANd co_br_id = 2
AND be_br_id = 2


AND BatchType = 'Alternate MWFS'


select stu_id, stu_br_id, stu_gr_no from sm_student WHERE stu_id NOT IN (SELECT sc_stu_id FROM sm_student_course WHERE sc_br_id = 1) AND stu_br_id = 1

select stu_id, stu_br_id, stu_gr_no from sm_student WHERE stu_id NOT IN (SELECT sc_stu_id FROM sm_student_course) 

UPDATE  main_student SET BatchType = 'Alternate MWFS' WHERE BatchType='Alternate' 
UPDATE main_student SET BatchType = 'Weekly 5 Days (Monday to Friday)' WHERE BatchType='Weekly 5 Day' 
AND EnrollmentNoCP IN ('M000000092','M000000093','M000000094','M000000126','M000000131','M000000158','M000000160','M000000163');

UPDATE main_student SET BatchType = 'Weekly 5 Days (Monday to Friday)' WHERE BatchType='Weekly 5 Days (Monda' 
AND EnrollmentNoCP IN ('M000000092','M000000093','M000000094','M000000126','M000000131','M000000158','M000000160','M000000163');

SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-06-23',1,'2018-06-23',1
FROM sm_student INNER JOIN main_student ON (stu_gr_no = EnrollmentNoCP) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNoCP AND BatchType = brt_name
AND stu_br_id = 2 AND brt_br_id =2
ANd co_br_id = 2
AND be_br_id = 2
AND stu_gr_no = 'M000000092'

M000000108
M000000129
M000000130
M000000146
M000000147
M000000164

/*
Truncate all
DELETE FROM ``;
DELETE FROM `sm_exam`;
DELETE FROM `sm_exam_student_entrolled`;
DELETE FROM `sm_exam_result`;



TRUNCATE `sm_student_course`;
TRUNCATE `sm_exam`;
TRUNCATE `sm_exam_student_entrolled`;
TRUNCATE `sm_exam_result`;
TRUNCATE `sm_payment_transaction`;
TRUNCATE `sm_student`;
TRUNCATE `sm_log`;

TRUNCATE `sm_dealer`;
TRUNCATE `sm_event`;
TRUNCATE `sm_event_student_entrolled`;
TRUNCATE `sm_exam`;
-- TRUNCATE `sm_exam_categories_allocation`;
TRUNCATE `sm_exam_result`;
TRUNCATE `sm_exam_student_entrolled`;
TRUNCATE `sm_invoice`;
TRUNCATE `sm_invoice_products`;
TRUNCATE `sm_log`;
TRUNCATE `sm_student`;
TRUNCATE `sm_student_attendance`;
TRUNCATE `sm_student_course`;
TRUNCATE `sm_student_document`;
TRUNCATE `sm_student_other`;
TRUNCATE `sm_payment_transaction`;
-- DELETE FROM `sm_payment_transaction` WHERE pt_tran_u_type = 'Exam fee';
Course fee
Event fee
Event fee[other]
Event fee[student]
*/

-- 01-Aug-2018 1:54 PM
ALTER TABLE `sm_student_course` ADD `sc_additional_days` INT NOT NULL DEFAULT '0' AFTER `sc_remarks`;



-- Import new file process -- 08-Aug-2018 12:44 PM

ALTER TABLE `gym_student` ADD `branch_id` INT NOT NULL DEFAULT '2' AFTER `AddmitionDate`;
ALTER TABLE `main_student_import` ADD `branch_id` INT NOT NULL DEFAULT '1' AFTER `AddmitionDate`;

/*
ALTER TABLE `gym_student` CHANGE `BirthDate` `BirthDate` DATE NULL DEFAULT NULL;
ALTER TABLE `gym_student` CHANGE `AddmitionDate` `AddmitionDate` DATE NULL DEFAULT NULL;
ALTER TABLE `main_student_import` CHANGE `BirthDate` `BirthDate` DATE NULL DEFAULT NULL;
ALTER TABLE `main_student_import` CHANGE `AddmitionDate` `AddmitionDate` DATE NULL DEFAULT NULL;
*/


UPDATE `gym_student` SET BirthDate = CONCAT(SUBSTRING(BirthDate, 7, 4),'-',SUBSTRING(BirthDate, 4, 2),'-',SUBSTRING(BirthDate, 1, 2)) WHERE BirthDate !='';
UPDATE `gym_student` SET AddmitionDate = CONCAT(SUBSTRING(AddmitionDate, 7, 4),'-',SUBSTRING(AddmitionDate, 4, 2),'-',SUBSTRING(AddmitionDate, 1, 2)) WHERE AddmitionDate !='';

UPDATE `main_student_import` SET BirthDate = CONCAT(SUBSTRING(BirthDate, 7, 4),'-',SUBSTRING(BirthDate, 4, 2),'-',SUBSTRING(BirthDate, 1, 2)) WHERE BirthDate !='';
UPDATE `main_student_import` SET AddmitionDate = CONCAT(SUBSTRING(AddmitionDate, 7, 4),'-',SUBSTRING(AddmitionDate, 4, 2),'-',SUBSTRING(AddmitionDate, 1, 2)) WHERE AddmitionDate !='';


INSERT INTO `main_student_import` (`EnrollmentNo`, `Profile`, `FirstName`, `MiddelName`, `LastName`, `MotherNAMe`, `BirthDate`, `HomeAddress`, `OfficeAddress`, `Email`, `ParanceMo`, `ApplicantMo`, `WhatsappNo`, `Aadhar No`, `BatchTime`, `BatchType`, `Courseid`, `Belt`, `AddmitionDate`, `branch_id`) 
SELECT `EnrollmentNo`, `Profile`, `FirstName`, `MiddelName`, `LastName`, `MotherNAMe`, `BirthDate`, `HomeAddress`, `OfficeAddress`, `Email`, `ParanceMo`, `ApplicantMo`, `WhatsappNo`, `Aadhar No`, `BatchTime`, `BatchType`, `Courseid`, `Belt`, `AddmitionDate`, `branch_id`
FROM gym_student

ALTER TABLE `main_student_import` CHANGE `BatchType` `BatchType` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
UPDATE `main_student_import` SET BatchType = 'Weekly 5 Days (Monday to Friday)' WHERE BatchType = 'Weekly 5 Day' AND branch_id = 2;

INSERT INTO `sm_student`(`stu_gr_no`, `stu_profile`, `stu_first_name`, `stu_middle_name`, `stu_last_name`, `stu_birth_date`, `stu_phone`, `stu_email`, `stu_home_address`, `stu_office_address`, `stu_city`, `stu_state_id`, `stu_postal_code`, `stu_mother_name`, `stu_parent_mobile_no`, `stu_aadharno`, `stu_whatsappno`, `stu_batchtime`,  `stu_admission_date`,stu_br_id) 
SELECT  `EnrollmentNo`, `Profile`, `FirstNAMe`, `MiddelNAMe`, `LastNAMe`, `BirthDate`,`ApplicantMo`,`Email`, `HomeAddress`, `OfficeAddress`, '',2, '', `MotherNAMe`, `ParanceMo`,  `Aadhar No`, `WhatsappNo`,  `BatchTime`,   `AddmitionDate` ,`branch_id`  FROM `main_student_import` 
ORDER BY EnrollmentNo ASC

Cross Query:

SELECT * from  sm_student WHERE CONCAT('MA0000000',stu_id) != stu_gr_no AND stu_id < 10
SELECT * from  sm_student WHERE CONCAT('MA000000',stu_id) != stu_gr_no AND stu_id > 9 AND stu_id <100;
SELECT * from  sm_student WHERE CONCAT('MA00000',stu_id) != stu_gr_no AND stu_id > 99;


UPDATE `sm_student` SET stu_create_by_id = stu_br_id , stu_update_by_id = stu_br_id;
UPDATE `sm_student` SET stu_create_date = now() , stu_update_date = now();


INSERT INTO `sm_student_course`(`sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, `sc_total_fee`, `sc_total_paid`, `sc_full_fee_paid`, `sc_is_current`, `sc_belt_duration`, `sc_belt_exam_fee`, `sc_belt_onemonth_fee`, `sc_remarks`, `sc_create_date`, `sc_create_by_id`, `sc_update_date`, `sc_update_by_id`) 
SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-06-23',1,'2018-06-23',1
FROM sm_student INNER JOIN main_student_import ON (stu_gr_no = EnrollmentNo) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNo AND BatchType = brt_name
AND stu_br_id = 1 AND brt_br_id =1


INSERT INTO `sm_student_course`(`sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, `sc_total_fee`, `sc_total_paid`, `sc_full_fee_paid`, `sc_is_current`, `sc_belt_duration`, `sc_belt_exam_fee`, `sc_belt_onemonth_fee`, `sc_remarks`, `sc_create_date`, `sc_create_by_id`, `sc_update_date`, `sc_update_by_id`) 
SELECT stu_id , 0 , stu_br_id , brt_id , co_id ,be_id, stu_admission_date,brt_amount,0,'N',1,0,0,0,'','2018-06-23',1,'2018-06-23',1
FROM sm_student INNER JOIN main_student_import ON (stu_gr_no = EnrollmentNo) 
INNER JOIN  sm_branch_type ON (BatchType = brt_name)
INNER JOIN  sm_course ON (Courseid  = co_name)
INNER JOIN  sm_belt ON (Belt  = be_name )
WHERE stu_gr_no = EnrollmentNo AND BatchType = brt_name
AND stu_br_id = 2 AND brt_br_id =2



-- UPDATE sm_student_course SET sc_total_fee = 2400 WHERE sc_stu_id IN (1,52,62,82,83,96)

-- find the receipt no as integer
SELECT * FROM sm_payment_transaction WHERE ( pt_receipt_no > 650 AND pt_receipt_no < 666 ) AND pt_tran_amount = 2400 ORDER BY pt_receipt_no ASC


-- find the receipt wise payment details to check and process , requested by akash bhai.
-- finding course fee
-- SELECT stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , sc_half_course,  pt_receipt_no, pt_type, pt_tran_u_type, pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no, pt_tran_no, pt_tran_date, pt_tran_remarks , pt_sc_id  
SELECT stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , sc_half_course,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%M-%Y') as transaction_date, pt_tran_remarks , 'main branch'
FROM `sm_payment_transaction`  INNER JOIN sm_student_course ON (pt_sc_id = sc_id) 
INNER JOIN sm_student ON (stu_id = sc_stu_id)
LEFT JOIN  sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_course ON (sc_co_id = co_id)
WHERE pt_br_id = 1 
AND pt_tran_u_type = 'Course Fee'
ORDER BY pt_receipt_no 

SELECT stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , sc_half_course,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%M-%Y') as transaction_date, pt_tran_remarks , 'zanzarda road'
FROM `sm_payment_transaction`  INNER JOIN sm_student_course ON (pt_sc_id = sc_id) 
INNER JOIN sm_student ON (stu_id = sc_stu_id)
LEFT JOIN  sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_course ON (sc_co_id = co_id)
WHERE pt_br_id = 2 
AND pt_tran_u_type = 'Course Fee'
ORDER BY pt_receipt_no 

-- Exam fee for student
SELECT stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for ,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%M-%Y') as transaction_date, pt_tran_remarks , ex_name  ,  'Main branch'
FROM `sm_payment_transaction`  INNER JOIN sm_exam_student_entrolled ON (pt_sc_id = exs_id) 
INNER JOIN sm_exam ON (exs_ex_id = ex_id)
INNER JOIN sm_student ON (stu_id = exs_stu_id)
LEFT JOIN  sm_belt ON (exs_be_id = be_id)
LEFT JOIN sm_course ON (exs_co_id = co_id)
WHERE pt_br_id = 1 
AND pt_tran_u_type = 'Exam Fee'
ORDER BY pt_receipt_no 

SELECT stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for ,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%M-%Y') as transaction_date, pt_tran_remarks , ex_name  ,  'zanzarda road'
FROM `sm_payment_transaction`  INNER JOIN sm_exam_student_entrolled ON (pt_sc_id = exs_id) 
INNER JOIN sm_exam ON (exs_ex_id = ex_id)
INNER JOIN sm_student ON (stu_id = exs_stu_id)
LEFT JOIN  sm_belt ON (exs_be_id = be_id)
LEFT JOIN sm_course ON (exs_co_id = co_id)
WHERE pt_br_id = 2 
AND pt_tran_u_type = 'Exam Fee'
ORDER BY pt_receipt_no 


----- 
SELECT pt_stu_id, sc_stu_id FROM `sm_payment_transaction` INNER JOIN sm_student_course ON (sc_id = pt_sc_id AND sc_br_id = pt_br_id )  
WHERE pt_stu_id = 0 
AND pt_tran_u_type = 'Course fee'

UPDATE sm_payment_transaction INNER JOIN sm_student_course ON (sc_id = pt_sc_id AND sc_br_id = pt_br_id )  
SET pt_stu_id =  sc_stu_id 
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Course fee' AND sc_id = pt_sc_id AND sc_br_id = pt_br_id


SELECT pt_stu_id, exs_stu_id FROM sm_payment_transaction INNER JOIN sm_exam_student_entrolled ON (exs_id = pt_sc_id  )  
WHERE pt_stu_id = 0 
AND pt_tran_u_type = 'Exam fee'

UPDATE sm_payment_transaction INNER JOIN sm_exam_student_entrolled ON (exs_id = pt_sc_id  )  
SET pt_stu_id =  exs_stu_id 
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Exam fee' AND exs_id = pt_sc_id


SELECT pt_stu_id, evs_stu_id FROM sm_payment_transaction INNER JOIN sm_event_student_entrolled ON (evs_id = pt_sc_id)  
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Event fee[student]' AND evs_transtion_type = 'Event fee[student]'

UPDATE sm_payment_transaction INNER JOIN sm_event_student_entrolled ON (evs_id = pt_sc_id)  
SET pt_stu_id =  evs_stu_id
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Event fee[student]' AND evs_transtion_type = 'Event fee[student]'


SELECT pt_stu_id, evs_stu_id FROM sm_payment_transaction INNER JOIN sm_event_student_entrolled ON (evs_id = pt_sc_id)  
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Event fee[other]' AND evs_transtion_type = 'Event fee[other]'

UPDATE sm_payment_transaction INNER JOIN sm_event_student_entrolled ON (evs_id = pt_sc_id)  
SET pt_stu_id =  evs_stu_id
WHERE pt_stu_id = 0 AND pt_tran_u_type = 'Event fee[other]' AND evs_transtion_type = 'Event fee[other]'

-----

SELECT * FROM sm_payment_transaction 
WHERE pt_stu_id = 0 