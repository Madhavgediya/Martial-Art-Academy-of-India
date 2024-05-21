/*
SELECT bt_name , stu_gr_no, stu_first_name, stu_last_name
from sm_batch_time INNER JOIN sm_student ON (stu_batchtime = bt_id)
where stu_br_id = 1 AND bt_name = '07:00 AM to 08:00 AM'


SELECT bt_name , stu_gr_no, stu_first_name, stu_last_name
from sm_batch_time INNER JOIN sm_student ON (stu_batchtime = bt_id)
where stu_br_id = 1 AND bt_name = '07:00 PM to 08:00 PM'
*/

UPDATE
SELECT sc_total_fee , brt_amount_month
FROM sm_student_course
INNER JOIN sm_branch_type ON (sc_brt_id = brt_id)
WHERE sc_is_current = 1 AND sc_stu_id = 1



`sc_id`, `sc_stu_id`, `sc_cd_id`, `sc_br_id`, `sc_brt_id`, `sc_co_id`, `sc_be_id`, `sc_joined_date`, ``, `sc_total_paid`, `sc_full_fee_paid`, ``
UPDATE



SELECT sc_total_fee , brt_amount_month

UPDATE sm_student_course
INNER JOIN sm_branch_type ON (sc_brt_id = brt_id)
SET sc_total_fee = (sc_total_fee+brt_amount_month) ,  sc_full_fee_paid = 'N'
WHERE sc_is_current = 1 AND sc_stu_id = 1


-- list of count of result categories per exam and student.
SELECT count(*), exre_ex_id, exre_stu_id FROM `sm_exam_result`
GROUP BY exre_ex_id, exre_stu_id

-- Current course 
SELECT  DATEDIFF("2017-03-26",sc_joined_date), DATEDIFF(sc_joined_date,"2017-03-26") diff_2,   sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,co_name, DATE_FORMAT(sc_joined_date,'%d-%m-%Y') as sc_joined_date   FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id ) WHERE sc_stu_id = 9 ORDER BY sc_id ASC

-- Checking how many course is given to student.
SELECT COUNT(*), sc_stu_id FROM `sm_student_course` group by sc_stu_id Having count(*) > 1


-- this is the qurey after union.
SELECT   e1.exs_co_id,  e1.exs_be_id, stu_id,  e1.exs_ex_id, be_name_for, e1.exs_result_status, e1.exs_result_marks, e1.exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, e1.exs_total_marks, co_name, M.eca_total_marks , be_belt_exam_fee, e1.exs_fee, e1.exs_paid, IF(e1.exs_id IS NULL,0,e1.exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name FROM sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (be_belt_duration != 0  AND sc_be_id = be_id )
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
LEFT JOIN sm_exam_student_entrolled e1 ON (stu_id = e1.exs_stu_id AND sc_be_id = e1.exs_be_id AND sc_co_id = e1.exs_co_id  )
LEFT JOIN sm_exam_student_entrolled e2 ON (e2.exs_ex_id = 6 AND stu_id = e2.exs_stu_id AND sc_be_id = e2.exs_be_id AND sc_co_id = e2.exs_co_id  )
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_belt_duration != 0  AND be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)
WHERE sc_is_current = 1 AND (e1.exs_id IS NULL OR  (e1.exs_result_status = 'F' OR e1.exs_result_status = 'A' ) )  AND (stu_status = 'A' OR (stu_status = 'I' AND (DATEDIFF(now(),stu_deactivation_date) < 30) )) 
AND e2.exs_be_id IS NULL AND e2.exs_stu_id IS NULL AND e2.exs_co_id IS NULL  AND  stu_br_id= 1 AND CONCAT(sc_stu_id,'-',sc_be_id,'-',sc_co_id) NOT IN (SELECT DISTINCT CONCAT(exs_stu_id , '-',exs_be_id,'-',exs_co_id) FROM sm_exam_student_entrolled WHERE exs_result_status = 'P' AND exs_ex_id != 6 )  AND  ((sc_half_course = 0 AND DATEDIFF("2018-07-03",sc_joined_date) >= (be_belt_duration+sc_additional_days)) OR (sc_half_course = 1 AND DATEDIFF("2018-07-03",sc_joined_date) >= 25)) 
sc_stu_id = 3
  GROUP BY sc_co_id, sc_be_id , sc_stu_id  order by stu_gr_no asc

