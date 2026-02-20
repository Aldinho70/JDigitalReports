SELECT 
    R.id AS 'id_report',
    R.report_date,
    M.full_name AS 'monitor_name',
    R.client_name, 
    R.unit_name, 
    R.report_type, 
    R.comment AS 'comment_report', 

    T.id AS 'id_ticket', 
    T.status AS 'status_ticket', 
    T.resolution_type AS 'resolution_type_ticket', 
    T.assigned_to_technician,
    T.is_billable,
    T.comment AS 'comment_tectechnician'
FROM 
    reports AS R 

INNER JOIN 
    tickets AS T 
    ON T.report_id = R.id

INNER JOIN 
    monitors AS M
    ON M.id = R.monitor_id
