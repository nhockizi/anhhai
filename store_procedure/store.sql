CREATE PROCEDURE `list_data_to_doi`(
 IN `p_from_date` DATETIME,
 IN `p_to_date` DATETIME
 )
BEGIN
  SELECT
    d.`name`,
    d.ID as id,
    IFNULL(SUM(od.SOTAM),0) as sotam,
    IFNULL(SUM(od.METVUONG),0) as metvuong,
    DATE(t.fd) as fd
  FROM dics d
    LEFT JOIN gl_transactions	t
      ON t.`status` = d.`value`
      AND t.fd BETWEEN `p_from_date` AND `p_to_date`
    LEFT JOIN gl_order_details od
      ON t.detailId = od.id
  WHERE d.upcode = 147
  GROUP BY DATE(t.fd),d.`ID`
  ORDER BY DATE(t.fd)
  ;
END;

CREATE DEFINER = CURRENT_USER PROCEDURE `list_data_by_id_to_doi`(
	IN `p_id` Int,
	IN `p_from_date` DATETIME,
	IN `p_to_date` DATETIME
)
BEGIN
	SELECT
		t1.id,
		t1.`code`,
		t1.`name`,
		t1.`value`,
		t1.`doi`,
		SUM(sotam) as sotam,
		SUM(metvuong) as metvuong,
		t1.fd
	FROM
	(
		SELECT
			c.id,
			c.`code`,
			c.`name`,
			c.`value`,
			d.`name` as doi,
			IFNULL(od.`sotam`,0) as sotam,
			IFNULL(od.`metvuong`,0)as metvuong,
			DATE(t.fd) as fd
		FROM gl_transactions t
			JOIN gl_order_details od
				ON t.detailId = od.id
			JOIN dics d
				ON t.`status` = d.`value`
				AND d.`ID` = p_id
				AND d.`upcode` = 147
			JOIN gl_codes c
				ON od.`CODE` = c.`code`
			WHERE t.fd BETWEEN `p_from_date` AND `p_to_date`
	) t1
	GROUP BY t1.id,t1.fd
	ORDER BY t1.fd
	;
END;

CREATE DEFINER = CURRENT_USER PROCEDURE `list_data_by_id_to_doi_id_loai_kinh`(
	IN `p_doi_id` Int,
	IN `p_loai_id` Int,
	IN `p_from_date` DATETIME,
	IN `p_to_date` DATETIME
)
BEGIN
	SELECT
		od.id,
		c.`name` as `loai`,
		t1.`doi`,
		od.`SOTAM` as `sotam`,
		od.`DAI` as `dai`,
		od.`RONG` as `rong`,
		od.`METVUONG` as `metvuong`,
		t1.barcode,
		t1.sodh,
		DATE(t1.fd) as fd
	FROM
		gl_order_details od
		INNER JOIN
			(
				SELECT t.detailId , t.barcode, t.`status` , t.`orderid`, t.fd, d.`name` as `doi`,o.SODH as sodh
				FROM gl_transactions t
				INNER JOIN dics d
					ON t.`status` = d.`value`
					AND d.`ID` = p_doi_id
					AND d.`upcode` = 147
				INNER JOIN gl_orders o
					ON o.id = t.orderid
				WHERE t.fd BETWEEN `p_from_date` AND `p_to_date`
				GROUP BY t.detailId
			) t1
			ON od.ID = t1.detailId
		INNER JOIN gl_codes c
			ON od.`CODE` = c.`code`
			AND c.id = p_loai_id
	ORDER BY DATE(t1.fd)
	;
END;
