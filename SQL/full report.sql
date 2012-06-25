SELECT *, sum(report_quantity) FROM reports_list
    INNER JOIN reports
        ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
        
            GROUP BY item_id