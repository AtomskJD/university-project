<?php
$sql = <<<_SQL
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`workshops`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`workshops` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`workshops` (
  `workshop_id` INT UNSIGNED NOT NULL ,
  `workshop_name` VARCHAR(45) NULL ,
  PRIMARY KEY (`workshop_id`) ,
  UNIQUE INDEX `workshop_id_UNIQUE` (`workshop_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`storages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`storages` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`storages` (
  `storage_id` INT UNSIGNED NOT NULL ,
  `storage_name` VARCHAR(45) NULL ,
  PRIMARY KEY (`storage_id`) ,
  UNIQUE INDEX `storage_id_UNIQUE` (`storage_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`items` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`items` (
  `item_id` INT UNSIGNED NOT NULL ,
  `item_name` VARCHAR(45) NULL ,
  `storage_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`item_id`) ,
  INDEX `fk_items_storages` (`storage_id` ASC) ,
  UNIQUE INDEX `item_id_UNIQUE` (`item_id` ASC) ,
  CONSTRAINT `fk_items_storages`
    FOREIGN KEY (`storage_id` )
    REFERENCES `mydb`.`storages` (`storage_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`orders` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`orders` (
  `workshop_id` INT UNSIGNED NOT NULL ,
  `item_id` INT UNSIGNED NOT NULL ,
  `order_quantity` INT NOT NULL ,
  `output_date` DATE NOT NULL ,
  INDEX `fk_orders_workshops1` (`workshop_id` ASC) ,
  INDEX `fk_orders_items` (`item_id` ASC) ,
  PRIMARY KEY (`workshop_id`, `item_id`, `output_date`) ,
  CONSTRAINT `fk_orders_workshops1`
    FOREIGN KEY (`workshop_id` )
    REFERENCES `mydb`.`workshops` (`workshop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_items`
    FOREIGN KEY (`item_id` )
    REFERENCES `mydb`.`items` (`item_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`reports` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`reports` (
  `report_id` INT UNSIGNED NOT NULL ,
  `workshop_id` INT UNSIGNED NOT NULL ,
  `report_quantity` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`report_id`, `workshop_id`) ,
  INDEX `fk_reports_workshops` (`workshop_id` ASC) ,
  CONSTRAINT `fk_reports_workshops`
    FOREIGN KEY (`workshop_id` )
    REFERENCES `mydb`.`workshops` (`workshop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`reports_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`reports_list` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`reports_list` (
  `workshop_id` INT UNSIGNED NOT NULL ,
  `report_id` INT UNSIGNED NOT NULL ,
  `report_date` DATE NULL ,
  PRIMARY KEY (`workshop_id`, `report_id`) ,
  INDEX `fk_rep-list_reports` (`report_id` ASC) ,
  INDEX `fk_rep-list_workshops` (`workshop_id` ASC) ,
  CONSTRAINT `fk_rep-list_reports`
    FOREIGN KEY (`report_id` )
    REFERENCES `mydb`.`reports` (`report_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rep-list_workshops`
    FOREIGN KEY (`workshop_id` )
    REFERENCES `mydb`.`workshops` (`workshop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`items_has_workshops`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`items_has_workshops` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`items_has_workshops` (
  `items_item_id` INT UNSIGNED NOT NULL ,
  `workshops_workshop_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`items_item_id`, `workshops_workshop_id`) ,
  INDEX `fk_items_has_workshops_workshops1` (`workshops_workshop_id` ASC) ,
  INDEX `fk_items_has_workshops_items1` (`items_item_id` ASC) ,
  CONSTRAINT `fk_items_has_workshops_items1`
    FOREIGN KEY (`items_item_id` )
    REFERENCES `mydb`.`items` (`item_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_items_has_workshops_workshops1`
    FOREIGN KEY (`workshops_workshop_id` )
    REFERENCES `mydb`.`workshops` (`workshop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`reports_has_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`reports_has_items` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`reports_has_items` (
  `reports_report_id` INT UNSIGNED NOT NULL ,
  `reports_workshop_id` INT UNSIGNED NOT NULL ,
  `items_item_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`reports_report_id`, `reports_workshop_id`, `items_item_id`) ,
  INDEX `fk_reports_has_items_items1` (`items_item_id` ASC) ,
  INDEX `fk_reports_has_items_reports1` (`reports_report_id` ASC, `reports_workshop_id` ASC) ,
  CONSTRAINT `fk_reports_has_items_reports1`
    FOREIGN KEY (`reports_report_id` , `reports_workshop_id` )
    REFERENCES `mydb`.`reports` (`report_id` , `workshop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reports_has_items_items1`
    FOREIGN KEY (`items_item_id` )
    REFERENCES `mydb`.`items` (`item_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
_SQL;
try {

$db = new PDO("mysql:host=localhost", 'root', 'pass@word1');
$db->exec($sql);
} catch (PDOException $e){
  die ('Ошибка '. $e->getMessage());
}
?>