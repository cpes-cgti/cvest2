SELECT
	cr.lot, COUNT(cr.id) as redactions, SUM(IF(ISNULL(cr.score), 0, 1)) as corrigidas
FROM
	redactions r JOIN corrector_redaction cr ON r.id = cr.redaction_id
GROUP BY cr.lot
	

SELECT	
	PREDIO, CAMPUS, ESCOLA, ENDERECO, SUM(IF(QUILOMBOLA = 'SIM',1,0)) AS QUILOMBOLA, SUM(IF(QUILOMBOLA != 'SIM',1,0)) AS NAO_QUILOMBOLA, COUNT(INSCRICAO) AS TOTAL_INDIGENAS
FROM
	(
	SELECT
		c.codigo as COD_CAMPUS,
		c.descricao as CAMPUS,
		i.inscricao as INSCRICAO,
		SUBSTRING(i.local, 1, 3) as PREDIO,
		s.quilombola as QUILOMBOLA,
		e.escola as ESCOLA,
		e.endereco as ENDERECO
	FROM
		inscricoes i, isencao s, campus c, locais_escolas e
	WHERE
		i.cpf = s.cpf AND
		SUBSTRING(i.local, 1, 3) = e.codigo AND
		i.campus = c.codigo AND
		i.efetivado = 'S' AND
		(s.indigena = 'SIM' OR s.quilombola = 'SIM')
	) A
GROUP BY PREDIO;


SELECT
	c.codigo as COD_CAMPUS,
	c.descricao as CAMPUS,
	i.inscricao as INSCRICAO,
	i.endereco as ENDERECO,
	i.bairro as BAIRRO,
	i.cidade as CIDADE,
	s.quilombola as QUILOMBOLA,
	SUBSTRING(i.local, 1, 3) as PREDIO,
	e.escola as ESCOLA
FROM
	inscricoes i, isencao s, campus c, locais_escolas e
WHERE
	i.cpf = s.cpf AND
	SUBSTRING(i.local, 1, 3) = e.codigo AND
	i.campus = c.codigo AND
	i.efetivado = 'S' AND
	(s.indigena = 'SIM' OR s.quilombola = 'SIM') AND
	c.codigo = 14;
	