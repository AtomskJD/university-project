CREATE VIEW `mydb`.`order_join` AS
SELECT reports.workshop_id AS workshop_id_rep, report_date, item_id as item_id_rep, sum(report_quantity) AS summ FROM reports_list
                INNER JOIN reports
                    ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
                
                        GROUP BY item_id_rep,MONTH(reports_list.report_date) -- without MONTH parameter is quantity for all time