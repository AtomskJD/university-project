SELECT SUM(report_quantity) FROM reports
    INNER JOIN reports_list
        ON reports_list.report_id=reports.report_id AND reports_list.workshop_id=reports.workshop_id
    
    WHERE MONTH(reports_list.report_date) = 6
    
    GROUP BY reports.item_id