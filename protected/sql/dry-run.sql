-- Dry-Run Database Script


-- Insert the Department entity
INSERT INTO departments(dept_code, dept_name) VALUES('TST', 'Test Department');

-- Insert the Position entity
INSERT INTO positions(pos_desc) VALUES('Test Position');

-- Insert the Employee entity
INSERT INTO employees(emp_id, emp_lname, emp_fname, username, password, position, main_dept)
VALUES('1', 'Test', 'Test', 'test_user', '$2y$10$3mOG55TjQU2kmnMimk1jt.CloWDyP03/oj0E99pY/H57nifzDa2Lu', '1', '1');

-- Insert the Security Role Type
INSERT INTO user_types(type_desc) VALUES('Test Role');

-- Insert Allowable actions
INSERT INTO user_actions VALUES('SYS', 'MU/MS/MD/MM/MP', '1');
INSERT INTO user_actions VALUES('IP', 'IPM/IPMRPT', '1');

-- Insert the User Account
INSERT INTO user_main(emp_ref, type_ref, dept_ref, pos_ref) 
VALUES('1', '1', '1', '1');