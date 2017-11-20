DROP VIEW IF EXISTS view_todos;
DROP TABLE IF EXISTS todos;

CREATE TABLE todos
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(93) NOT NULL,
    title VARCHAR(255) NOT NULL,
    completed BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE SQL SECURITY DEFINER VIEW view_todos AS
SELECT *
FROM todos
WHERE username = USER();

DELIMITER $$
CREATE TRIGGER trigger_todos_insert BEFORE INSERT ON todos FOR EACH ROW
BEGIN
    SET NEW.username = USER();
END$$

CREATE TRIGGER trigger_todos_update BEFORE UPDATE ON todos FOR EACH ROW
BEGIN
    SET NEW.username = USER();
    IF (OLD.username <> NEW.username) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Unauthorized content update.';
    END IF;
END$$

CREATE TRIGGER trigger_todos_delete BEFORE DELETE ON todos FOR EACH ROW
BEGIN
    IF (OLD.username <> USER()) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Unauthorized content delete.';
    END IF;
END$$
DELIMITER ;
