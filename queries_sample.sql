SELECT
	cr.lot, COUNT(cr.id) as redactions, SUM(IF(ISNULL(cr.score), 0, 1)) as corrigidas
FROM
	redactions r JOIN corrector_redaction cr ON r.id = cr.redaction_id
GROUP BY cr.lot
	
