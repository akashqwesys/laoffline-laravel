--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: employees; Type: TABLE DATA; Schema: public; Owner: akashs_laoffline123
--

INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (1, 'Admin', '', '', '', 'admin@versatilewebtech.com', '+9100000000', '', 1, 0, '', '', '', '+910000000000', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (4, 'Anil', 'Kumar', 'Gaur', 'anil_kumar_gaur.jpg', 'anil@llaveshagarwal.com', '8347477741', '', 23, 1, 'a:1:{i:0;s:0:"";}', 'Vishal', '', '8347477741', 'Kimora Fashion', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (5, 'Dipesh', '', 'Nagpure', 'dipesh_sonu_nagpure.jpg', 'dipesh@llaveshagarwal.com', '8347477742', '', 24, 0, 'a:1:{i:0;s:0:"";}', '', '', '', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (6, 'Jignesh', '', 'Thakor', '', 'jignesh@llaveshagarwal.com', '8347477746', '', 24, 0, 'b:0;', '', '', '', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (7, 'Narayan', '', '', '', 'narayan@llaveshagarwal.com', '9824100956', '', 24, 0, 'b:0;', '', '', '', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (8, 'Vrinda', '', 'Agarwal', '', 'vrinda@llaveshagarwal.com', '8347477751', '', 23, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (10, 'Utsav', '', 'agarwal', '', '', '8347477740', '', 1, 0, 'a:1:{i:0;s:0:"";}', '', '', '', '', '', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (12, 'Akash', '', 'Shah', '', 'akash@versatilewebtech.com', '7573071683', '', 24, 1, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (15, 'Receptionist', '', '', '', 'mail@llaveshagarwal.com', '', '', 21, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (16, 'Tejaswini', '', '', '', 'patelteju04@gmail.com', '', '', 23, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (19, 'Testing', '', 'Application', '', 'testingapplication@gmail.com', '9185263741', '', 24, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (21, 'Lavesh ', '', 'Agarwal', '', 'llaveshagarwal@gmail.com', '', '', 25, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (22, 'Adarsh', '', 'Singh', '', 'adarsh@llaveshagarwal.com', '', '', 23, 0, 'a:0:{}', 'quikr', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (23, 'salman', '', '', '', 'salman@llaveshagarwal.com', '', '', 26, 0, 'a:0:{}', 'quikr', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (24, 'kishan', '', '', '', 'kishan@llaveshagarwal.com', '', '', 26, 0, 'a:0:{}', 'tejaswini', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (25, 'rinkesh', '', '', '', 'rinkesh@llaveshagarwal.com', '', '', 23, 1, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (27, 'Yash ', 'Sanjay', 'Marsade', '', 'yashmarsade26@gmail.com', '08866781489', '407 Om Sai Nagar, Dus kholi, Kharvar Nagar, Udhna Nahar, Surat', 24, 0, 'a:0:{}', 'Adarsh Singh', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (28, 'Lavesh', '', 'agarwal', '', '', '9824114068', '', 1, 0, '', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (29, 'AUTOMATED', '', '', '', 'utsav@llaveshagarwal.com', '8347477740', '', 23, 0, 'a:0:{}', '', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (30, 'Priyanka', 'Shailesh', 'Rathod', '', 'rathodpriyanka03648@gmail.com', '07226923966', '255, Priyanka society city-2, Dindoli, Udhana, Surat ', 24, 0, 'a:0:{}', 'Tejaswini Patel', '', '', 'As above same.', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (31, 'Tushar', '', 'Mahajan', '', 'test@test.com', '6351610055', 'test', 23, 0, 'a:0:{}', 'akash bhai shakti prints and pure', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (32, 'Jash', '', 'Lunia', '', 'jashlunia2004@gmail.com', '8469267669', 'C106, Radhe shyam complex, behind hari nagar 2, asha nagar, udhna, surat 394210', 23, 0, 'a:0:{}', 'Ravinder Ji Accountant', '', '', '', '', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO employees (id, firstname, middlename, lastname, profile_pic, email_id, mobile, address, user_group, excel_access, id_proof, ref_full_name, ref_pass_pic, ref_mobile, ref_address, web_login, is_delete, created_at, updated_at) VALUES (2, 'Admin', 'Admin', 'Admin', '', 'admin@admin.com', '1234567890', 'This is address', 1, 1, '', 'Refrence 1', '', '1234657890', 'This is refrence address', '2022-03-24 14:03:24', 0, '2022-02-06 18:19:42', '2022-03-24 14:03:24');


--
-- Data for Name: link_companies; Type: TABLE DATA; Schema: public; Owner: akashs_laoffline123
--



--
-- Data for Name: link_companies_logs; Type: TABLE DATA; Schema: public; Owner: akashs_laoffline123
--



--
-- PostgreSQL database dump complete
--

