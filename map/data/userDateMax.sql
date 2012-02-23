SELECT user, MAX(date) FROM data WHERE user!="" AND page="/map/update.php" GROUP BY user
