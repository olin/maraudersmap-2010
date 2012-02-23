SELECT DAYOFYEAR(date), COUNT(*) FROM data WHERE page="/map/update.php" GROUP BY DAYOFYEAR(date);
