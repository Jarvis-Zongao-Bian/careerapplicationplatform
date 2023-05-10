#i.Create/DELETE/Edit/Display an Employer.
#i.Create
INSERT INTO User (userId, password, name, email, phone)
VALUES ('Test', 'Test', 'Test', 'Test@yahoo.com', '450 514 5568');

INSERT INTO Employer (userId, category, payOption, accountStatus, balance)
VALUES ('Test', 'Prime', 'Manual', 'Active', 0.0);

#i.DELETE
DELETE FROM Employer WHERE userId = 'Test';
DELETE FROM User WHERE userId = 'Test';


#i.Edit
UPDATE User
SET password= 'Test2',name='Test2',email ='Test2',phone= '450 450 4500'
WHERE userId = 'Test';

UPDATE Employer
SET category= 'Gold', payOption= 'Auto', accountStatus = 'Frozen', balance = -10
WHERE userId = 'Test';


#i.Display
SELECT User.*, Employer.accountStatus,Employer.payOption ,Employer.category ,Employer.balance
FROM User, Employer
WHERE User.userId = 'DuDuDu' AND User.userId = Employer.userId ;


#ii.Create/DELETE/Edit/Display a category by an Employer.
#Assume the category is the job category
#ii.Create a category (job) by an Employer.
INSERT INTO sic55311.Job (jobId, jobTitle, description, numberOfOpening, postDate, category, status)
VALUES ('1665', 'Data Analyst', 'Analyze data', 5, '2021-08-10', 'IT support', 'Open');

INSERT INTO sic55311.Post (jobId, employerId)
VALUES ('1665', 'DuDuDu');

#ii.DELETE a category IT jobs by an Employer.
DELETE Post, Job FROM Post INNER JOIN Job
WHERE Post.jobId = Job.jobId AND  Post.employerId ='DuDuDu' AND Job.category ='IT support';

#ii.DELETE a category IT jobs by an Employer.
DELETE FROM Post WHERE employerId='DuDuDu' AND jobId IN (SELECT jobId FROM Job WHERE category='IT support');
DELETE FROM Job WHERE category ='IT support' AND jobId IN (SELECT jobId FROM Post WHERE employerId='DuDuDu');

#ii.Display a category IT support to IT jobs by an Employer.
SELECT Post.employerId , Job.*
FROM Post INNER JOIN Job
WHERE Post.jobId = Job.jobId AND  Post.employerId ='DuDuDu' AND Job.category ='IT' ;


#iii.Post a new job by an employer.
INSERT INTO Job (jobId, jobTitle, description, numberOfOpening, postDate, category, status)
VALUES ('1520', 'Customer service', 'Provide phone support to assist', 4, '2021-09-10', 'Customer service', 'Open');


#iv.Provide a job offer for an employee by an employer.
INSERT INTO Offer (employerId, jobId, applicantId, postDate, status)
VALUES ('lucky7', '9002', 'Xman', '2021-10-10', 'Open');

#v.Report of a posted job by an employer (Job title AND description, date posted, list of employees applied to the job AND status of each application).
SELECT Job.jobTitle,Job.description ,Job.postDate, Application.applicantId,Application.status
FROM Job, Application, Post, Employer, User
WHERE Employer.userId =Post.employerId AND Post.jobId =Job.jobId AND Application.jobId =Job.jobId AND Employer.userId =User.userId AND User.name = 'Tiger';

#vi.Report of posted jobs by an employer during a specific period of time (Job title, date posted, short description of the job up to 50 characters, number of needed employees tothe post, number of applied jobs to the post, number of accepted offers).
SELECT Job.jobTitle,Job.description ,Job.postDate, Application.applicantId,Application.status,
       (SELECT count(Application.status)
        FROM Application, Job, Post
        WHERE Application.status ='applied' AND Application.jobId =Job.jobId AND Post.JobId  = Job.jobId AND Post.EmployerId = 'Tiger')AS numberofapplied,
       (SELECT count(Offer.status)
        FROM Offer, Job, User
        WHERE Offer.status ='Accept' AND Offer.jobId =Job.jobId AND Offer.employerId =User.userId AND User.name = 'Tiger') as numberofaccept
FROM Job, Application, Post, Employer, User
WHERE Employer.userId =Post.employerId AND Post.jobId =Job.jobId AND Application.jobId =Job.jobId AND Employer.userId =User.userId AND User.name = 'Tiger' AND Job.postDate between '2019-10-10' AND '2025-10-10';

