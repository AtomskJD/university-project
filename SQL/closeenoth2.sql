-- BEGIN MAIN PART
SELECT * FROM (
SELECT * FROM orders
    
    LEFT OUTER JOIN full_beta j1
        
        ON orders.item_id=j1.item_id AND MONTH(orders.output_date)=MONTH(j1.report_date)
    
    UNION (
    SELECT * FROM orders
        
        RIGHT OUTER JOIN full_beta j2
        
            ON orders.item_id=j2.item_id AND MONTH(orders.output_date)=MONTH(j2.report_date)
    )
) main1
-- END MAIN PANT
          
        