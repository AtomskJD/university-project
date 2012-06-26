-- BEGIN MAIN PART
SELECT MONTHNAME(output_date) AS output_month, MONTHNAME(report_date) AS report_month, item_name, unit_name, order_quantity, summ FROM (
    SELECT * FROM orders
        
        LEFT OUTER JOIN 
        (
            SELECT reports.workshop_id AS workshop_id_rep, report_date, item_id as item_id_rep, sum(report_quantity) AS summ FROM reports_list
                INNER JOIN reports
                    ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
                
                        GROUP BY item_id_rep,MONTH(reports_list.report_date) -- without MONTH parameter is quantity for all time 
        ) j1
            
            ON orders.item_id=j1.item_id_rep AND MONTH(orders.output_date)=MONTH(j1.report_date)
        
    UNION (
    SELECT * FROM orders
            
        RIGHT OUTER JOIN 
        (
            SELECT reports.workshop_id AS workshop_id_rep, report_date, item_id as item_id_rep, sum(report_quantity) as summ FROM reports_list
                INNER JOIN reports
                    ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
                
                        GROUP BY item_id_rep,MONTH(reports_list.report_date)
        ) j2
        
            ON orders.item_id=j2.item_id_rep AND MONTH(orders.output_date)=MONTH(j2.report_date)
        ) 
) main1 
-- END MAIN PANT
-- just for advanced data NEED MORE JOINs u'mad >8^(
INNER JOIN items
            ON items.item_id = main1.item_id OR items.item_id = main1.item_id_rep
            
LEFT JOIN units
            ON units.unit_id = items.unit_id
            
LEFT JOIN storages
            ON storages.storage_id = items.storage_id
            
LEFT JOIN workshops
            ON workshops.workshop_id = main1.workshop_id OR workshops.workshop_id = main1.workshop_id_rep
