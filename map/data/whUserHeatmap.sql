SELECT place,COUNT(*) FROM data WHERE page="/map/update.php" AND place LIKE "WH%" GROUP BY place;
