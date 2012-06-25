SELECT report_date, reports.item_id, item_name, sum(report_quantity) FROM reports_list
            INNER JOIN reports
                ON reports.report_id=reports_list.report_id AND reports.workshop_id=reports_list.workshop_id
            INNER JOIN items
                ON items.item_id=reports.item_id
                
            GROUP BY reports.item_id