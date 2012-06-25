-- BEGIN MAIN PART
SELECT * FROM orders
    
    LEFT OUTER JOIN full_report j1
        
        ON orders.item_id=j1.item_id AND MONTH(orders.output_date)=MONTH(j1.report_date)
    
    UNION (
    SELECT * FROM orders
        
        RIGHT OUTER JOIN full_report j2
        
            ON orders.item_id=j2.item_id AND MONTH(orders.output_date)=MONTH(j2.report_date)
    ) 
-- END MAIN PANT
          
        