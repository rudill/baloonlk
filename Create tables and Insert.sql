create database BaloonLK

use BaloonLK;

CREATE TABLE Category (
    categoryID INT PRIMARY KEY,
    categoryType VARCHAR(50) NOT NULL,
    categoryDesc VARCHAR(255)
);

CREATE TABLE Warehouse (
    warehouseID INT PRIMARY KEY,
    location VARCHAR(50) NOT NULL,
    w_contactNo int
);

CREATE TABLE Product (
    productID INT PRIMARY KEY,
    productName VARCHAR(30) NOT NULL,
    productDesc VARCHAR(255),
    productPrice DECIMAL(10, 2) NOT NULL CHECK (productPrice >= 0),
    productStock INT NOT NULL CHECK (productStock >= 0),
    categoryID INT NULL,
    warehouseID INT NULL,
    FOREIGN KEY (categoryID) REFERENCES Category(categoryID) ON DELETE SET NULL,
    FOREIGN KEY (warehouseID) REFERENCES Warehouse(warehouseID) ON DELETE SET NULL
);

CREATE TABLE Supplier (
    supplierID INT PRIMARY KEY,
    supplierName VARCHAR(50) NOT NULL
);


CREATE TABLE supplier_product (
    supplierID INT,
    productID INT,
    supplyDate DATE NOT NULL CHECK (supplyDate <= GETDATE()),
    productQuantity INT NOT NULL CHECK (productQuantity > 0),
    PRIMARY KEY (supplierID, productID),
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

CREATE TABLE Supplier_contactNo (
    supplierID INT,
    supplierContactNo INT,
    PRIMARY KEY (supplierID),
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID)
);

CREATE TABLE Customer (
    customerID VARCHAR(6) PRIMARY KEY,
    customerName VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE Customer_contactNo (
    customerID VARCHAR(6),
    customercontactNo INT,
    PRIMARY KEY (customerID, customercontactNo),
    FOREIGN KEY (customerID) REFERENCES Customer(customerID)
);

CREATE TABLE Admin_Salary (
    jobID VARCHAR(7) PRIMARY KEY,
    jobTitle VARCHAR(50) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL CHECK (salary >= 0)
);

CREATE TABLE Admin (
    adminID VARCHAR(7) PRIMARY KEY,
    adminName VARCHAR(100) NOT NULL,
    jobID VARCHAR(7) NOT NULL,
    FOREIGN KEY (jobID) REFERENCES Admin_Salary(jobID)
);

CREATE TABLE Orders (
    orderID VARCHAR(15) PRIMARY KEY,
    adminID VARCHAR(7) ,
    customerID VARCHAR(6) NOT NULL,
	orderDate DATETIME DEFAULT GETDATE(),
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (adminID) REFERENCES Admin(adminID),
    FOREIGN KEY (customerID) REFERENCES Customer(customerID)
);

CREATE TABLE OrderItem (
    orderID VARCHAR(15),
    productID INT NOT NULL,
    itemQuantity INT NOT NULL CHECK (itemQuantity > 0),
    PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID)
);

