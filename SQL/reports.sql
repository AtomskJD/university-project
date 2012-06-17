SELECT workshop_name, report_id, item_name, report_quantity FROM reports
    INNER JOIN items
        ON items.item_id = reports.item_id
    INNER JOIN workshops
        ON workshops.workshop_id = reports.workshop_id