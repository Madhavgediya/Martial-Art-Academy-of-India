SELECT  DISTINCT  evs_fee, stu_br_id, stu_id, evs_ev_id,  evs_result_status,evs_result_marks,evs_enroll_next,  evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name, brt_name,be_name_for be_name, be_name_for,co_name, 'student' as stu_or_other   FROM  sm_student
LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1 )
LEFT JOIN sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id) 
 LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'student'  AND stu_id = evs_stu_id)  WHERE  stu_br_id= 1 UNION SELECT  DISTINCT  evs_fee, stu_br_id, stu_id, evs_ev_id, evs_result_status,evs_result_marks,evs_enroll_next,evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name , '' brt_name, '' be_name, '' be_name_for,'' co_name, 'other' as stu_or_other  FROM  sm_student_other  LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'other' AND stu_id = evs_stu_id )  WHERE  stu_br_id= 1 order by stu_gr_no asc LIMIT 0, 1000



-- DELETE FROM sm_course WHERE co_br_id !=1
-- DELETE FROM sm_course_belt WHERE cb_br_id !=1
-- DELETE FROM sm_belt WHERE be_br_id !=1
-- DELETE FROM sm_exam_categories WHERE exc_br_id !=1
-- DELETE FROM sm_exam_categories_allocation WHERE eca_br_id !=1
-- DELETE FROM sm_exam WHERE ex_br_id  !=1 
-- DELETE FROM sm_exam_student_entrolled WHERE exs_ex_id IN (SELECT ex_id from sm_exam WHERE ex_br_id !=1)
-- DELETE FROM sm_ DELETE FROM sm_course WHERE co_br_id !=1
-- DELETE FROM sm_course_belt WHERE cb_br_id !=1
-- DELETE FROM sm_belt WHERE be_br_id !=1
-- DELETE FROM sm_exam_categories WHERE exc_br_id !=1
-- DELETE FROM sm_exam_categories_allocation WHERE eca_br_id !=1
-- DELETE FROM sm_exam WHERE ex_br_id  !=1 
-- DELETE FROM sm_exam_student_entrolled WHERE exs_ex_id IN (SELECT ex_id from sm_exam WHERE ex_br_id !=1)
-- DELETE FROM sm_

  
-- 21 Jul 2018 9:48 PM
ALTER TABLE sm_invoice_products ADD invpro_gst FLOAT NOT NULL DEFAULT '0' AFTER invpro_pro_desc ;
ALTER TABLE sm_invoice ADD inv_gst_amount FLOAT NOT NULL DEFAULT '0' AFTER inv_total_amount;

-- 05 Sep 2018 7:31 PM
ALTER TABLE sm_exam_student_entrolled ADD exs_certificate CHAR(1) NOT NULL DEFAULT 'N' AFTER exs_transction_no, ADD exs_belt CHAR(1) NOT NULL DEFAULT 'N' AFTER exs_certificate;

-- TRUNCATE sm_activeinactive;

ALTER TABLE sm_activeinactive CHANGE ac_id ac_id INT(11) NOT NULL AUTO_INCREMENT; 

UPDATE sm_branch SET br_add_1 = '310, paltinum complex'; 
UPDATE sm_branch SET br_add_2 = 'kalva chowk'; 
UPDATE sm_branch SET br_city = 'jungadh' ;
UPDATE sm_branch SET br_contact_p_phone1 = '7878273458';

ALTER TABLE sm_activeinactive ADD ac_remarkrs VARCHAR(255) NOT NULL DEFAULT '' AFTER ac_update_by_id; 
-- 16-sep-2018 2:13 PM
ALTER TABLE sm_contact CHANGE con_no_of_followup con_no_of_followup INT(11) NULL DEFAULT '0'; 
ALTER TABLE sm_student ADD stu_password VARCHAR(255) NOT NULL DEFAULT '' AFTER stu_gr_no; 

-- 21-sep-2018 10:31 PM
ALTER TABLE sm_log ADD log_course_change_date DATE NULL AFTER log_date; 

