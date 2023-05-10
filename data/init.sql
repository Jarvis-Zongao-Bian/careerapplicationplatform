CREATE DATABASE sic55311;
USE sic55311;

CREATE TABLE User
(
    userId   VARCHAR(25) NOT NULL PRIMARY KEY,
    password VARCHAR(25) NOT NULL,
    name     VARCHAR(25) NOT NULL,
    email    VARCHAR(50) NOT NULL,
    phone    VARCHAR(20)
);

CREATE TABLE Employer
(
    userId        VARCHAR(25),
    category      VARCHAR(25) DEFAULT 'Prime',
    payOption     VARCHAR(10) DEFAULT 'Manual',
    accountStatus VARCHAR(25) DEFAULT 'Frozen',
    balance       REAL        DEFAULT 0.0,
    expDate       DATE,
    PRIMARY KEY (userId),
    FOREIGN KEY (userId) REFERENCES User (userId)
);

CREATE TABLE Applicant
(
    userId        VARCHAR(25),
    category      VARCHAR(25) DEFAULT 'Basic',
    payOption     VARCHAR(10) DEFAULT 'Manual',
    accountStatus VARCHAR(25) DEFAULT 'Frozen',
    balance       REAL        DEFAULT 0.0,
    expDate       DATE,
    PRIMARY KEY (userId),
    FOREIGN KEY (userId) REFERENCES User (userId)
);

CREATE TABLE Administrator
(
    userId VARCHAR(25),
    PRIMARY KEY (userId),
    FOREIGN KEY (userId) REFERENCES User (userId)
);

CREATE TABLE Job
(
    jobId           INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    jobTitle        VARCHAR(30)     NOT NULL,
    description     VARCHAR(50)     NOT NULL,
    numberOfOpening INT(3) UNSIGNED NOT NULL,
    postDate        DATE            NOT NULL,
    category        VARCHAR(30)     NOT NULL,
    status          VARCHAR(30)     NOT NULL DEFAULT 'Open'
);

CREATE TABLE PayMethod
(
    accountNumber VARCHAR(25),
    payType       VARCHAR(10),
    expDate       VARCHAR(5),
    CVN           VARCHAR(4),
    PRIMARY KEY (accountNumber, payType)
);

CREATE TABLE Post
(
    jobId      INT(10) UNSIGNED,
    employerId VARCHAR(25),
    PRIMARY KEY (jobId),
    FOREIGN KEY (jobId) REFERENCES Job (jobId),
    FOREIGN KEY (employerId) REFERENCES Employer (userId)
);

CREATE TABLE Application
(
    jobId       INT(10) UNSIGNED,
    applicantId  VARCHAR(25),
    appliedDate DATE,
    status      VARCHAR(15) DEFAULT 'Applied',
    PRIMARY KEY (jobId, applicantId),
    FOREIGN KEY (jobId) REFERENCES Job (jobId),
    FOREIGN KEY (applicantId) REFERENCES Applicant (userId)
);

CREATE TABLE Offer
(
    employerId VARCHAR(25),
    jobId      INT(10) UNSIGNED,
    applicantId VARCHAR(25),
    postDate   DATE,
    status     VARCHAR(10) DEFAULT 'Active',
    PRIMARY KEY (jobId, applicantId),
    FOREIGN KEY (jobId) REFERENCES Job (jobId),
    FOREIGN KEY (applicantId) REFERENCES Applicant (userId),
    FOREIGN KEY (employerId) REFERENCES Employer (userId)
);

CREATE TABLE PayWith
(
    userId        VARCHAR(25),
    accountNumber VARCHAR(25),
    payType       VARCHAR(10) NOT NULL,
    isDefault     BOOLEAN DEFAULT 0,
    PRIMARY KEY (userId, accountNumber),
    FOREIGN KEY (userId) REFERENCES User (userId)
);

CREATE TABLE Log
(
    userId      VARCHAR(25),
    activity    VARCHAR(100),
    time        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userId, time),
    FOREIGN KEY (userId) REFERENCES User (userId)
);

