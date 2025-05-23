use BaloonLK
GO



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





select * from OrderItem
select * from Orders

select * from Product
select * from Customer

GO

CREATE FUNCTION dbo.CustomerDetails (@CustomerID VARCHAR(20))
	RETURNS TABLE
	AS
    RETURN (
    SELECT
        O.customerID,
        C.customerName,
        C.email,
        C.address,
        cc.customercontactNo
    FROM Orders O
    JOIN Customer C ON O.customerID = C.customerID
    JOIN Customer_contactNo cc ON C.customerID = cc.customerID
    WHERE C.customerID = @CustomerID
    GROUP BY O.customerID, C.customerName, C.email, C.address, cc.customercontactNo
    );
SELECT * FROM dbo.CustomerDetails('#CX003');



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



CREATE TRIGGER UpdateProductStock_AfterInsert
ON supplier_product
AFTER INSERT
AS
BEGIN
   
    UPDATE P
    SET P.productStock = P.productStock + I.productQuantity
    FROM Product P
    INNER JOIN inserted I ON P.productID = I.productID;
END;




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
    P.productPrice,
    OI.itemQuantity,
    (OI.itemQuantity * P.productPrice) AS TotalPrice,
    C.customerID,
    C.customerName,
    C.email,
    O.paymentMethod,
    O.status
FROM Orders O
JOIN Customer C ON O.customerID = C.customerID
JOIN OrderItem OI ON O.orderID = OI.orderID
JOIN Product P ON OI.productID = P.productID

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



CREATE PROCEDURE CompletedOrder 
@adminid VARCHAR(7),
@orderid VARCHAR(15)

AS
Begin

UPDATE Orders
SET adminID = @adminid,
    status = 'Completed'
WHERE orderID = @orderid;

END


 

CREATE VIEW GetAcceptedOrders
AS
SELECT 
    O.orderID,
    O.orderDate,
    P.productName,
    P.productPrice,
    OI.itemQuantity,
    (OI.itemQuantity * P.productPrice) AS TotalPrice,
    C.customerID,
    C.customerName,
    C.email,
    O.paymentMethod,
    O.status,
	ad.adminName AS AcceptedBy

FROM Orders O
JOIN Customer C ON O.customerID = C.customerID
JOIN OrderItem OI ON O.orderID = OI.orderID
JOIN Product P ON OI.productID = P.productID
JOIN Admin ad ON O.adminID = ad.adminID
WHERE O.status = 'Accepted';


select * from  GetAcceptedOrders


CREATE VIEW GetCompletedOrders
AS
SELECT 
    O.orderID,
    O.orderDate,
    P.productName,
    P.productPrice,
    OI.itemQuantity,
    (OI.itemQuantity * P.productPrice) AS TotalPrice,
    C.customerID,
    C.customerName,
    C.email,
    O.paymentMethod,
    O.status,
	ad.adminName AS AcceptedBy

FROM Orders O
JOIN Customer C ON O.customerID = C.customerID
JOIN OrderItem OI ON O.orderID = OI.orderID
JOIN Product P ON OI.productID = P.productID
JOIN Admin ad ON O.adminID = ad.adminID
WHERE O.status = 'Completed';