CREATE TABLE OrderItem_Price (
    productID INT PRIMARY KEY,
    unitPrice DECIMAL(10, 2) NOT NULL CHECK (unitPrice >= 0),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

CREATE TABLE PaymentMethod (
    payID INT PRIMARY KEY,
    method VARCHAR(50) NOT NULL
);

CREATE TABLE Bill (
    billID VARCHAR(10) PRIMARY KEY,
    orderID VARCHAR(15) NOT NULL,
    billDate DATE NOT NULL,
    payID INT NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    FOREIGN KEY (payID) REFERENCES PaymentMethod(payID)
);


INSERT INTO Category VALUES
(1001, 'Smart Watch', 'Watches with smart features'),
(1002, 'Earbuds', 'Bluetooth earbuds'),
(1003, 'Speakers', 'Portable Bluetooth speakers'),
(1004, 'Power Bank', 'High capacity banks'),
(1005, 'Tempered Glass', 'Screen protectors'),
(1006, 'Headphones', 'Over-ear headphones'),
(1007, 'Chargers', 'Fast charging devices'),
(1008, 'USB Cables', 'Type-C and Micro USB'),
(1009, 'Smartphone', 'Latest phones'),
(1010, 'Accessories', 'General accessories');

INSERT INTO Warehouse VALUES
(1, 'Homagama', '0112000000'),
(2, 'Malabe', '0112000001');

INSERT INTO Product VALUES
(1, 'Apple Smart Watch', 'Latest Gen Smart Watch', 42000, 50, 1001, 1),
(2, 'Samsung Earbuds', 'Bluetooth earbuds with ANC', 25000, 30, 1002, 2),
(3, 'JBL Speaker', 'Portable Bluetooth speaker', 38000, 45, 1003, 1),
(4, 'Anker Power Bank', 'High capacity bank', 11000, 60, 1004, 1),
(5, 'iPhone 14 Glass', 'Tempered glass for iPhone', 1500, 100, 1005, 2),
(6,	'Anker Cable',	'Durable USB Cable',	1200.00	,80	,1008	,1),
(7,	'Tempered Glass X',	'iPhone 14 tempered glass', 1500.00	,90	,1005,	2),
(8, 'Sony Headphones',	'Over-ear headphones',	18500.00,	45	,1006,	1),
(9,	'OnePlus Phone','Flagship phone',	15000.00,	30,	1009,	2),
(10, 'Phone Case', 'Shock-proof case',	1800.00,55,	1010,	1);

INSERT INTO Supplier VALUES
(101, 'Kamal Perera'),
(102, 'Dilshan Cooray'),
(103, 'Tech Lanka Pvt Ltd');

INSERT INTO supplier_product VALUES
(101, 1, '2025-05-19', 300),
(101, 2, '2025-05-19', 250),
(102, 3, '2025-05-17', 200),
(103, 4, '2025-05-18', 150),
(103, 10, '2025-05-18', 150),
(102, 8, '2025-05-18', 150),
(103, 7, '2025-05-18', 150);

INSERT INTO Supplier_contactNo VALUES
(101, '0112222222'),
(101, '0771111222'),
(102, '0113333333'),
(103, '0777777777');

insert into Customer (customerID,customerName,address,email)
values
('#CX001' ,	'Tom Silva',	'23/A, Colombo 03',	'tom@email.com'),
('#CX002',	'Nirosha Perera',	'45/B, Kandy'	,'nirosha@email.com'),
('#CX003',	'Kavindu D.',	'12/1, Galle'	,'kavindu@email.com'),
('#CX004',	'Sameera L.',	'34/A, Negombo'	,'sameera@email.com'),
('#CX005',	'Janani G.',	'7/3, Matara'	,'janani@email.com'),
('#CX006',	'Thilina K'	,'16/C, Kurunegala'	,'thilina@email.com'),
('#CX007',	'Kasun N.'	,'10/B, Gampaha'	,'kasun@email.com'),
('#CX008',	'Shanika T.',	'22/A, Colombo 06','shanika@email.com'),
('#CX009',	'Pubudu R.',	'18/D, Nugegoda	','pubudu@email.com'),
('#CX010',	'Ravindu S.',	'50/A, Wattala'	,'ravindu@email.com');

INSERT INTO Customer_contactNo VALUES
('#CX001',  0771111111),
('#CX002',	0772222222),
('#CX003',	0773333333),
('#CX004',	0774444444),
('#CX005',	0775555555),
('#CX006',	0776666666),
('#CX007',	0777777777),
('#CX008',	0778888888),
('#CX009',	0779999999),
('#CX010',	0770000000);

INSERT INTO Admin_Salary VALUES
('#job1', 'Sales Assistant', 200000),
('#job2', 'Inventory Manager',	250000.00);

INSERT INTO Admin VALUES
('ADM01',	'Kamal Silva',	'#job1'),
('ADM02',	'Ruwan Jayasuriya',	'#job2');

INSERT INTO Orders (orderID,customerID) VALUES 
('ORD001','#CX001'),
('ORD002','#CX003'),
('ORD003','#CX004'),
('ORD004','#CX002'),
('ORD005','#CX006'),
('ORD006','#CX007');

INSERT INTO Orders (orderID,customerID,adminID,status) VALUES 
('ORD007','#CX008','ADM01','Accepted'),
('ORD008','#CX005','ADM01','Accepted');

INSERT INTO OrderItem VALUES
('ORD001',2,1),
('ORD002',1,1),
('ORD003',5,1),
('ORD003',4,1),
('ORD005',6,3),
('ORD004',10,1),
('ORD006',7,3),
('ORD007',5,1),
('ORD007',8,1),
('ORD007',10,3);

INSERT INTO OrderItem_Price Values
(1,	55000.00),
(2,	7500.00),
(4	,4500.00),
(5,	8500.00),
(6	,1200.00),
(7,	1500.00),
(8,	18500.00),
(10	,1800.00);

INSERT INTO PaymentMethod VALUES
(1, 'Cash'),
(2, 'Card'),
(3, 'Online'),
(4, 'KOKO Payment');

INSERT INTO Bill VALUES 
('B10','ORD001','2025-05-22',1),
('B11','ORD002','2025-05-22',2),
('B13','ORD003','2025-05-22',3),
('B14','ORD004','2025-05-21',1),
('B15','ORD005','2025-05-22',4),
('B16','ORD006','2025-05-21',2),
('B17','ORD007','2025-05-21',2),
('B18','ORD008','2025-05-22',1);