INSERT INTO sic55311.User (userId, password, name, email, phone)
VALUES ('1234', 'Yahoo', '1234', '1234@yahoo.com', '450 454 4568'),
       ('Admin', 'Admin', 'Admin', 'Admin@hotmail.com', '911'),
       ('DuDuDu', 'dudu', 'Dudu', 'DuDu05@qq.com', '485 548 1258'),
       ('fish1', 'fishfish', 'Fish', 'fish@hotmail.com', '514 555 5555'),
       ('lucky7', '7777', 'Lucky', 'Lucky@hotmail.com', '777 777 7777'),
       ('Spider', 'SSSS', 'Spider', 'Spider@hotmail.com', '555 565 5514'),
       ('STAT', '2FMI', 'Stat', 'Stat@email.com', '451 563 8546'),
       ('Tech', 'Tech1', 'Tech', 'Tech@info.com', '1 805 852 8546'),
       ('Tiger', 'TigerTiger', 'Tiger', 'Tiger@gmail.com', '514 444 4444'),
       ('Tired', '5555', 'TiredMan', 'Tired@qq.com', '555 555 5555'),
       ('Xman', 'XXXX', 'Xman', 'Xman@hotmail.com', '000 000 0001'),
       ('Nobody', 'Nobody1', 'Nobody', 'Nobody@hotmail.com', '000 000 0000');


INSERT INTO sic55311.Employer (userId, category, payOption, accountStatus, balance,expDate)
VALUES ('DuDuDu', 'Prime', 'Manual', 'Active', 785.55, '2022-10-15'),
       ('lucky7', 'Prime', 'Manual', 'Frozen', -15.75, '2021-09-15'),
       ('Spider', 'Prime', 'Manual', 'Active', 15.0, '2022-09-20'),
       ('STAT', 'Prime', 'Manual', 'Active', 515.58, '2022-10-15'),
       ('Tech', 'Gold', 'Manual', 'Active', 28481.0, '2022-09-20'),
       ('Tiger', 'Prime', 'Manual', 'Frozen', -563.9, '2021-09-15'),
       ('Xman', 'Gold', 'Manual', 'Active', 25.21, '2022-09-15');

INSERT INTO sic55311.Applicant (userId, category, payOption, accountStatus, balance,expDate)
VALUES ('1234', 'Basic', 'Manual', 'Frozen', -10.0, '2021-09-15'),
       ('DuDuDu', 'Prime', 'Manual', 'Active', 213.0, '2022-09-18'),
       ('fish1', 'Prime', 'Manual', 'Active', 515.58, '2022-09-18'),
       ('lucky7', 'Gold', 'Manual', 'Active', 5285.0, '2022-09-20'),
       ('Spider', 'Gold', 'Manual', 'Active', 5254.0, '2022-09-20'),
       ('Tired', 'Basic', 'Manual', 'Frozen', -453.54, '2021-09-15'),
       ('Xman', 'Prime', 'Manual', 'Active', 12.52, '2022-09-18'),
       ('Nobody', 'Prime', 'Manual', 'Frozen', -12.52, '2022-09-18');

INSERT INTO sic55311.Administrator (userId)
VALUES ('Admin');

