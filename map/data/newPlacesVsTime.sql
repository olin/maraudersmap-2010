CREATE TEMPORARY TABLE temp SELECT time, placename FROM point WHERE 1 GROUP BY placename ORDER BY time;
SELECT DATE(time),COUNT(*) FROM temp WHERE 1 GROUP BY DATE(time);