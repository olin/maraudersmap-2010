SELECT DAYOFYEAR(date), COUNT(*) FROM data WHERE page="/map/ui/mapui.php" GROUP BY DAYOFYEAR(date);
SELECT DAYOFYEAR(date), COUNT(*) FROM data WHERE page="/map/ui/map_backend.php" GROUP BY DAYOFYEAR(date);
