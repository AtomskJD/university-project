-- BEGIN MAIN PART
SELECT * FROM orders
    
    LEFT OUTER JOIN 
    (
        SELECT report_date, item_id, sum(report_quantity) FROM reports_list
            INNER JOIN reports
                ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
            
                    GROUP BY item_id
    ) j1
        
        ON orders.item_id=j1.item_id AND MONTH(orders.output_date)=MONTH(j1.report_date)
    
    UNION (
    SELECT * FROM orders
        
        RIGHT OUTER JOIN 
        (
            SELECT report_date, item_id, sum(report_quantity) FROM reports_list
                INNER JOIN reports
                    ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
                
                        GROUP BY item_id
        ) j2
        
            ON orders.item_id=j2.item_id AND MONTH(orders.output_date)=MONTH(j2.report_date)
    ) 
-- END MAIN PANT