<meta charset="UTF-8">   /*  <-  НЕ УДАЛЯТЬ !!! чтобы БД поняла какая кодировка */

SHOW VARIABLES;



CREATE TABLE `score_list` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `date` DATETIME ,
    `name` VARCHAR(30) CHARACTER SET utf8mb4 ,
    `score` VARCHAR(20) CHARACTER SET utf8mb4 ,
    PRIMARY KEY (`id`)
) default charset utf8mb4 ;


INSERT INTO `score_list` (
        `date`,`name`,`score`
    ) VALUES (now(),'саша','888'
);

DELETE FROM score_list WHERE id=39;
DELETE FROM score_list WHERE id BETWEEN 33 AND 36;

ALTER TABLE `score_list`
    ADD COLUMN images VARCHAR(20) CHARACTER SET utf8mb4
        AFTER score;