-- BEGIN MAIN PART
SELECT MONTHNAME(output_date) AS output_month, MONTHNAME(report_date) AS report_month, item_name, unit_name, order_quantity, summ FROM (
    SELECT * FROM orders
        
        LEFT OUTER JOIN (SELECT * FROM order_join) j1
            
            ON orders.item_id=j1.item_id_rep AND MONTH(orders.output_date)=MONTH(j1.report_date)
        
    UNION (
    SELECT * FROM orders
            
        RIGHT OUTER JOIN (SELECT * FROM order_join) j2
        
            ON orders.item_id=j2.item_id_rep AND MONTH(orders.output_date)=MONTH(j2.report_date)
        ) 
) main1 
-- END MAIN PANT
-- just for advanced data NEED MORE JOINs u'mad >:(
INNER JOIN items
            ON items.item_id = main1.item_id OR items.item_id = main1.item_id_rep
            
LEFT JOIN units
            ON units.unit_id = items.unit_id
            
LEFT JOIN storages
            ON storages.storage_id = items.storage_id
            
LEFT JOIN workshops
            ON workshops.workshop_id = main1.workshop_id OR workshops.workshop_id = main1.workshop_id_rep