-- 23-sep-2018 01:31 PM
ALTER TABLE sm_book ADD book_language VARCHAR(100) NULL DEFAULT '' AFTER book_publication, ADD book_price FLOAT NULL DEFAULT '0' AFTER book_language; 

-- 12-Oct-2018 10:45 AM
ALTER TABLE sm_invoice_products ADD invpro_used VARCHAR(20) NULL DEFAULT '' AFTER invpro_psb_barcode; 
ALTER TABLE sm_invoice_products ADD invpro_cgst_amount FLOAT NULL DEFAULT '0' AFTER invpro_gst; 
ALTER TABLE sm_invoice_products ADD invpro_sgst_amount FLOAT NULL DEFAULT '0' AFTER invpro_cgst_amount 
ALTER TABLE sm_invoice ADD inv_sgst_amount FLOAT NULL DEFAULT '0' AFTER inv_gst_amount; 
ALTER TABLE sm_invoice CHANGE inv_gst_amount inv_cgst_amount FLOAT NOT NULL DEFAULT '0'; 

-- 03-Nov-2018 11:45 PM
ALTER TABLE sm_event ADD ev_end_date DATE NULL DEFAULT NULL AFTER ev_date; 


-- 16-Nov-2018 10:40 PM
ALTER TABLE sm_dealer ADD del_igst CHAR(1) NULL DEFAULT 'N' COMMENT 'Y and N' AFTER del_status;
UPDATE sm_dealer ADD  SET del_igst =  'N';

-- 20-Nov-2018 02:07 PM
ALTER TABLE sm_invoice ADD inv_igst_amount FLOAT NULL DEFAULT '0' AFTER inv_sgst_amount; 
ALTER TABLE sm_invoice_products ADD invpro_igst_amount FLOAT NULL DEFAULT '0' AFTER invpro_sgst_amount; 

-- 20-Nov-2018 03:47 PM
ALTER TABLE sm_invoice ADD inv_purchase_invoice_no VARCHAR(25) NULL DEFAULT '' AFTER inv_customers_id;

-- 20-Nov-2018 04:21 PM
ALTER TABLE sm_invoice_products ADD invpro_pro_qty_sold INT NULL DEFAULT '0' AFTER invpro_used, ADD invpro_pro_qty_dead INT NULL DEFAULT '0' AFTER invpro_pro_qty_sold;

-- 16-Dec-2018 03:31 PM
ALTER TABLE sm_invoice_products_sale ADD invpro_id_purchase INT NULL AFTER invpro_pro_qty_dead; 
ALTER TABLE sm_invoice_products ADD invpro_id_purchase INT NULL DEFAULT NULL AFTER invpro_pro_qty_dead; 
/*
UPDATE sm_invoice_products IP INNER JOIN (SELECT SUM(invpro_pro_qty) as invpro_pro_qty, invpro_id_purchase FROM sm_invoice_products_sale GROUP BY invpro_id_purchase) as M ON (IP.invpro_id = M.invpro_id_purchase) SET IP.invpro_pro_qty_sold = M.invpro_pro_qty AND M.invpro_id_purchase IN (1) 
*/

-- 24-Dec-2018 03:31 PM
ALTER TABLE sm_invoice_products_sale ADD invpro_status CHAR(1) NOT NULL DEFAULT 'Y' AFTER invpro_id_purchase; 

-- 30-Dec-2018 1:00 PM
ALTER TABLE sm_invoice_products ADD invpro_pro_qty_return INT NOT NULL DEFAULT '0' AFTER invpro_id_purchase; 

-- 30-Jan-2019 10:00 AM
ALTER TABLE sm_payment_transaction ADD pt_stu_id INT NOT NULL DEFAULT '0' AFTER pt_br_id;

-- 16-Feb-2019 4:50 PM
ALTER TABLE sm_invoice_products_sale ADD invpro_pro_qty_return INT NOT NULL DEFAULT '0' AFTER invpro_id_purchase; 

