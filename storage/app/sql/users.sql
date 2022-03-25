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
-- Data for Name: user_groups; Type: TABLE DATA; Schema: public; Owner: akashs_laoffline123
--

INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (1, 'Admin', '[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36]', '[37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72]', 1, 0, '2022-02-06 18:19:42', '2022-02-06 18:19:42');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (21, 'Receptionist', '[9,17,20,23,27]', '[56,59,63,53,45]', 2, 0, '2022-02-06 18:22:08', '2022-02-06 18:34:38');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (22, 'Data Entry', '[7]', '[43]', 3, 0, '2022-02-06 18:35:51', '2022-02-06 18:35:51');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (23, 'Accounts', '[7]', '[43]', 4, 0, '2022-02-06 18:36:26', '2022-02-06 18:36:26');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (24, 'Data Entry Higher Level', '[7]', '[43]', 5, 0, '2022-02-06 18:36:52', '2022-02-06 18:36:52');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (25, 'Management', '[7]', '[43]', 6, 0, '2022-02-06 18:37:11', '2022-02-06 18:37:11');
INSERT INTO user_groups (id, name, access_permissions, modify_permissions, roles_id, is_delete, created_at, updated_at) VALUES (26, 'Field Work', '[7]', '[43]', 7, 0, '2022-02-06 18:37:27', '2022-02-06 18:37:27');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: akashs_laoffline123
--

INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (1, 2, 'admin', '$2y$10$587lIPE/y1J/uFq7n27SpOgZZxPn/D0DOFJp8rLePEchCV6tC3nIK', '1', 0, '2022-02-06 18:19:43', '2022-02-06 18:19:43');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (2, 1, 'admin', '617b06ca9db20c82c4631ce7da599126', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (3, 4, 'anil', '617b06ca9db20c82c4631ce7da599126', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (4, 5, 'dipesh', '4329349226f902effedefb669e736d3d', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (5, 6, 'jignesh', 'aaa4b8ff353f2c929336a7637b6c3dff', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (6, 7, 'narayan', 'b7cc7597f2fa50073b4f8b0518d1157d', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (7, 8, 'vrinda', '7934ca3ea4842410eb359e579c0e7b5e', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (8, 10, 'utsav', 'f89ef45ba6452a0e2a9e45f3b0b2cd62', '1', 0, '2022-02-06 18:39:09', '2022-02-06 18:39:09');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (9, 12, 'akash', 'c6916d3c88ee8ecebd2f032ff57f3ea8', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (10, 15, 'receptionist', 'e10adc3949ba59abbe56e057f20f883e', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (11, 16, 'tejaswini', '7e1a7701d7692f26aa31d3a0c6fa657d', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (12, 19, 'testing', 'e10adc3949ba59abbe56e057f20f883e', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (13, 21, 'lavesh', 'b9dce7afc2dacfda1c446f9ed9a4c7c7', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (14, 22, 'adarsh', 'd34f16617ebcc24670b7c9b2b897f181', '0', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (15, 23, 'salman', 'c2839bed26321da8b466c80a032e4714', '0', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (16, 24, 'kishan', 'b1634c02812896b87fff3d56f89e36af', '0', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (17, 25, 'rinkesh', '07b7168167e0118130e809acf1817cb4', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (18, 27, 'yash', '50d568c1e9756eed3dc93a76cc7a157c', '0', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (19, 28, 'lavesh', '6aba9978e2e589597442c3b390332435', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (20, 29, 'auto', '9df22f196a33acd0b372fe502de51211', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (21, 30, 'priyanka', '9d1bf636cd409943f333561962548ffb', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (22, 31, 'tushar', 'df7c905d9ffebe7cda405cf1c82a3add', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');
INSERT INTO users (id, employee_id, username, password, is_active, is_delete, created_at, updated_at) VALUES (23, 32, 'jash', '89342079ba87d7e7a0d7340f11c1166b', '1', 0, '2022-02-06 18:39:10', '2022-02-06 18:39:10');


--
-- PostgreSQL database dump complete
--

