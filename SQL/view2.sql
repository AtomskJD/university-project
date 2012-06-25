SELECT MONTHNAME(output_date) AS Month, item_name, unit_name, order_quantity, SUM(report_quantity) FROM items_has_workshops
    INNER JOIN items
        ON items.item_id = items_has_workshops.items_item_id
    INNER JOIN units
        ON units.unit_id = items.unit_id        
    INNER JOIN workshops
        ON workshops.workshop_id = items_has_workshops.workshops_workshop_id
        
    INNER JOIN orders
        ON orders.workshop_id =items_has_workshops.workshops_workshop_id AND orders.item_id = items_has_workshops.items_item_id
        
    INNER JOIN reports
       ON reports.workshop_id=items_has_workshops.workshops_workshop_id AND reports.item_id=items_has_workshops.items_item_id
       
    INNER JOIN reports_list
        ON reports_list.report_id=reports.report_id AND reports_list.workshop_id=reports.workshop_id
    
    WHERE MONTH(reports_list.report_date) = 6
    
    GROUP BY reports.item_id