INSERT INTO sic55311.Job (jobId, jobTitle, description, numberOfOpening, postDate, category, status)
VALUES ('1515', 'Customer service', 'Provide chat support to assist', 5, '2021-08-10', 'Customer service', 'Open'),
       ('1516', 'Pick Packer', 'Warehouse staff perform a number of tasks', 3, '2021-11-10', 'Warehouse ', 'Open'),
       ('1517', 'Sales Associates', 'Deliver a high level of customer service', 2, '2021-10-10', 'Customer service',
        'Open'),
       ('3002', 'Data Entry Specialist', 'support of Algoluxs data annotation', 4, '2021-09-15', 'IT', 'Open'),
       ('4001', 'Data Analyst', 'Predict the Data', 3, '2021-07-15', 'IT', 'Open'),
       ('4002', 'Data Management', 'Manage the Data, Excel, MS Word', 5, '2021-07-16', 'IT', 'Open'),
       ('4003', 'Database designer', 'Designing Database', 2, '2021-07-20', 'IT', 'Open'),
       ('4004', 'Data Specialist', 'Data annotation', 4, '2021-07-30', 'IT', 'Open'),
       ('4005', 'Data entry clerk', 'Spreadsheet, Excel, MS Word', 2, '2021-07-21', 'IT', 'Open'),
       ('4006', 'Data clencer', 'Delete the extra data', 2, '2021-07-21', 'IT', 'Open'),
       ('9001', 'peer support worker', 'Hospital/medical facility or clinic', 6, '2021-09-15', 'support worker',
        'Open'),
       ('9002', 'Apple Specialist', 'create the energy and excitement', 1, '2021-10-14', 'Customer service', 'Closed');


INSERT INTO sic55311.PayWith (userId, accountNumber, payType, isDefault)
VALUES ('1234', '4504545451544856', 'credit', 0),
       ('DuDuDu', '2134251234215984', 'credit', 0),
       ('STAT', '8919846548918918', 'credit', 0),
       ('Tiger', '2591815649848191', 'debit', 0),
       ('Tired', '0980562284918541', 'debit', 0),
       ('Xman', '2959848191519489', 'debit', 1);

INSERT INTO sic55311.PayMethod (accountNumber, payType, expDate, CVN)
VALUES ('0980562284918541', 'debit', '30/48', '245'),
       ('2134251234215984', 'credit', '09/21', NULL),
       ('2591815649848191', 'debit', '19/54', '481'),
       ('2959848191519489', 'debit', '21/36', '854'),
       ('4504545451544856', 'credit', '11/22', NULL),
       ('8919846548918918', 'credit', '09/33', NULL);


INSERT INTO sic55311.Post (jobId, employerId)
VALUES ('1515', 'DuDuDu'),
       ('4001', 'STAT'),
       ('3002', 'Tech'),
       ('1516', 'Tiger'),
       ('1517', 'Xman'),
       ('4002', 'DuDuDu'),
       ('4003', 'DuDuDu'),
       ('4004', 'DuDuDu'),
       ('4005', 'DuDuDu'),
       ('4006', 'DuDuDu');



INSERT INTO sic55311.Offer (employerId, jobId, applicantId, postDate, status)
VALUES ('lucky7', '9002', 'DuDuDu', '2021-10-10', 'Accept'),
       ('lucky7', '9002', 'Tired', '2021-10-10', 'Deny'),
       ('Tiger', '1516', 'DuDuDu', '2021-10-10', 'Accept');


INSERT INTO sic55311.Application (jobId, applicantId, Applieddate, status)
VALUES ('1515', 'DuDuDu', '2021-11-21', 'Applied'),
       ('1516', 'DuDuDu', '2021-12-21', 'Applied'),
       ('1516', 'fish1', '2022-02-23', 'Applied'),
       ('1516', '1234', '2022-02-23', 'Applied'),
       ('1516', 'lucky7', '2021-12-21', 'Applied'),
       ('1516', 'Spider', '2022-02-21', 'Applied'),
       ('1516', 'Xman', '2021-01-21', 'Applied'),
       ('1517', 'lucky7', '2021-12-21', 'Applied'),
       ('1517', 'Xman', '2022-01-11', 'Applied'),
       ('3002', 'Spider', '2021-12-21', 'Applied'),
       ('4001', 'DuDuDu', '2020-10-21', 'Applied'),
       ('4001', 'Xman', '2020-11-25', 'Applied'),
       ('4002', 'lucky7', '2021-12-21', 'Applied'),
       ('4003', 'lucky7', '2021-12-21', 'Applied'),
       ('4004', 'lucky7', '2021-12-21', 'Applied');