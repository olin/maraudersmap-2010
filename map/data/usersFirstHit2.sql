SELECT user, MIN(DATEDIFF(date, '2008-01-01')) FROM data WHERE page="/map/update.php" GROUP BY user;
