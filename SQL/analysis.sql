SELECT item_name, order_quantity, report_quantity, report_quantity-order_quantity as Surplus FROM items
    INNER JOIN reports
        ON items.item_id = reports.item_id
            INNER JOIN reports_list
                ON reports.report_id = reports_list.report_id 
                AND reports.workshop_id = reports_list.workshop_id
                
            LEFT JOIN orders
                ON items.item_id = orders.item_id
                
                WHERE report_date < '2012-07-1'