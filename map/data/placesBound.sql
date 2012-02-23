SELECT place, user, COUNT(user) FROM data WHERE page="/map/update.php" GROUP BY place,user;