#vii.Create/DELETE/Edit/Display an Employee.
#vii.Create
INSERT INTO User (userId, password, name, email, phone)
VALUES ('Test2', 'Test2', 'Test2', 'Test@yahoo.com', '450 514 5568');

INSERT INTO Applicant (userId, category, payOption, accountStatus, balance)
VALUES ('Test2', 'Basic', 'Manual', 'Active', 0.0);

#vii.DELETE
DELETE FROM Applicant WHERE userId = 'Test2';
DELETE FROM User WHERE userId = 'Test2';

#vii.Edit
UPDATE User
SET password= 'Test22',name='Test22',email ='Test2@hotmail.com',phone= '450 450 4500'
WHERE userId = 'Test2';

UPDATE Applicant
SET category= 'Gold', payOption= 'Auto', accountStatus = 'Active', balance = 100
WHERE userId = 'Test2';

#vii.Display
SELECT User.*, Applicant.accountStatus,Applicant.payOption ,Applicant.category ,Applicant.balance
FROM User, Applicant
WHERE User.userId = 'DuDuDu' AND User.userId = Applicant.userId ;


#viii.Search for a job by an employee.
SELECT Job.*
FROM Job
WHERE jobTitle like 'Data%';

#ix.Apply for a job by an employee.
INSERT INTO Application (jobId, applicantId, applieddate, status)
VALUES ('1517', 'DuDuDu', '2020-10-21', 'applied');

#x.Accept/Deny a job offer by an employee.
UPDATE Offer
SET status= 'Accept'
WHERE applicantId = 'Tired';
UPDATE Offer
SET status= 'Deny'
WHERE applicantId = 'DuDuDu';


#xi.Withdraw FROM an applied job by an employee.
DELETE FROM Application WHERE applicantId = 'DuDuDu' AND jobId = 1517;


#xii.DELETE a profile by an employee
#DELETE Employee
#DELETE payment information
DELETE PayMethod , PayWith  FROM PayMethod  INNER JOIN PayWith
WHERE PayMethod.accountNumber = PayMethod.accountNumber AND  PayWith.userId ='Xman';
#DELETE job application
DELETE FROM Offer
WHERE Offer.applicantId ='Xman';
DELETE FROM Application
WHERE Application.applicantId ='Xman';
#DELETE employee profile
DELETE FROM Applicant
WHERE Applicant.userId ='Xman';


#xiii.Report of applied jobs by an employee during a specific period of time (Job title, date applied, short description of the job up to 50 characters, status of the application).
SELECT Job.jobTitle,Application.applieddate,Job.description, Application.status
FROM Job, Applicant, Application, User
WHERE Application.jobId = Job.jobId AND Application.applicantId =Applicant.userId AND Applicant.userId = User.userId AND User.name = 'Lucky' AND applieddate between '2019-10-10' AND '2025-10-10';

#xiv.Add/DELETE/Edit a method of payment by a user.

INSERT INTO sic55311.PayWith (userId, accountNumber, paytype, isDefault)
VALUES ('DuDuDu', '4504545596544856', 'debit', 0);

#xv.Add/DELETE/Edit an automatic payment by a user.
UPDATE Employer
SET payOption= 'Auto'
WHERE userId = 'DuDuDu';

#xvi.Make a manual payment by a user.

INSERT INTO sic55311.PayMethod (accountNumber, paytype, expDate, CVN)
VALUES ('4504545451544846', 'credit', '11/22', NULL);

INSERT INTO sic55311.PayWith (userId, accountNumber, paytype, isDefault)
VALUES ('DuDuDu', '4504545451544846', 'credit', 0);


#xvii.Report of all users by the administrator for employers or employees (Name, email, category, status, balance.
SELECT User.name,User.email,Employer.category,Employer.accountStatus,Employer.balance
FROM User, Employer
WHERE User.userId = Employer.userId;

#xviii.Report of all outstANDing balance accounts (User name, email, balance, since when the account is suffering).

SELECT User.name, User.email, Employer.balance, Employer.expDate
FROM User
         INNER join Employer
                    ON User.userId = Employer.userId
WHERE Employer.accountStatus ='Frozen'
UNION
SELECT User.name, User.email, Applicant.balance, Applicant.expDate
FROM User
         INNER join Applicant
                    ON User.userId = Applicant.userId
WHERE Applicant.accountStatus ='Frozen';