-- 21-Feb-2019 9:50 AM
ALTER TABLE sm_payment_transaction ADD pt_remarks VARCHAR(1000) NULL DEFAULT NULL AFTER pt_stu_id; 

-- 23-Feb-2019 10:25 AM
ALTER TABLE sm_log CHANGE log_action log_action VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 
UPDATE sm_log SET log_action =  'return_product_qty_Purchase' WHERE log_action = 'return_product_qty_Purcha';

-- 24-Feb-2019 07:20 AM
CREATE TABLE sm_customer (
  del_id int(11) NOT NULL,
  del_first_name varchar(50) NOT NULL,
  del_last_name varchar(50) NOT NULL,
  del_company_name varchar(50) NOT NULL,
  del_phone varchar(15) NOT NULL DEFAULT '',
  del_phone_2 varchar(15) NOT NULL DEFAULT '',
  del_email varchar(100) DEFAULT NULL,
  del_office_address varchar(100) NOT NULL,
  del_city varchar(50) NOT NULL,
  del_state_id int(11) NOT NULL,
  del_postal_code varchar(15) NOT NULL,
  del_gstno varchar(15) NOT NULL,
  del_panno varchar(50) DEFAULT NULL,
  del_whatsappno varchar(15) DEFAULT NULL,
  del_status char(1) NOT NULL DEFAULT 'A',
  del_igst char(1) DEFAULT 'N' COMMENT 'Y and N',
  del_br_id int(11) DEFAULT NULL,
  del_create_date datetime DEFAULT NULL,
  del_create_by_id int(11) DEFAULT NULL,
  del_update_date datetime DEFAULT NULL,
  del_update_by_id int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE sm_customer
  ADD PRIMARY KEY (del_id);

ALTER TABLE sm_customer
  MODIFY del_id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sm_invoice_sale` ADD `inv_sale_type` CHAR(1) NOT NULL DEFAULT 'S' AFTER `inv_purchase_invoice_no`; 

-- 17 March 2019
ALTER TABLE `sm_invoice` ADD `inv_sale_type` CHAR(1) NULL DEFAULT NULL AFTER `inv_purchase_invoice_no`; 
ALTER TABLE `sm_branch` ADD `br_logo_path` VARCHAR(1000) NULL DEFAULT '' AFTER `br_batch_time`; 

-- dist/img/logo.png
UPDATE `sm_branch` SET `br_logo_path` = 'dist/img/logo.png' WHERE `sm_branch`.`br_id` = 1; 

-- 19 March 2019 7:28 PM Fixting
UPDATE `sm_student_course` SET `sc_additional_days` = '30' WHERE `sm_student_course`.`sc_id` = 634; 

-- 19 March 2019 7:28 PM Fixting
DELETE FROM `sm_student_course` WHERE `sm_student_course`.`sc_id` = 72;
DELETE FROM `sm_student_course` WHERE `sm_student_course`.`sc_id` = 480;
DELETE FROM `sm_student_course` WHERE `sm_student_course`.`sc_id` = 548;

-- 31 March 2019 6:30 PM Fixting
ALTER TABLE `sm_invoice` ADD `inv_invd_id` INT NULL DEFAULT '0' AFTER `inv_id`; 
ALTER TABLE `sm_invoice_sale` ADD `inv_invd_id` INT NULL DEFAULT '0' AFTER `inv_id`; 

-- 14 Jul 2019
ALTER TABLE `sm_invoice` CHANGE `inv_billing_name` `inv_billing_name` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_email` `inv_billing_email` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_contact1` `inv_billing_contact1` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_contact2` `inv_billing_contact2` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_address1` `inv_billing_address1` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_address2` `inv_billing_address2` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_city` `inv_billing_city` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_state` `inv_billing_state` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_country` `inv_billing_country` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_postal` `inv_billing_postal` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_name` `inv_shipping_name` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_contact1` `inv_shipping_contact1` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_contact2` `inv_shipping_contact2` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_address1` `inv_shipping_address1` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_address2` `inv_shipping_address2` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_city` `inv_shipping_city` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_state` `inv_shipping_state` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `sm_invoice` CHANGE `inv_shipping_country` `inv_shipping_country` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_postal` `inv_shipping_postal` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_status` `inv_status` CHAR(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'G=Generated,S=Shippged,D=Delivered,R=Returned,N=Deleted', CHANGE `inv_payment_method` `inv_payment_method` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_payment_status` `inv_payment_status` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_mode` `inv_delivery_mode` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_notes` `inv_delivery_notes` VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '', CHANGE `inv_delivery_date` `inv_delivery_date` DATE NULL, CHANGE `inv_delivery_status` `inv_delivery_status` CHAR(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '' COMMENT 'D=Delivered', CHANGE `inv_payment_notes` `inv_payment_notes` VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_specify` `inv_delivery_specify` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_generate_date` `inv_generate_date` DATE NULL, CHANGE `inv_date` `inv_date` DATETIME NULL, CHANGE `inv_admin_id` `inv_admin_id` INT(11) NULL DEFAULT '0';
ALTER TABLE `sm_invoice` CHANGE `inv_store_id` `inv_store_id` INT(11) NULL DEFAULT '0', CHANGE `inv_total_amount` `inv_total_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_cgst_amount` `inv_cgst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_sgst_amount` `inv_sgst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_igst_amount` `inv_igst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_additional_amount` `inv_additional_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_discount_amount` `inv_discount_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_shipping_amount` `inv_shipping_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_net_amount` `inv_net_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_total_paid_amount` `inv_total_paid_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_customers_id` `inv_customers_id` INT(11) NULL DEFAULT '0', CHANGE `inv_purchase_invoice_no` `inv_purchase_invoice_no` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';
ALTER TABLE `sm_invoice_products` CHANGE `invpro_inv_id` `invpro_inv_id` INT(11) NULL, CHANGE `invpro_pro_id` `invpro_pro_id` INT(11) NULL, CHANGE `invpro_po_id_2` `invpro_po_id_2` INT(11) NULL DEFAULT '0', CHANGE `invpro_pro_qty` `invpro_pro_qty` INT(11) NULL, CHANGE `invpro_po_id` `invpro_po_id` INT(11) NULL DEFAULT '0', CHANGE `invpro_pro_desc` `invpro_pro_desc` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `invpro_gst` `invpro_gst` FLOAT NULL DEFAULT '0', CHANGE `invpro_pro_price` `invpro_pro_price` DOUBLE NULL, CHANGE `invpro_pro_price_tot` `invpro_pro_price_tot` DOUBLE NULL, CHANGE `invpro_final_pro_price` `invpro_final_pro_price` DOUBLE NULL DEFAULT '0', CHANGE `invpro_final_price_tot` `invpro_final_price_tot` DOUBLE NULL DEFAULT '0', CHANGE `invpro_psb_barcode` `invpro_psb_barcode` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '', CHANGE `invpro_pro_qty_return` `invpro_pro_qty_return` INT(11) NULL DEFAULT '0';
ALTER TABLE `sm_invoice_sale` CHANGE `inv_billing_name` `inv_billing_name` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_email` `inv_billing_email` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_contact1` `inv_billing_contact1` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_contact2` `inv_billing_contact2` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_address1` `inv_billing_address1` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_address2` `inv_billing_address2` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_city` `inv_billing_city` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_state` `inv_billing_state` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_country` `inv_billing_country` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_billing_postal` `inv_billing_postal` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_name` `inv_shipping_name` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_contact1` `inv_shipping_contact1` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_contact2` `inv_shipping_contact2` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_address1` `inv_shipping_address1` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_address2` `inv_shipping_address2` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_city` `inv_shipping_city` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_state` `inv_shipping_state` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_country` `inv_shipping_country` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_shipping_postal` `inv_shipping_postal` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_status` `inv_status` CHAR(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'G=Generated,S=Shippged,D=Delivered,R=Returned,N=Deleted', CHANGE `inv_payment_method` `inv_payment_method` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_payment_status` `inv_payment_status` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_mode` `inv_delivery_mode` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_notes` `inv_delivery_notes` VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';
ALTER TABLE `sm_invoice_sale` CHANGE `inv_delivery_date` `inv_delivery_date` DATE NULL, CHANGE `inv_delivery_status` `inv_delivery_status` CHAR(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '' COMMENT 'D=Delivered', CHANGE `inv_payment_notes` `inv_payment_notes` VARCHAR(2000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_delivery_specify` `inv_delivery_specify` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `inv_generate_date` `inv_generate_date` DATE NULL, CHANGE `inv_date` `inv_date` DATETIME NULL, CHANGE `inv_store_id` `inv_store_id` INT(11) NULL DEFAULT '0', CHANGE `inv_total_amount` `inv_total_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_cgst_amount` `inv_cgst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_sgst_amount` `inv_sgst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_igst_amount` `inv_igst_amount` FLOAT NULL DEFAULT '0', CHANGE `inv_additional_amount` `inv_additional_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_discount_amount` `inv_discount_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_shipping_amount` `inv_shipping_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_net_amount` `inv_net_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_total_paid_amount` `inv_total_paid_amount` DOUBLE NULL DEFAULT '0', CHANGE `inv_customers_id` `inv_customers_id` INT(11) NULL DEFAULT '0', CHANGE `inv_purchase_invoice_no` `inv_purchase_invoice_no` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';
ALTER TABLE `sm_invoice_products_sale` CHANGE `invpro_inv_id` `invpro_inv_id` INT(11) NULL, CHANGE `invpro_pro_id` `invpro_pro_id` INT(11) NULL, CHANGE `invpro_po_id_2` `invpro_po_id_2` INT(11) NULL DEFAULT '0', CHANGE `invpro_pro_qty` `invpro_pro_qty` INT(11) NULL, CHANGE `invpro_po_id` `invpro_po_id` INT(11) NULL DEFAULT '0', CHANGE `invpro_pro_desc` `invpro_pro_desc` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `invpro_gst` `invpro_gst` FLOAT NULL DEFAULT '0', CHANGE `invpro_cgst_amount` `invpro_cgst_amount` FLOAT NULL DEFAULT '0', CHANGE `invpro_sgst_amount` `invpro_sgst_amount` FLOAT NULL DEFAULT '0', CHANGE `invpro_igst_amount` `invpro_igst_amount` FLOAT NULL DEFAULT '0', CHANGE `invpro_pro_price` `invpro_pro_price` DOUBLE NULL, CHANGE `invpro_pro_price_tot` `invpro_pro_price_tot` DOUBLE NULL, CHANGE `invpro_pro_model` `invpro_pro_model` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_pro_serial` `invpro_pro_serial` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_pro_warranty_terms` `invpro_pro_warranty_terms` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_pro_warranty_month` `invpro_pro_warranty_month` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_pro_extended_warranty` `invpro_pro_extended_warranty` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_pro_condition` `invpro_pro_condition` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `invpro_final_pro_price` `invpro_final_pro_price` DOUBLE NULL DEFAULT '0', CHANGE `invpro_final_price_tot` `invpro_final_price_tot` DOUBLE NULL DEFAULT '0', CHANGE `invpro_psb_barcode` `invpro_psb_barcode` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '', CHANGE `invpro_used` `invpro_used` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '', CHANGE `invpro_pro_qty_sold` `invpro_pro_qty_sold` INT(11) NULL DEFAULT '0', CHANGE `invpro_pro_qty_dead` `invpro_pro_qty_dead` INT(11) NULL DEFAULT '0', CHANGE `invpro_id_purchase` `invpro_id_purchase` INT(11) NULL DEFAULT NULL, CHANGE `invpro_pro_qty_return` `invpro_pro_qty_return` INT(11) NULL DEFAULT '0', CHANGE `invpro_status` `invpro_status` CHAR(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Y';
