SELECT user, place, COUNT(user) FROM data WHERE page="/map/update.php" GROUP BY user,place;
