use BaloonLK
Go
CREATE PROCEDURE UpdateProductStock
    @ProductID INT,
    @NewStock INT
AS
BEGIN
    
    UPDATE Product
    SET productStock =  productStock + @NewStock
    WHERE ProductID = @ProductID;
END;


EXEC UpdateProductStock @ProductID = 1, @NewStock = 10;


GO
select * from Product


select * from Category

SELECT 
    O.orderID,
    C.customerName,
    P.productName,
    OI.itemQuantity,
    OIP.unitPrice,
    (OI.itemQuantity * OIP.unitPrice) AS TotalPrice,
    B.billID,
    B.billDate,
    PM.method AS PaymentMethod,
	O.status
FROM Orders O
JOIN Customer C ON O.customerID = C.customerID
JOIN OrderItem OI ON O.orderID = OI.orderID
JOIN Product P ON OI.productID = P.productID
JOIN OrderItem_Price OIP ON OI.productID = OIP.productID
JOIN Bill B ON O.orderID = B.orderID
JOIN PaymentMethod PM ON B.payID = PM.payID;

select * from OrderItem
select * from Orders
go

CREATE FUNCTION dbo.GetOrdersByCustomer (@CustomerID INT)
RETURNS TABLE
AS
RETURN (
    SELECT
	O.OrderID,
	O.customerID,
	C.customerName,
	P.productName,
	OI.itemQuantity,
	P.productPrice*OI.itemQuantity as TotalPrice,
	B.billDate as Orderdate

    FROM Orders O
	JOIN Customer C ON O.customerID = C.customerID
	JOIN OrderItem OI on O.orderID= OI.orderID
	JOIN Product P on OI.productID =P.productID
	Join Bill B on  B.orderID = O.orderID
    WHERE C.customerID = @CustomerID);

SELECT * FROM dbo.GetOrdersByCustomer(1);

DROP FUNCTION IF EXISTS GetOrdersByCustomer
GO

CREATE VIEW ProductStock
AS
SELECT 
    p.ProductID,
    p.ProductName,
    c.categoryType,
    p.productStock,
    CASE 
        WHEN p.productStock > 0 THEN 'In Stock'
        ELSE 'Out of Stock'
    END AS AvailabilityStatus
FROM Product p
JOIN Category c ON p.CategoryID = c.CategoryID;

SELECT * FROM ProductStock;

CREATE TRIGGER DecreaseStockAfterOrder
ON OrderItem
AFTER INSERT
AS
BEGIN
    UPDATE Product
    SET productStock = productStock - i.itemQuantity
    FROM Product p
    JOIN OrderItem i ON p.productID = i.ProductID;
END;

insert into OrderItem values(9,1,3);

select * from OrderItem
select * from Orders
select * from Product
select * from Admin
select * from Customer
select * from Category
select * from Warehouse

CREATE VIEW GetPendingOrders
AS
SELECT 
    O.orderID,
    O.orderDate,
    P.productName,
    OIP.unitPrice,
    OI.itemQuantity,
    (OI.itemQuantity * OIP.unitPrice) AS TotalPrice,
    C.customerID,
    C.customerName,
    C.email,
    PM.method AS PaymentMethod,
    O.status
FROM Orders O
JOIN Customer C ON O.customerID = C.customerID
JOIN OrderItem OI ON O.orderID = OI.orderID
JOIN Product P ON OI.productID = P.productID
JOIN OrderItem_Price OIP ON OI.productID = OIP.productID
JOIN Bill B ON O.orderID = B.orderID
JOIN PaymentMethod PM ON B.payID = PM.payID
WHERE O.status = 'Pending';


select * from GetPendingOrders

CREATE PROCEDURE AcceptedOrder 
@adminid VARCHAR(7),
@orderid VARCHAR(15)

AS
Begin

UPDATE Orders
SET adminID = @adminid,
    status = 'Accepted'
WHERE orderID = @orderid;

END

EXEC AcceptedOrder @adminid ='ADM02', @orderid='ORD003' ;
 


