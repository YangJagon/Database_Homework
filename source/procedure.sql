select name from mysql.proc where db = 'e_commerce' and `type` = 'PROCEDURE';
select name from mysql.proc where db = 'e_commerce' and `type` = 'FUNCTION';
call add_purchases('p008', 'c001', 'e01', 'pr01', 1);

DELIMITER //
DROP PROCEDURE IF EXISTS SHOW_TABLE;
CREATE PROCEDURE SHOW_TABLE(IN TABLE_NAME VARCHAR(64))
BEGIN
    SET @sentence = concat('SELECT * FROM ', TABLE_NAME, ';');
    PREPARE stmt FROM @sentence;
    EXECUTE stmt;
    SET @sentence = NULL;
END //
DELIMITER ;

DELIMITER //
DROP FUNCTION IF EXISTS MONTH_NUM2ALPHA//
CREATE FUNCTION MONTH_NUM2ALPHA(MON INT) RETURNS VARCHAR(3)
BEGIN 
    DECLARE result VARCHAR(3) DEFAULT '';
    CASE MON
        WHEN 1 THEN
            SET result = 'JAN';
        WHEN 2 THEN
            SET result = 'FEB';
        WHEN 3 THEN
            SET result = 'MAR';
        WHEN 4 THEN
            SET result = 'APR';
        WHEN 5 THEN
            SET result = 'MAY';
        WHEN 6 THEN
            SET result = 'JUN';
        WHEN 7 THEN
            SET result = 'JUL';
        WHEN 8 THEN
            SET result = 'AUG';
        WHEN 9 THEN
            SET result = 'SEP';
        WHEN 10 THEN
            SET result = 'OCT';
        WHEN 11 THEN
            SET result = 'NOV';
        WHEN 12 THEN
            SET result = 'DEC';
        ELSE
            SET result = 'ERR';
        END CASE;
    RETURN result;
END //
DELIMITER;

--Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec

DELIMITER //
DROP PROCEDURE IF EXISTS report_monthly_sale //
CREATE PROCEDURE report_monthly_sale(IN prod_id VARCHAR(4))
BEGIN
    SELECT PNAME, MONTH_NUM2ALPHA(MONTH) AS MONTH, YEAR, SUM(QTY) AS TOTAL_QUANTITY, SUM(TOTAL_PRICE) AS TOTAL_PRICE, SUM(TOTAL_PRICE)/SUM(QTY) AS AVERAGE_PRICE  FROM 
    (SELECT PNAME, MONTH(PTIME) AS MONTH, YEAR(PTIME) AS YEAR, QTY, TOTAL_PRICE FROM PURCHASES NATURAL JOIN PRODUCTS WHERE PID=prod_id) 
    AS T GROUP BY YEAR, MONTH;
END //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS add_purchase //
CREATE PROCEDURE add_purchase(pur_no VARCHAR(4), c_id VARCHAR(4), e_id VARCHAR(3), p_id VARCHAR(4), pur_qty INT)
BEGIN  
    DECLARE price DECIMAL(6,2);
    DECLARE discount DECIMAL(3,2);
    SELECT ORIGINAL_PRICE FROM PRODUCTS WHERE PID=p_id INTO price;
	SELECT DISCNT_RATE FROM PRODUCTS WHERE PID=p_id INTO discount;
    INSERT INTO PURCHASES VALUES(pur_no, c_id, e_id, p_id, pur_qty, CURRENT_TIMESTAMP(), pur_qty*price*(1-discount));
END //
DELIMITER ;

who: left(user(), instr(user(), '@')-1);

CREATE TRIGGER logs_purchases AFTER INSERT ON PURCHASES
FOR EACH ROW
    INSERT INTO LOGS(WHO,TIME,TABLE_NAME,OPERATION,KEY_VALUE) VALUES(left(user(), instr(user(), '@')-1), CURRENT_TIMESTAMP(), 'Purchases', 'Insert', NEW.PURID);

CREATE TRIGGER logs_products AFTER UPDATE ON PRODUCTS
FOR EACH ROW
    INSERT INTO LOGS(WHO,TIME,TABLE_NAME,OPERATION,KEY_VALUE) VALUES(left(user(), instr(user(), '@')-1), CURRENT_TIMESTAMP(), 'Products', 'Update', NEW.PID);

CREATE TRIGGER logs_customers AFTER UPDATE ON CUSTOMERS
FOR EACH ROW
    INSERT INTO LOGS(WHO,TIME,TABLE_NAME,OPERATION,KEY_VALUE) VALUES(left(user(), instr(user(), '@')-1), CURRENT_TIMESTAMP(), 'Customers', 'Update', NEW.CID);


DELIMITER //
CREATE PROCEDURE show_message(mess varchar(256))
BEGIN
    SELECT mess as Message;
END //
DELIMITER ;



DELIMITER //
DROP PROCEDURE IF EXISTS add_purchases //
CREATE PROCEDURE add_purchases(pur_no VARCHAR(4), c_id VARCHAR(4), e_id VARCHAR(3), p_id VARCHAR(4), pur_qty INT)
BEGIN  
    DECLARE price DECIMAL(6,2);
    DECLARE discount DECIMAL(3,2);
    DECLARE theqoh INT;

    SELECT qoh from PRODUCTS WHERE PID=p_id INTO theqoh;
    SELECT ORIGINAL_PRICE FROM PRODUCTS WHERE PID=p_id INTO price;
	SELECT DISCNT_RATE FROM PRODUCTS WHERE PID=p_id INTO discount;

    IF pur_qty <= theqoh THEN
        INSERT INTO PURCHASES VALUES(pur_no, c_id, e_id, p_id, pur_qty, CURRENT_TIMESTAMP(), pur_qty*price*(1-discount));
    ELSE
        CALL show_message('Insufficient quantity in stock.');
    END IF;
END //
DELIMITER ;



DELIMITER //
DROP TRIGGER IF EXISTS qoh_purchases //
CREATE TRIGGER qoh_purchases AFTER INSERT ON PURCHASES
FOR EACH ROW
BEGIN
    DECLARE old_qoh INT;
    DECLARE thredshold INT;
    DECLARE new_qoh INT;
    DECLARE vm INT;
    DECLARE lvt DATETIME;
    
    SELECT QOH, QOH_THRESHOLD FROM PRODUCTS WHERE PID = NEW.PID INTO old_qoh, thredshold;
    SET new_qoh = old_qoh - NEW.QTY;
    IF new_qoh < thredshold THEN
        SET new_qoh = old_qoh*2;
    END IF;
    UPDATE PRODUCTS SET QOH = new_qoh WHERE PID = NEW.PID;

    SELECT VISITS_MADE FROM CUSTOMERS WHERE CID = NEW.CID INTO vm;
    UPDATE CUSTOMERS SET VISITS_MADE = vm + 1, LAST_VISIT_TIME = CURRENT_TIMESTAMP() WHERE CID = NEW.CID;
END //
DELIMITER ;